<?php

include('boot.php');
 
$data = nomc($_POST['data']);
$data = json_decode($data,true);

// output a json return message array (each index is a new message)
echo "[";

$add = "";

if(isLoggedIn())
  foreach($data as $t => $v)
    {
    
    // print_r($v);
    // Array
    // (
    //  [t] => phone
    //  [v] => 5308771956dd
    // )
    
    // $r = processMessage($v);
  
    $t = trim($v['t']);
    $v = $v['v'];
    $fn = "$t.html";
    $fpn = "$data_path/$fn";
    
    //save file
    zoldfile($fpn,$v);
    $r = array(
      //"path" => md5$fpn, // for debuggin 
      "data" => $v
     );
  
  
    if(isset($r))
      {
      echo $add.json_encode($r); //for example, returns the index numbers
      $add = ",";
      }//if
      
    }//foreach
  
echo "]";


// might be good to delete cache here too (older than x time)

?>