<?php
    /**
     * @author Michael Anderson 
     * @author W18032122
     * 
     * This script acts as a router to recieve ajax requests from the chat client. 
     * This script recieves those requests, extracts a requestType string which is then used to determine
     * the script to include to accomplish the requested functionality.
     */
    session_start();
  
    //recordset for querying database
    include_once("../../classes/recordset.class.php");
    include_once("../../includes/functions.inc.php");
    include_once("./chatfunctions.inc.php");
    // check if current user is a teacher or student
    $isTeacher = False; 
    if(checkIfTeacher())
    {
        $isTeacher = True;
    }

    //get all contents from the body of the http request sent from the chat page
    $requestData = file_get_contents("php://input");

    //decode from json into an associative array
    $requestArray = json_decode($requestData,true);

    //create empty array ready to hold the response data 
    $result = array(); 

    //create a recordset object to connect to the database
    $recset = new RecordSet(); 

    //empty string to hold error messages
    $error = ""; 

    //hold current users id 
    $myID = $_SESSION['user_id'];

    //empty variables to hold the current classID and chat recipientID
    $recipientID = "";
    $classID = "";

    //get the request type sent from the chat client, the request type is a string that determines which php script to include
    if(isset($requestArray['requestType']) && ($requestArray['requestType'] == "sendMessage"))
    {
        //request type is send message so include send message script
        include("./sendmessage.inc.php");
    }
    else
    if(isset($requestArray['requestType']) && ($requestArray['requestType'] == "startChat"))
    {
        //request type is start chat
        include("./startchat.inc.php");
    }
    //something with errros
?>


