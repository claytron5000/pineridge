<?php

///////////////////////////////////////////
//
// pineridgeburners.com -- cusomizable code base
//
////////////////////////////////////////
//
// ver-history
//     2008.08.19
//
//
//
 
//session_start();

include("boot.php");

$args = kpGetArgs();
// [0] = here
// [1] = is

//exit;

//logging in
 if(isset($args[0]) && ($args[0] == $login_key))
   {
   if(tryLogin($args[0]))
     $just_logged_in = true;
   }//if
   
// logging out   
if(isset($_GET['logout']))
   {
   //try logout
   if(tryLogout())
     $just_logged_out = true;
   }//if
   
   

$page = new render3();

$page->resolve_messages = false; //no error messages

$page->set('r',$kpURLRoot);
$page->set('m',"$kpURLRoot/media");
$page->set('l',"$kpURLRoot/$lib_path_root");


$page->set('year',date("Y")); //can we make this global part of render? (if not found.. like a standards plugin)

$request = kpGetRequestPath();
// /here/is/where/we/are

$page->set("full-req-path",$request);// /here/is/where/we/are
//$r = str_replace("/","",$request);
$r = explode("/",$request);
$r = $r[1];
$page->set("nav-$r",'here');

if($r == '')
{ $r = 'home';
  $request = "/home";
}

if(isset($just_logged_out)) //override content on logout
  {
  $r = 'logged-out';
  $request = "/logged-out";
  } 


 if(isLoggedIn())
   {
   //hook into solve for function.
   $page->set("mode-header","{{build-header}}");
   $page->solvefor_func_name = 'solvefordesign';  
   }
 else
   {
   $page->set("mode-header","<!-- no extra header info -->");
   $page->solvefor_func_name = 'solveforview';
   }    

//$page->set("white","#FFF");
//$page->set("gray",'#222');





$effective_page_path = "$content_path_root$request"; 

$a = $effective_page_path; // ./pages/contact-simtek
//echo $a;exit;
$page->addpath($a);
// ./abc/123


// ADDL. ////////////////////////////
$l = array(
        "ignorethis"
          );
if(isset($args[0]) && in_array($args[0],$l))
  {
  include("go-".$args[0].".php");
  }


// ALT FORKS ////////////////////////////
$l = array(
        "proload",
        "bitbucket"
          );
if(isset($args[0]) && in_array($args[0],$l))
  {
  include("go-".$args[0].".php");
  exit;
  }
  
 
//dbug("req:[$request]");
$d = kpGetDesignPath($effective_page_path,"design-select");

//echo "[$effective_page_path-$d]";
$design_path = $d;


$render_from = "$design_path_root/$design_path";
// echo "[$render_from]"; 
// RENDERING FROM [./layout/home]


  echo $page->render("{{start}}","$render_from");

?>
