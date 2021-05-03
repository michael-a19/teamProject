<?php

class DBConn {
    

    private static $dbconn = null; 

    private function __construct(){
        
    }
   
    public static function getConnection(){
        $host = "localhost";
        $username = "root";//unn_w18032122";//"root"; uni password with unn_
        $password = "";//Kate1991b";//"";
        $dbName = "group_database"; //uni password with unn_/*"unn_w18032122";*/
        $dbDSN = "mysql:host={$host};dbname={$dbName}";
        if(!self::$dbconn)
        {
            try {
                self::$dbconn = new PDO($dbDSN,$username, $password);
                //self::$dbconn = new PDO("sqlite:".$dbName);
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

