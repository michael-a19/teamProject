<?php


class JSONPage {
    private $recordSet; 
    private $type = 'JSON'; 
    private $page; //page and type should be seperated out into a super class that is inherited by both json page and html page 

    public function __construct($pathArr, $recordSet){

        $this->recordSet = $recordSet; 

        $path = (empty($pathArr[1])) ? "api" : $pathArr[1]; #if 2nd index in patharray is empty set it to api if not set it to that element
        $path = (!empty($pathArr[2])) ? $path . '/' . $pathArr[2] : $path; // if 3rdelement is not empty join to first element if not just set it to itself 

        //this switch statement will run different sql commands based on the passed in url path 
        switch ($path)
        {
            case 'api':
                $this->page = $this->json_test();
                break; 
            case 'login':
                $this->page = $this->login(); 
                break;
            case 'students':
                $this->page = $this->getStudentExample();
                break;
            default:
                $this->page = $this->json_error(); #if the path is not correct then error is returned 
                break; 
        }
    }
    public function json_test() {
        $json['status'] = "200"; 
        $json['message'] = "testing if this works"; 
        $json['data'] = "";
        return json_encode($json); 
    }

    public function getType() {
        return $this->type;
    }

    public function getPage() {
        return $this->page; 
    }

    public function json_error() {
        $json['status'] = '404'; 
        $json['message'] = 'that don\'t exist yo'; 
        $json['data'] = ''; 
        return json_encode($json); 
    }
// all this needs to be in its own class because this is ridiculously long, stop it from becoming huge when all the different requests are there. Define to decode function that will be 
//needed when doing thins that a teacher wants like adding students etc 
    public function login(){
        $errorMessage= "";
        $test = "";
        #get the input from the post request (email and password and stuff )
        
        $input = json_decode(file_get_contents("php://input")); #returns the raw data from the post body post usually uses html forms but since we're sending this from an ajax js call 
        #it isn't the right type to use $_POST so we need to get the raw data from the psot body instead 
        $test = '1';
        #check the input isn't empty (nothing sent from post )
        if($input) {
            $test = $test+" 2";
            #check if email and password are set
            if(isset($input->email) && isset($input->password)) {
                #check if password and email are not empty 
                $test = $test+" 3";
                if(!empty($input->email) && !empty($input->password))
                {
                    $test = $test+" 4";
                    #if not empty then query database for the user and shit 
                    $sqlQuery = "SELECT user_email, user_password, user_fName, user_lName, user_type from User WHERE user_email like :email";
                    $params['email'] = $input->email; 
                    $result = $this->recordSet->getRecordSet($sqlQuery, $params); 
                    #once got the results from the sql search then we compare the passwords 
                    #if the resultsof the query are not empty (0) then we can compare both 
                    if(count($result) !== 0) {
                        $test = "made it here ";
                        $userPassword = ($input->password); 
                        if(password_verify($userPassword, $result[0]['user_password'])) { #password of the first result from the search (incase multiple accounts have same email)
                            #if the passwords are the same then we can encode a token and return a response 
                            $jwtkey = JWTKEY;

                            $json['status'] = '200';
                            $json['message'] ='login successful'; 
                            $json['name'] = $result[0]['user_fName'];
                            $token['iat'] = time(); #time the token is created 
                            $token['exp'] = time() + 3600; #expires an hour after being created 
                            $token['email'] = $input->email;
                            $token['type'] = $result[0]['user_type'];
                            $token = \Firebase\JWT\JWT::encode($token, $jwtkey); 
                            
                            $json['type'] = $result[0]['user_type'];
                            $json['token'] = $token; 
                            return json_encode($json); 
                        }
                        else {
                            #login was wrong 
                            $errorMessage = "incorrect details "; 
                        }
                    }else {
                        #results were empty 
                        $errorMessage = "incorrect details "; 
                    }

                }else {
                    #emial or password from request were empty 
                    $errorMessage = "empty email or password "; 
                }
            }else {
                #email or password were not set 
                $errorMessage = "empty email or password";
            }
        }else {
            #body of post request was empty 
            $errorMessage = "empty request";
        }

        $json['status'] = '400'; #or some relevant code can't be bothered to look them up right now 
        $json['message'] = $errorMessage;  
        $json['token'] = null; 
        $json['data'] = null; 
        $json['test'] = $test;
        return json_encode($json);
    }

    private function getStudentExample(){
        $query = "SELECT * from studentExample";
        $params = ""; 
        $result = $this->recordSet->getRecordSet($query, $params); 
        $json['status'] = '200';
        $json['message'] = "request ok"; 
        $json['data'] = $result;
        return json_encode($json); 
    }
}
?>