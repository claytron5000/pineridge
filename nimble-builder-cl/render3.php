<?php
//
// nimble: 
//   2009.03.19 [add support for external field to text (solvefor) resolution]
//   2009.02.20 [render default folder "./" (was "")] 
//   2008.08.28 [verision key file search to find all]
//

// COPYRIGHT 2009 Q9 DESIGN


class render3
{
    var $params = array();
    var $paths = array();
    var $stop_file_name = false;
    var $resolve_messages = true;
    
    var $solvefor_func_name = false; // set this to a function name to call your own solve for on unresolved by r3.
    
    
    function render($text,$start_path = "./")
    {
    $start_path = str_replace("\\","/",$start_path);
    
    //we want to end path in /
    if(strlen($start_path) > 0)
      {
        if($start_path[strlen($start_path)-1] !== "/")
          $start_path .= "/";
      }//if
    
    
    $dl = "{{";
    $dr = "}}";

    
    $delims = array();
    $cursor = 0;
    
    //find delims
    while(($start = strpos ( $text, $dl , $cursor)) !== false) //find next starting delem
                      {
                        //find end of field
                        if(($end = strpos ( $text, $dr, $start)) === false) //find next ending delem (if there is one)
                            //assume end of text for end
                            $end = strlen ( $text );
                            
                            
                        //add pair
                        $s = $start+2; //field content
                        $e = $end-1;
                        $field_data = substr($text,$s,$e-$s+1);
                        $delims[] = array($s,$e,$field_data); //[0] pos of first char. [1] pos of last char (in the field.. not the delims.)
                        
                        $cursor = $e+3;
                      }//while
                      
    
    //echo "[$text]";
    // print_r($delims);
    // {{layout}} and some more {{fields}} yay!]
    //    [0] => Array    
    //        (
    //            [0] => 2
    //            [1] => 7
    //            [2] => layout
    //        )
    //
    //    [1] => Array
    //        (
    //            [0] => 25
    //            [1] => 30
    //            [2] => fields
    //        )

    

    // BUILD OUTPUT
    $o = "";
    $cursor = 0;

    //process delims
    if(count($delims) > 0)    
      {
      
        //each delim
        foreach($delims as $v)
          {
          $s = $v[0];
          $e = $v[1];
          $f = $v[2];
          
          
          //everything up to this fields
          $o .= substr($text,$cursor,$s-$cursor-2);
          
          //pull content for this field.. process it and add to output
          $o .= $this->render($this->solvefor($f,$start_path), $start_path); 
          
          $cursor = $e+3;
          }//foreach
          
          
        //rest of text
        $o .= substr($text,$cursor);
        
       }//if
    else //nothing to process so let's just use the provided text as is.
      $o .= $text;
   
    return $o;
    }//render
    
    
    function solvefor($field,$start_path)
    {//Translates a field value to text content.
    
      //echo "[$field][$start_path] .. ";
      //try params
      if(isset($this->params[$field]))
        return $this->params[$field];
        
      if($fpn = $this->findfile($field.".html",$start_path))
        return(trim(file_get_contents($fpn)));
      
      //try function name -- nimbleResolutionFunction() -- returns text
      $nfn = $this->nimbleFunctionName($field);//insures to add "nimble" to the begining.
      if(function_exists($nfn))
        return $nfn();
  
  
      //get a little outside help (if volunteered)
      if($this->solvefor_func_name != false)
        {
        // return your text replacement to 'field' or false if you donno.
        $xsf = call_user_func($this->solvefor_func_name,$field,$start_path);
        if($xsf != false)
          return $xsf;
        }//if
  
      
      //no find
        if($this->resolve_messages)
           return "[unresolved: $field]";
        else
           return false;
  
    }//function
    
            
    
    function findfile($filename,$start_path)
      {//search current settings to find matching file.



        //let's try added paths
        foreach($this->paths as $v)
          {
          //echo "[$v]";
          //local/
          
          $f = $v.$filename;
          //echo "[$f]";      
          //global/layout.html
          
          //ok?
          if(file_exists($f))
            return $f;
              
          }//foreach



        //Hmmmm..no?... let's design path tree
      
        //search for redirect design tree director file?
        $start_path = str_replace("\\","/",$start_path);
        $folders = explode("/",$start_path);
        
        // print_r($folders); //./procedures/
        // [0] => .
        // [1] => procedures
        // [2] =>
         
        unset($folders[count($folders)-1]);
        // print_r($folders);
        // [0] => .
        // [1] => procedures
        
       //false if not found
       $c = count($folders);
       foreach($folders as $i => $v)
         {
         
         //build search path string
         $ac = $c-$i;
         $f = "";
         for($r = 0;$r < $ac;$r++)
            $f .= $folders[$r]."/";
         // ./procedures/vertebroplasty/
         
         $f .= $filename;
         //echo "[$f]";
         //./procedures/vertebroplasty/layout.html
         
         if(file_exists($f))
            return $f;
        }//foreach
        
        
      

          return false;
      }//func
    
    
    function nimbleFunctionName($str)
      {
      //Hello-this  is field
      //Hello_thisisfield (nospaces and dashes to underscore)
      $str = str_replace("-","_",$str);
      $str = str_replace(" ","",$str);
      $str = "nimble$str";
      return $str;
      }
    
    
 /*   function setstop($filename)
    {//$filename = 'render2stop'
     //not yet supported.. for now we assume we running from root
    $this->stop_file_name = $filename;
    }//render
*/    
    
    
    function set($tag,$value)
    {
    $this->params[$tag] = $value;
    }//render
    
    
    function get($tag)
    {
    return $this->params[$tag];
    }//render
    
    
    
    function importparams($array)
    {
    foreach($array as $t => $v)
      $this->setparam($t,$v);
    }//render
    
    
    function addpath($path)
    {
     //we want to end path in /
    if(strlen($path) > 0)
      {
        if($path[strlen($path)-1] !== "/")
          $path .= "/";
      }//if
      
    $this->paths[] = $path;
    }//render
    

    
    
}//class


////////////////////
// LOG
//
//
//  2008.08.28 : Added 'nimble' function resolution. [note: we could also have a "registered" function name method for resolutions so don't need to have the nimble prefix.]
//  2007.05.11 : Add importparams function.
?>
