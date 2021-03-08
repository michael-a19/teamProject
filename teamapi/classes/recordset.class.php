<?php 
/**
 * Recordset class to execute sql queries and return the results. 
 */
class RecordSet {

    private $conn; 
    private $stmt; 

    function __construct($dbname) {
        #create a connection to the database from the pdodb class
        $this->conn = PDOdb::getConnection($dbname);
    }

    /**
     * Execute the given sql query as a pdo prepared statement if the query has associated params
     * if the query has no params then execute the query as a standard statement
     * 
     * @param String :the sql query
     * @param array_of_strings: the associated parameters for the sql (represents the placeholder values for the prepared statement)
     * @return PDO_STATEMENT
     */
    function getRecordSet($query, $params = null) {
        #check if the params is an array, if so execute as prepared statement
        if(is_array($params)) {
            $this->stmt = $this->conn->prepare($query); 
            #execute the prepared statement with params 
            $this->stmt->execute($params);
        }
        else {
            $this->stmt = $this->conn->query($query); 
        }
        #make the returned recordset and associative array 
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    function checkConn(){
         if($this->conn){
            return "true";
         }
         else 
         {  
             return 'false';
         }
    }
}

