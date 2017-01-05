<?php
/////////////////////////////////////////////////////////
//
// /proload/text/css/filename.css
//
// runs /proload/text/css/filename.css.html thru render process.
//

$proload_source = ".";

//we should prob detect mime type.. but for now... we'll let that be specified.
$mime = $args[1]."/".$args[2];
header("Content-type: $mime");


$str = $page->render("{{".$args[3]."}}","$proload_source");

header('Content-Length: '.strlen($str));
echo $str;


?>
