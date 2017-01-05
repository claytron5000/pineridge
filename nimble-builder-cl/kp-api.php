<?php

/////////////////////////////////////////////////////////
//
// i -- .htaccess baised layout rendering
//
//

// .htaccess in root folder 
/* 
#FROM DRUPAL 5 2007.05.08

# Various rewrite rules.
<IfModule mod_rewrite.c>
  RewriteEngine on

  # If your site can be accessed both with and without the prefix www. you
  # can use one of the following settings to force user to use only one option:
  #
  # If you want the site to be accessed WITH the www. only, adapt and
  # uncomment the following:
  # RewriteCond %{HTTP_HOST} ^example\.com$ [NC]
  # RewriteRule .* http://www.example.com/ [L,R=301]
  #
  # If you want the site to be accessed only WITHOUT the www. prefix, adapt
  # and uncomment the following:
  # RewriteCond %{HTTP_HOST} ^www\.example\.com$ [NC]
  # RewriteRule .* http://example.com/ [L,R=301]


  # Rewrite current-style URLs of the form 'index.php?q=x'.
  RewriteCond %{REQUEST_FILENAME} !-f
  #RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ i.php?i=$1 [L,QSA]
</IfModule>

# $Id: .htaccess,v 1.81 2007/01/09 09:27:10 dries Exp $
*/


//ignore case.
$_GET['i'] = strtolower($_GET['i']);

// http://s3/clients/freebeelistings.com
$kpURLRoot = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);

//no ending slash
while($kpURLRoot[strlen($kpURLRoot)-1] == "/")
  $kpURLRoot = substr($kpURLRoot,0,strlen($kpURLRoot)-1);


///////////////////////////
function kpGetArgs()
{//  [0] => abc
 //  [1] => 123
 //  [2] => 46k
 //  [3] => hello.jpg
 // /abc/123/46k/hello.jpg
 // 
 
$args = kpGetRequestPath();

$args = explode("/",$args);
array_shift($args);

//no args
if((count($args) == 1) && (strlen(trim($args[0])) == 0))
  $args = array();

return $args;
}//function


////////////////////////////////////
function kpGetRequestPath()
{// /here/is/our/path/request
 // Request path from url address request.
 
 
//from mod rewrite
//print_r($_GET);
//Array ( [q] => ace/happyday/ )
 
 
$q = $_GET['i'];

$path = "/$q";

//we don't want a trailing slash
if(strlen($path) > 1)
   if ($path{strlen($path)-1} == "/")
        $path = substr($path,0,strlen($path)-1);

        
return $path;
}//function



////////////////////////
function kpGetDesignPath($path,$title = "framework")
{
// search for design-path.html
// /contact

$r2 = new render3();

$r2->resolve_messages = false;

$dp = $r2->render("{{".$title."}}","$path");

return $dp;
}//func





///////////////////////////////////////////////////////////////
// rev-history 2007.08.16
//

?>
