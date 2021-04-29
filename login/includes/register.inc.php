<?php
    session_start();
    //include database connection object 
    include_once('../classes/recordset.class.php');
    include_once("../includes/functions.inc.php");

    //check if user is already logged in, if so send to homepage
    if(isset($_SESSION['user_id'])){
        //redirect to homepage
        header('location: ../index.php');
    }

    $error = "";//empty array to hold errors
    $data = array(); //empty array to hold input from form

    //check if the form has been submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    { 
        $recset = new RecordSet();
        /* validate user input */
        
        //check if each variable is set and asssign to local variables
        $data['fname']     = (isset($_REQUEST['fname']))     ? validateInput($_REQUEST['fname'])     : ""; //add the trim here 
        $data['lname']     = (isset($_REQUEST['lname']))     ? validateInput($_REQUEST['lname'])     : "";
        $data['email']     = (isset($_REQUEST['email']))     ? validateInput($_REQUEST['email'])     : "";
        $data['password']  = (isset($_REQUEST['password']))  ? validateInput($_REQUEST['password'])  : "";
        $data['password2'] = (isset($_REQUEST['password2'])) ? validateInput($_REQUEST['password2']) : "";

        //check if empty and the REST OF The validation!!!
        if(!empty($data['fname'])) 
        {
            /* need to sanitise with filter var  */
            //check the length is greater than 3
            if(strlen($data['fname']) < 3) 
            {
                $error .= "First name must be longer than 3 letters. <br>";
            }
            //only allow letters in name
            if(!preg_match("/^[a-z A-Z]*$/", $data['fname']))
            {
                $error .= "Name must only contains letters. <br>";
            }
        }
        else
        {
            $error .= "First name must not be empty <br>";
        }//end of fname validation

        //check if lname is empty if not validate it 
        if(!empty($data['lname']))
        {
            if(strlen($data['lname']) < 3) 
            {
                $error .= "Last name must be longer than 3 letters. <br>";
            }
            //only allow letters in last name
            if(!preg_match("/^[a-z A-Z]*$/", $data['lname'])) 
            {
                $error .= "Name must only contains letters. <br>";
            }
        }
         else 
        {
            $error .= "Last name must not be empty <br>";
        }//end of last name validation 

        //check if email is empty, if not then validate 
        if(!empty($data['email']))
        {
            //letters a-z of any length then an @ symbol then letters of any length a-z then a . then letters of any length a-z
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            {
                $error .= "Email is invalid. <br>";
            }
        } 
        else
        {
            $error .= "Email must not be empty <br>";
        }//end of email validation 

        //check if password is empty if not then validate it 
        if(!empty($data['password']))
        {
            
            if($data['password'] != $data['password2'])
            {
                $error .= "passwords do not match. <br>";
            }
            if(strlen($data['password']) < 6) 
            {
                $error .= "password  too short, must be more than 6 letters long. <br>";
            }
        }
        else
        {
            $error .= "Password must not be empty<br>";
        }
        
        //if no errors then create account
        if(empty($error))
        {   
            //check if user account already exists 
            $checkQuery ="SELECT tp_users.user_forename FROM tp_users WHERE tp_users.user_email = :email"; 
            $checkParam['email'] = $data['email'];

            $checkRes = $recset->getRecordSet($checkQuery,$checkParam);
            $checkRes = $checkRes[0];
            if(count($checkRes) < 1) //if no results create user, if results are found return error
            {
                //hash user password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                $query = "insert into tp_users ( user_forename, user_surname, user_email, user_type_id ,user_password,user_year_group, user_online_status ) 
                        values ( :fname, :lname, :email, :type ,:password, :yeargroup, :onlinestatus)";
                //make email lowercase 
                $data['email'] = strtolower($data['email']); 
                //set query parameters 
                $params = array();
                $params['fname']        = $data['fname'];
                $params['lname']        = $data['lname'];
                $params['email']        = $data['email'];
                $params['type']         = 2;
                $params['password']     = $data['password'];
                $params['yeargroup']    = 0;
                $params['onlinestatus'] = 0;
                try 
                {
                    //post to database
                    $res = $recset->writeToDB($query, $params);
                    //if successful relocate to the login form 
                    
                    header("location: ../login.php");
                    //prevent code below being ran after redirect which may override this redirect
                    die();
                }
                catch(Exception $e)
                {
                    $error .= "Internal Error " . $e->getMessage() . "</br>";
                }



              
            } 
            else //no results found proceed to create account
            {
                $error .= "That email is already registered, try logging in. <br/>";
                
            }
        }
    }
    else
    {
        //if request method is not post then acess to script did not come from form
        //send to 404 page or just to register page?
        $error .= "ERROR</br>to create an account use form";
    }
    //if script gets to this point the login failed so return to register page and show  error message 
    $_SESSION['error'] = $error;
    header('location: ../register.php');




