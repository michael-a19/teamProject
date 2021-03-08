<?php
/**
 * A view class, sets the relevant headers for each page type
 * 
 * @author Michael Anderson, Student ID: w18032122
 * 
 */
    class view
    {
    
        public function __construct($pageData)
        {
            $pageData->getType() == 'JSON' #set header appropriate to the page being requested 
            ? $this->JSONheaders()
            : $this->HTMLheaders();

            echo $pageData->getPage();
        }

        private function JSONheaders()
        {
            #cross origin to allow pages from different servers to access the api 
            header("Access-Control-Allow-Origin: *"); 
            header("Content-Type: application/json; charset=UTF-8"); 
            header("Access-Control-Allow-Methods: GET, POST");
        }
        private function HTMLheaders()
        {
            header("Content-Type: text/html; charset=UTF-8");
        }
    }


?>