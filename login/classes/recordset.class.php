<?php
   # include_once('../include/autoloader.inc.php');
    include_once('dbconn.class.php');
    class RecordSet {
        private $conn;
        private $stmt;
        //create the db connection here and shit and pass in 
        public function __construct($dbname){
            $this->conn = DBConn::getConnection($dbname);
        }
        
        //method for setting, getting, deleting etc spereate functions, delete return number of rows effected 
        function getRecordSet($query, $params = null) {
            #check if the params is an array, if so execute as prepared statement
            if(is_array($params)) {
                #if array prepare the statement
                $this->stmt = $this->conn->prepare($query); 
                #execute the prepared statement with params 
                $this->stmt->execute($params);
            }
            else {
                $this->stmt = $this->conn->query($query); 
            }
            #make the returned recordset an associative array 
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC); 
        }



      function writeToDB($query, $params = null) {

         if(is_array($params)) {
             $this->stmt = $this->conn->prepare($query); 
             #execute the prepared statement with params 
             $dave =  $this->stmt->execute($params);
         }
         else {
            $dave = $this->stmt = $this->conn->query($query); 
         }
         #make the returned recordset and associative array 
         //if(return $this->stmt->fetchAll(PDO::FETCH_ASSOC);) 
         if($dave){
             return true;
         }
         else{
             return false;
         }
      }
    }

?>