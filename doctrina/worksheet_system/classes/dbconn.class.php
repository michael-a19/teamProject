<?php

class DBConn {
    

    private static $dbconn = null; 

    private function __construct(){
        
    }

    public static function getConnection(){
        $host = "localhost"; //leave as localhost
        $username = "unn_w17009405"; 
        $password = "G3ltw00D19992019";
        $dbName = "unn_w17009405";
        $dbDSN = "mysql:host={$host};dbname={$dbName}";
        if(!self::$dbconn)
        {
            try {
                self::$dbconn = new PDO($dbDSN,$username, $password);
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

