<?php 
/**
* PDO (PHP Data Objects) Database Connection
* Create a singleton SQLite database connection
*/

class PDOdb
{
  //private $host = "";
  //private $user = "";
  //private $pwd = "";
  //private $dbName = "";
  private static $dbConnection = null; #to hold an instance of the PDOdb class 
 //private $dbConnection; #only needed for the mysql connection as not creating a databse yet or object of this
    /**
     * Constructor for pdo database connestion 
     * singleton pattern to prevent multiple instances
     */
    private function __construct()
    {
        //when changing from sqlite to actual database need to implement this to hold the actual object of hte basebase connection
        #$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
        #dbConnection = PDO #create the pdo thingy here and then can return it in the static function 
    }

    /**
     * Return DB connection or create initial connection or creat initial connection 
     * @return object (PDO connection)
     * @param dbname the dataabase name
     */
    public static function getConnection($dbname)
    {
        if( !self::$dbConnection ) #if the static variable dbconnection is not set then set it if made then just return it
        {
            try #remove try catch block later when using the exception handler 
            {
                
                self::$dbConnection = new PDO("sqlite:".$dbname); #create new pdo connection to sqlite database, if specified sqlite database does not exist then it will be created as a new file
                self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); #attr_errmode - sets database handle to report errors ERRMODE_EXCEPTION throws exceptiopns 
            }
            catch( PDOException $e ) {
                echo $e->getMessage(); #shouldn't echo this for assessment. will be removed when implementing the error handler. 
            }
        }
        return self::$dbConnection; #return the pdo object that holds a connection to the database 
    }
}
?>