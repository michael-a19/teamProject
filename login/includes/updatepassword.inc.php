<?php 
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
        
        //check if user passwords are empty 
        $data['oldPassword']  = (isset($_REQUEST['oldPassword']))  ? ($_REQUEST['oldPassword'])  : "";
        $data['newPassword']  = (isset($_REQUEST['newPassword']))  ? ($_REQUEST['newPassword'])  : "";
        $data['newPassword2'] = (isset($_REQUEST['newPassword2'])) ? ($_REQUEST['newPassword2']) : "";

     
        //validate old password 
        if(empty($data['oldPassword']))
        {
            $error .= "old password must not be empty<br>";
        }

        if(!empty($data['newPassword']))
        {
            if($data['newPassword'] != $data['newPassword2'])
            {
                $error .= "new passwords do not match. <br>";
            }
            if(strlen($data['newPassword']) < 6) 
            {
                $error .= "new password is too short, passwords must be more than 6 letters long. <br>";
            }
        }
        else
        {
            $error .= "new password is invalid <br>";
        }


        //if no errors then check old passwords is correct 
        if(empty($error))
        {   
            //get user password to confirm matches with that entered
            $checkQuery = "SELECT tp_users.user_password from tp_users where tp_users.user_id = :userID limit 1";  //="SELECT tp_users.user_forename FROM tp_users WHERE tp_users.user_email = :email"; 
            $checkParam['userID'] = $_SESSION['user_id'];

            $checkRes = $recset->getRecordSet($checkQuery,$checkParam);
            $checkRes = $checkRes[0];

            if(count($checkRes) > 0) //if results found
            {

                //hash user password
                if(password_verify($data['oldPassword'], $checkRes['user_password']))
                {
                    //hash new password 
                    $newPassword = password_hash($data['newPassword'], PASSWORD_DEFAULT);
                    //insert new details into 
                    $insertDetails = "UPDATE tp_users SET user_password = :newPassword where user_id = :userID";
                    $params['userID'] = $_SESSION['user_id'];
                    $params['newPassword']  = $newPassword;
                    
                    try 
                    {
                        //write to database 
                        $recset->writeToDB($insertDetails,$params);
                        //set notifcation to show on password page
                        $_SESSION['notifcation']=  "password updated successfully";
                    }
                    catch(Exception $e)
                    {
                        $error .= "Internal Error " . $e->getMessage() . "</br>";
                    }
                } 
                else //no results found proceed to create account
                {
                    $error .= "incorrect password entered";
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

        header('location: ../password.php');
        die();
    }//user request did not come from the form 
    header('location: ../index.php');
?>