<?php 
    //script to update the profile details of the logged in user
    session_start(); 
    //include
    include_once("./functions.inc.php");
    include_once('../classes/recordset.class.php');
    checkLoggedIn();

    
    $error = "";//empty array to hold errors
    $data = array(); //empty array to hold input from form

    //check if the form has been submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $recset = new RecordSet();
        /* validate user input */

       // "SELECT tp_users.user_forename,tp_users.user_surname,tp_users.user_email from tp_users where tp_users.user_email = :email"; 
        $data['fname']     = (isset($_REQUEST['fname']))     ? validateInput($_REQUEST['fname'])     : ""; //add the trim here 
        $data['lname']     = (isset($_REQUEST['lname']))     ? validateInput($_REQUEST['lname'])     : "";
        $data['email']     = (isset($_REQUEST['email']))     ? validateInput($_REQUEST['email'])     : "";
        $data['password']  = (isset($_REQUEST['password']))  ? ($_REQUEST['password'])  : "";

        
        //check if empty and the REST OF The validation!!!
        if(!empty($data['fname'])) 
        {
            //check the length is greater than 3
            if(strlen($data['fname']) < 1) 
            {
                $error .= "First name must be longer than 1 letters. <br>";
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
            if(strlen($data['lname']) < 1) 
            {
                $error .= "Last name must be longer than 1 letters. <br>";
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

        //check if password
        if(empty($data['password']))
        {
            $error .= "Password must not be empty<br>";
        }

        //if no errors then update account
        if(empty($error))
        {   
            //get user password to confirm changes 
            $checkQuery = "SELECT tp_users.user_password from tp_users where tp_users.user_id = :userID limit 1";  //="SELECT tp_users.user_forename FROM tp_users WHERE tp_users.user_email = :email"; 
            $checkParam['userID'] = $_SESSION['user_id'];

            $checkRes = $recset->getRecordSet($checkQuery,$checkParam);
            $checkRes = $checkRes[0];

            if(count($checkRes) > 0) //if results found
            {

                //hash user password
                if(password_verify($data['password'], $checkRes['user_password']))
                {
                    //insert new details into 
                    $insertDetails = "UPDATE tp_users SET user_forename = :fname, user_surname = :lname, user_email = :email where user_id = :userID";
                    $params['userID'] = $_SESSION['user_id'];
                    $params['fname']  = $data['fname'];
                    $params['lname']  = $data['lname'];
                    $params['email']  = $data['email'];
                    
                    try {
                        $recset->writeToDB($insertDetails,$params);
                        //update session variables that hold user name
                        $_SESSION['user_forename'] = $data['fname'];
                        $_SESSION['user_surname']  = $data['lname'];
                        $_SESSION['notifcation']=  "profile updated successfully";
                        // header("location: ../login.php");
                        
                    }
                    catch(Exception $e)
                    {
                        $error .= "Internal Error " . $e->getMessage() . "</br>";
                    }
                } 
                else //no results found proceed to create account
                {
                    $error .= "incorrect password";
                }
            }
            else //no results found proceed to create account
            {
                $error .= "no users matching those details found"; //probably should log out
            }
                   
        }

        if(!empty($error))
        {
            //if script gets to this point the login failed so return to register page and show  error message 
            $_SESSION['error'] = $error;
        }

        header('location: ../changeprofile.php');
        die();
    }//user request did not come from the form 
    header('location: ../index.php');
?>