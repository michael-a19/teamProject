<?php

class DBConn {

    //static instance of the db connection following the singleton pattern
    private static $dbconn = null; 

    private function __construct(){}

    public static function getConnection($dbName){
        
        if(!self::$dbconn)
        {
            try {
               //database credentials 
                $host = "localhost";
               $dbName = "teamproj";  //change this to your db name
               $dbPassword = "" ;      //change this to your db password
               $dbUsername = "root";   //change this to your db username
               $dbDSN = "mysql:host={$host};dbname={$dbName}";
               self::$dbconn = new PDO($dbDSN,$dbUsername,$dbPassword); 
                //self::$dbconn = new PDO("sqlite:".$dbName);//  this is for the sqlite file (just for testing)
                self::$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch( PDOException $e) {
                echo $e->getMessage();
            }
        }
        return self::$dbconn;
    }
}

?>

