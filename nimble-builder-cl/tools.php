<?php

global $browser_id;

function sureBrowserId()
{   //makes sure we have a browser id in script and in browser.
    // this brand persists even thru session changes (browser closing)
    //
    // 2009.03.27
    //
    global $browser_id;
    $time = time();

    $cookie_id = 'browser_id_20090327';
    if(isset($_COOKIE[$cookie_id])) // do we already have a branding?
      {
      $browser_id = $_COOKIE[$cookie_id]; // cool.. let's use that one.
      }
    else
    {
      $browser_id = Date("Y",$time).".".Date("m",$time).".".Date("d",$time).".".md5(print_r($_SERVER,true).mt_rand()); //we MD5 and rand seed the session.. we don't what session hijacking thru guessing!
     // kp_brand = '2006.05.08.eb61870ad36e328f61f0d3d592bf805c'
     //create a new brand (if we're allowing such.)
      setcookie ( $cookie_id , $browser_id, 2147483647,"/");  
    }

}//func


function getBrowserId()
{
global $browser_id;
return $browser_id;
}//func


function nice4sql($t)
{
return mysql_real_escape_string($t);
}

function nice4js($t)
{
$t = str_replace(array("\n","\r"), array('\n','\r'), $t);
return $t;
}

 
function nice4whitespace($string)
{ 
 return  preg_replace('/\s+/', ' ', $string);
} 




function mysqlSelectArray($q)
{//need to sql protect values before this

 $q = mysql_query($q);
 
 $result = array();
 
 if($q !== false)
 while($row = mysql_fetch_assoc($q))
 {
 $result[] = $row;
 }//while
 
 return $result;
}


function niceq($q)
{ 
  // "select * from chat 
  //     where 
  //        ref_doc = [$docid] and
  //        udate >= [$since]";

  $b = -1;
  $quit = 9999;

  while( ($b = strpos($q,'[',$b+1)) !== false )
    {
    $e = strpos($q,']',$b);

    if($e !== false)
     {
     $bit = substr($q,$b,$e-$b+1);
     //$b = $e; // skip to end incase there's some opening [ in the section
     $a = $b+1;
     $l = $e-$a;
     $s = substr($q,$a,$l);

     $r = mysql_real_escape_string($s);
     
     $q = substr_replace($q,$r,$b,$e-$b+1);
     }
     
     if($quit-- <= 0)
       {
       return "ABORT!\n"; 
       }
       
    }//while
        
  $q = str_replace(
          array("[","]"),
          "",$q); //remove any stragglers
          
  return $q;        
}//func



function standardPath($p)
{// /this/is/a/path
 // standarize...start with slash.. no ending slash
 
$p = strtolower($p);

//remove ending slashes
while((strlen($p) > 1) && $p[strlen($p)-1] == "/")
  $p = substr($p,0,strlen($p)-1);
  
 
if( (strlen($p) == 0) || ($p[0] != "/") )
  $p = "/$p";

return $p;
}


function nomc($i)
{// works on single value or 1D array. (like $_GET)
$result = $i;

if (get_magic_quotes_gpc()) 
   {
   if(is_array($i))
     {
     foreach($i as $t => $v)
       $result[$t] = stripslashes($v);
     }//if
   else
     $result = stripslashes($i);
   }//if
return $result;
}//func


function plural($v,$plural_option,$singular_option)
{
if(($v == 0) || ($v == 1)) 
   return $singular_option;
else   
   return $plural_option;
}//func

function ago($ago_date)
{ // returns string like..
  //   New
  //   5 minutes ago
  //   2 hours ago
  // 213 days ago
  //
  // 2008.10.21
  //
 $now = time();
 $delta_time = $now - $ago_date;
 $t = "";
 
  if($delta_time < 60)
           $t = "new";
        else
        if($delta_time < 60*60)
           {
           $d = round($delta_time / (60));
           $t = "$d minute".plural($d,"s","")." ago.";
           }//if
        else
        if($delta_time < (60*60*24))
           {
           $d = round($delta_time / (60*60));
           $t = "$d hour".plural($d,"s","")." ago.";
           }//if
        else
           {
           $d = round($delta_time / (60*60*24));
           $d = number_format($d);
           $t = "$d day".plural($d,"s","")." ago.";
           }//if
           
        return $t;   
}//function


?>
