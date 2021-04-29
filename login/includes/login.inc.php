<?php
    //start session 
    session_start();
   
    //if user is already logged in redirect to the homepage
    if(isset($_SESSION['user_id'])){
        //redirect to homepage
        header('location: ../index.php');
    }

    //include database connection object 
    include_once('../classes/recordset.class.php');

    $error = "";//empty array to hold errors
    $data = array(); //empty array to hold input from form

    //check if the form has been submitted, if not refuse login attempt
    if($_SERVER["REQUEST_METHOD"] == "POST")
    { 
        //create a database connection object
        $recset = new RecordSet();
        
        //get and validate the users email and password, sent from the login form
        $data['email']    = (isset($_REQUEST['email']))    ? validateInput($_REQUEST['email'])    : "";
        $data['password'] = (isset($_REQUEST['password'])) ? ($_REQUEST['password']) : "";
       
        //check if email is empty, if not then validate further
        if(!empty($data['email']))
        {
           //check email is correct format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            {
                $error .= "Email is invalid. <br>";
            }
        } 
        else
        {
            $error .= "Empty email submitted <br>";
        }//end of email validation 

        //check if password is empty
        if(empty($data['password']))
        {
            $error .= "Empty password submitted <br>";
        }

        //if no errors occured during validation then attempt to login in user
        if(empty($error))
        {   
            $query = "SELECT * FROM tp_users WHERE user_email = :email LIMIT 1";

            $params['email']=$data['email'];
            
            try 
            {
                //query thee database
                $res = $recset->getRecordSet($query, $params);
            }
            catch(Exception $e)
            {
                $error .= "Internal Server Error " . $e->getMessage() . "</br>";
            }

            if(is_array($res)) //if contains results
            {
                $res = $res[0];
                //verify user password
                if(password_verify($data['password'], $res['user_password']))
                {
                    //if passwords is correct then login user and set session variables and redirect to homepage
                    //$_SESSION['loggedIn'] = True;
                    $_SESSION['user_id']       = $res['user_id'];
                    $_SESSION['user_forename'] = $res['user_forename'];
                    $_SESSION['user_surname']  = $res['user_surname'];
                    $_SESSION['type']          = $res['user_type_id']; // 2 is a teacher, 1 is a student

                    //redirect to index page after loggin in
                    header("location: ../index.php");
                    //prevent code below being ran after redirect which may override this redirect
                    die();
                }
                else
                {
                    $error .= "Invalid password of email <br/>";
                }
            }
            else
            {
                $error .= "An account with this email does not exist <br/>";
            }
        }   
    }
    else
    {
        //form was not submitted so user accessed this script through other means, create error
        $error .= "ERROR login attempt failed, Please try again";
    }
    //if script gets to this point the login failed so return to login page with error message 
    $_SESSION['error'] = $error;
    header('location: ../login.php');




function validateInput($input)
{
	$input = trim($input);
	$input = stripslashes($input); 
	$input = htmlspecialchars($input);
	return $input;
}
