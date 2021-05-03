<?php

class DBConn {
    

    private static $dbconn = null; 

    private function __construct(){
        
    }
   
    public static function getConnection(){
        $host = "localhost";
        $username = "unn_w";//"root"; uni password with unn_
        $password = "";//"";
        $dbName = "unn_w";//"group_database"; uni password with unn_
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

