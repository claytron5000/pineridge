<?php
 // 2009.04.01

function tryLogin($key)
{
global $login_key;
global $data_path;

$result = false;

$try = strtolower($login_key);
$key = strtolower($key);
if($key == $try)
  {
  $bid = getBrowserId();
  $fn = "$data_path/$bid.txt";
  if(file_put_contents($fn,print_r($_SERVER,true)))
    $result = true;
  }
  
return $result;
}//func

function tryLogout()
{
global $data_path;
$result = false;

  $bid = getBrowserId();
  $fn = "$data_path/$bid.txt";
  $base = dirname($fn); //we'll check to be sure we're writing to the expected folder
  if($base == $data_path)
    {
    if( @unlink($fn) ); // wisper so not to see data path
    $result = true;
    }
    
return $result;    
}//func


function isLoggedIn()
{
global $data_path;

$bid = getBrowserId();
$fn = "$data_path/$bid.txt";

return(file_exists($fn));
  
}//func
 
function zoldfile($filename, $str) {

 if (file_exists($filename)) {
  $filepath = dirname($filename);
  $oldfilename = basename($filename);
  $newfilename = $filepath."/zold".date("Y.m.d.U.").$oldfilename;
  //zold the name and write the contents to a new file with $filename  

  if (rename($filename,$newfilename)) {
   file_put_contents($filename, $str);
  } else {
   file_put_contents($filename, $str);
  }

 } else {
  //save the contents to a file with $filename
   file_put_contents($filename, $str);
 }

}

function solvefordesign($field)
 {
 global $data_path;
 //echo "HI!";

 $a = explode("#",$field);
 if ((count($a) == 2) && (strlen(trim($a[1])) > 0)) {
  $tag = $a[1];
  $tag = str_replace(array('..','/',"\\"),"",$tag);//'
  
  //locate the file name associated with $a[1] and load its contents
  if (file_exists($data_path."/$tag.html")) {
  $tag_content = file_get_contents($data_path."/$tag.html");
  } else $tag_content = "click to edit";
  
  $o = "<div id='$tag' class='editable' contenteditable>$tag_content</div>";
  return $o;
  } //if

 } //func

 
function solveforview($field)
 {
 global $data_path;
 //echo "HI!";

 $a = explode("#",$field);
 if ((count($a) == 2) && (strlen(trim($a[1])) > 0)) {
  $tag = $a[1];
  $tag = str_replace(array('..','/',"\\"),"",$tag);//'
  
  //locate the file name associated with $a[1] and load its contents
  if (file_exists($data_path."/$tag.html")) {
  $tag_content = file_get_contents($data_path."/$tag.html");
  } else $tag_content = "";
  
  $o = "$tag_content";
  return $o;
  } //if

 } //func
 

?>
