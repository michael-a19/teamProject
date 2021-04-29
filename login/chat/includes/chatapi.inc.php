<?php
    session_start();
    //pretend all checks for logged in are here 
    include_once("../../classes/recordset.class.php"); //works
    include_once("../../includes/functions.inc.php");
    
    // check if user is a teacher or student
    $isTeacher = False; 
    if(checkIfTeacher())
    {
        $isTeacher = True;
    }

    //get all contents from the body of the http request sent from the chat page
    $requestData = file_get_contents("php://input");
    //decode from json into an associative array
    $requestArray = json_decode($requestData,true); //true means as assoc array  ********************************************
   // print_r( $requestArray);
    $result = array(); 


    $recset = new RecordSet('../logindb.sqlite'); //from here will it be better to instantiate at top?

    //dunno if needed but an error string to hold any error messages if they occur
    $error = ""; 



    //switch? check first then do the thing or not whatever 
    if(isset($requestArray['requestType']) && ($requestArray['requestType'] == "sendMessage"))
    {
        //data will be in the variable named in the send datafunction, more than likely a $requestArrray['data']['thing']
        include("./sendmessage.inc.php");
    }
    else
    if(isset($requestArray['requestType']) && ($requestArray['requestType'] == "startChat"))
    {
        include("./startchat.inc.php");
        //$result['message'] = "this chat works"; 
        //$result['responseType'] = "startChat"; 
        //$result['responseData'] = array('data'=>"this also works", "message"=>"thisworks"); 
        //echo json_encode($result);
        //data will be in the variable named in the send datafunction, more than likely a $requestArrray['data']['thing']
    }
    if(isset($requestArray['requestType']) && ($requestArray['requestType'] == "test"))
    {
        $result['message'] = "this works"; 
        $result['responseType'] = "test"; 
        $result['responseData'] = array('data'=>"this also works", "message"=>"thisworks"); 
        echo json_encode($result);
        //data will be in the variable named in the send datafunction, more than likely a $requestArrray['data']['thing']
    }
    
    
    //function to generate the current users chat messages
    function myMessage($messageDetails, $userDetails){
        $seen = isSeen($messageDetails['message_seen']);
        $date = date('D d M Y, G:i A',$messageDetails['message_send_date']);
        $myMessage = <<<MESSAGE
        <div class="message-wrapper">
            <div class="message-name align-right right-text">
                {$userDetails['user_forename']} {$userDetails['user_surname']}  
            </div>
            <div class="message-text align-right my-message">
                {$messageDetails['message_text']}
            </div>
            <div class="message-details-wrapper align-right">
                <div class="message-details-right">
                    <span>$date</span>
			        <span>{$seen}</span>
                </div>
            </div>
        </div>
MESSAGE;
        return $myMessage;
    }
    //function to generate the other users chat messages
    function otherMessage($messageDetails, $userDetails){
        $seen = isSeen($messageDetails['message_seen']);
        $date = date('D d M Y, G A',$messageDetails['message_send_date']);
        $otherMessage = <<<MESSAGE
        <div class="message-wrapper">
            <div class="message-name align-left left-text">
                {$userDetails['user_forename']} {$userDetails['user_surname']} 
            </div>
            <div class="message-text align-left other-message">
                {$messageDetails['message_text']}
            </div>
            <div class="message-details-wrapper align-left">
                <div class="message-details">
                    <span>$date</span>
			        <span>{$seen}</span>
                </div>
            </div>
        </div>
    
MESSAGE;
        return $otherMessage;
    }

    function isSeen($num)
    {
        if($num = 1)
        {
            return "seen";
        }
        else
        {
            return "not seen";
        }
    }
    //function to generate a randome code of a specified size 
    function generateRandomString($length){
        $array = array(0,1,2,3,4,5,6,7,8,9, 'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $string = "";
        for($i=0; $i<$length; $i++){
            $random = rand(0,34);
            $string .= $array[$random];
        }
        return $string;
    }

/* when a user logs in we need to set their online status as online and logout needs to set it as offline, persisting to 
session won't work for other users 


//define result here as an array because we pass back result by everything 
$result = array(); 
//then in an include we write $result['message'] = ""; $result['responseType'] = "sendMessage" ; etc
//get sent object data 
get the sent reuest file etc 

    session_start();
    include_once("./classes/recordset.class.php");
    //check login first if not redirect user maybe? the requests aren't coming from php tho redirect won't work 
    have a clause that when returned signs user out!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //get all contents from the body of the http request sent from the chat page
    $requestData = file_get_contents("php://input");
    //decode from json into an associative array
    $requestArray = json_decode($requestData,true); //true means as assoc array 

    blah blah blah say from here we are loggged in
    $recset = new RecordSet('logindb.sqlite'); //from here will it be better to instantiate at top?

    //dunno if needed but an error string to hold any error messages if they occur
    $error = ""; 

    //switch? check first then do the thing or not whatever 
    if(isset($requestArray['requestType']) && ($requestArray['requestType'] == "sendMessage"))
    {
        include("includes/sendMessage.php");
        //in this includes file my thing will return a json object with responseType set and in index it will be handled
$requestType = get from sent object */


/* 
    function otherMessage($messageDetails, $userDetails){

        $otherMessage = <<<MESSAGE
        <div class="message-wrapper message-wrapper-left">
            <div class="left-message-box message-box">
                {$userDetails['fname']}<br/>
                {$messageDetails['message']}
            </div>
            <span class='date'>April 12  seen</div>
        </div>
MESSAGE;
        return $otherMessage;
    }


*/
?>


