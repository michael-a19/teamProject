<?php
include 'config/config.php';

#create a json recordset object and pass to the router class, recordset is responsible for communicating with the database
#databasepath is the path to the database that is stored in the config.ini file
$recordSet = new RecordSet($ini['main']['database']['databasepath']); //$ini['main'] is defined in config.php, it is an associative array created from contents of config.ini 
//echo($recordSet->checkConn());
//echo($ini['main']['database']['databasepath']);
$basepath = $_SERVER['REQUEST_URI']; 
//echo($basepath); 
/**
 * pass the recordset object to the router class and save the response to the page variable 
 * The router class looks at the url that the request came on and decides what data to return, so for example if the request is on www.example.com/students the router looks at the last part 
 * the /students part and knows the request is for student information, the router will then send for that data and return that data which will be stored in $page
 */
$pageData = new Router($recordSet); 
//echo("working to here");
/**
 * the page variable above is passed to view so that it can be returned to the client as a response with the correct headers so if the request was for json data about students
 * thge student info will be returned from router and passed to view, view will see that this data is for a json request and will set the json headers so the request is valid
 */
new View($pageData);
?>