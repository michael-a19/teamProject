<?php
   # include_once('../include/autoloader.inc.php');
    include_once('dbconn.class.php');
    class RecordSet {
        private $conn;
        private $stmt;
        public function __construct(){
            $this->conn = DBConn::getConnection();
        }
        
        //method for setting, getting, deleting etc sepreate functions, delete return number of rows effected 
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
             $success =  $this->stmt->execute($params);
         }
         else {
            $success = $this->stmt = $this->conn->query($query);
         }
         if($success){
             return true;
         }
         else{
             return false;
         }
      }
    }

?>