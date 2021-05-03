<?php
//connect.php
//starting the session if not already started
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
// variables of server data
$server	    = 'localhost';
$username	= 'unn_w18016014';
$password	= 'OEU0MJLC';
$database	= 'unn_w18016014';

//error handling
if(!mysql_connect($server, $username, $password))
{
	//if server, username and password could not connect, give error message and terminate script
 	exit('Error: could not establish database connection');
}
if(!mysql_select_db($database))
{
	//if database could not connect, give error message and terminate script
 	exit('Error: could not select the database');
}
?>
