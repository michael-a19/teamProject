<?php
   
    //variables to hold the responseMessage and type
    $responseMessage ='';
    $responseType ='';
    //boolean flag to check if user is a teacher 
   
    $recipientID = ""; 
    $classID = "";
    if( (isset($requestArray['requestData']['recipientID'])) && (isset($requestArray['requestData']['classID'])) && (isset($requestArray['requestData']['message'])) )
    {
        $recipientID = $requestArray['requestData']['recipientID'];
        $classID     = $requestArray['requestData']['classID'];
        $chatMessage = $requestArray['requestData']['message'];

        //$chatMessage = validateMessage($chatMessage);
    }
    //define current users id
    $myID = $_SESSION['user_id']; //pointless just make use session
    $params['classID'] = $classID;
    //set the parameters for the classchat 
    if($isTeacher)
    {
        //current user is a teacher
        $params['teacherID'] = $myID; 
        $params['studentID'] = $recipientID; 
    }
    else
    {
        //current user is a student so set query parama ters as appropriate
        $params['teacherID'] =  $recipientID;     
        $params['studentID'] =  $myID; 
    }
    // get instance of this classChat
    $classChatQuery = "SELECT * FROM tp_class_chat where class_id  = :classID AND teacher_id = :teacherID AND student_id = :studentID limit 1";
    $classChatResults = $recset->getRecordSet($classChatQuery,$params);

    //if classChat is found
    if(count($classChatResults) > 0) 
    {
         /* (3) insert message into the database*/
        $classChatResults =  $classChatResults[0];
        $params = array(); 
        $params['classChatID'] =  $classChatResults['class_chat_id'];
        $params['sender'] = $myID;
        $params['recipient'] = $recipientID;
        $params['sendDate'] = time(); //set time to unix timestamp
        $params['seen'] = 0;
        $params['message'] = $chatMessage;
        //$params['deleted_sender'] = 0;
        //$params['deleted_recipient'] = 0;

        $sendMessageQuery = "INSERT INTO tp_chat_messages 
        (class_chat_id,message_sender,message_recipient,message_send_date,message_seen,message_text)
        VALUES 
        (:classChatID,:sender,:recipient,:sendDate,:seen,:message)";

        //insert into database
        $recset->writeToDB($sendMessageQuery,$params);
        $responseMessage ='message sent successfully';
        $responseType ='getMessages'; //tells the chat to refresh and load new messages
      }
      else
      {
        //classChat not found return error
        $responseMessage ='error occured when attempting to make a connection, please try again';
        $responseType ='error';
      }

     
     
      /* (4) decide on what to do now, return empty object data with send message repsonse which will then call update? or just send an update? 
      means less redundant code tbh */ 
      $response['responseData'] = array("message"=>$responseMessage,"data"=>$responseMessage);
      $response['responseType'] = $responseType; //response type that causes the chat to refresh loading new chat messsages
      echo json_encode($response);

    







?>