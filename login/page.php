<?php
session_start();
$_SESSION['loggedIn'] = True;
$_SESSION['lname']= "test";
$_SESSION['fname']= "test";
include_once("functions2.php");
include_once("./includes/pagefunctions.inc.php");
echo pageStart("le title","./styles/style.css");
echo createNav();
echo createBanner();
echo "<main>";

echo "</main>";


echo pageEnd();
?>