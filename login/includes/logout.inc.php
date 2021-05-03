<?php 
    //script to log out a user
    session_start(); 
    include_once('../classes/recordset.class.php');
    $recset = new RecordSet();

    if(isset($_SESSION['user_id']))
    {
        try 
        {
            $setOffline = "UPDATE tp_users SET user_online_status = 0 WHERE user_id = :userID";
            $params = array("userID"=>$_SESSION['user_id']);
            $recset->writeToDB($setOffline, $params);
        }
        catch(Exception $e)
        {
            //log error to error file
        }
    }
    session_unset();
    session_destroy();
    header("location: /login/login.php");