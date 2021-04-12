<?php
    //start session 
    session_start();
   
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True){
        //redirect to homepage
        header('location: ../index.php');
    }
    //include database connection object 
    include_once('../classes/recordset.class.php');



    $error = "";//empty array to hold errors
    $data = array(); //empty array to hold input from form


    //check if the form has been submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    { 
        //change these, remove db name, not needed when using mysql 
        $recset = new RecordSet("../logindb.sqlite");
        /* validate user input */

        $data['email']    = (isset($_REQUEST['email']))    ? validateInput($_REQUEST['email'])    : "";
        $data['password'] = (isset($_REQUEST['password'])) ? validateInput($_REQUEST['password']) : "";
       
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
            $error .= "Empty email submitted <br>";
        }//end of email validation 

        //check if password is empty if not then validate it 
        if(empty($data['password']))
        {
            $error .= "Empty password submitted <br>";
        }

        //if no errors then attempt to login in user
        if(empty($error))
        {   
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";

            $params['email']=$data['email'];
            
            try 
            {
                //query thee database
                $res = $recset->getRecordSet($query, $params);
            }
            catch(Exception $e)
            {
                $error .= "Internal Error " . $e->getMessage() . "</br>";
            }

            if(is_array($res)) //if contains results
            {
                $res = $res[0];
                //verify password
                if(password_verify($data['password'], $res['password']))
                {
                    //if passwords are the same ses login session and redirect ot homepage
                    $_SESSION['loggedIn'] = True;
                    $_SESSION['userID']   = $res['userID'];
                    $_SESSION['fname']    = $res['fname'];
                    $_SESSION['lname']    = $res['lname'];
                    $_SESSION['type']     = $res['type'];

                    //redirect 
                    header("location: ../index.php");
                    //prevent code below being ran after redirect which may override this redirect
                    exit();
                   
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
                

        }//h
        
    }//oo
    else
    {
        $error .= "ERROR login attempt failed, Please try again";
    }
    //if script gets to this point the login failed so return error message 
    $_SESSION['error'] = $error;
    header('location: ../login.php');




function validateInput($input)
{
	$input = trim($input);
	$input = stripslashes($input); 
	$input = htmlspecialchars($input);
	return $input;
}
