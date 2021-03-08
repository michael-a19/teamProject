<?php

/**
 * class autoloader 
 * 
 */
function autoloadClasses($className) {
    #create the full path to the class, double slash to escape first slash
    $filename = "classes\\" . strtolower($className) . ".class.php";
    #replace double slash with the directory seperator of current OS
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
    #if filename is valid include it in the index page, this imports the specified class
    if (is_readable($filename)) { 
      include_once $filename;
    } else {
      throw new exception("FILE NOT FOUND: " .$className . " (". $filename . ")" );
    }
   
   }
   #register autoloader 
   spl_autoload_register("autoloadClasses");


#create an associative array from the contents of config.ini    
$ini['main'] = parse_ini_file("config.ini",true);

/**
 * define global constants for various paths
 */
define("BASEPATH", $ini['main']['paths']['basepath']); #the URL base path for this website
define('JWTKEY', $ini['main']['jwtkey']['jwtkey']); #jwtkey to use when creating webtokens 
define('CSSPATH', $ini['main']['paths']['csspath']); #path to the css style for the documentation page of this website

/**
 * TODO create error and exception handling 
 */