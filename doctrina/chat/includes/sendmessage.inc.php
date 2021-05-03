<?php
    /**
     * @author Michael Anderson 
     * @author W18032122
     * 
     * This script provides the functionality to save a message to the database, called when a user sends a message 
     * The script gets the message details sent in the body of an ajax request and submits it to the database
     * The script then returns a json response with a responseType string that tells the chat page to update the messages
     */
    $responseMessage ='';
    $responseType ='';
    //boolean flag to check if user is a teacher 
   

    //ensure the data passed from the ajax request is complete 
    if( (isset($requestArray['requestData']['recipientID'])) && (isset($requestArray['requestData']['classID'])) && (isset($requestArray['requestData']['message'])) )
    {
        $recipientID = $requestArray['requestData']['recipientID'];
        $classID     = $requestArray['requestData']['classID'];
        $chatMessage = $requestArray['requestData']['message'];
    }

    //set the sql prepared statement parameter for the current class ID 
    $params['classID'] = $classID;

    //determine which user, the sender or recipient is the teacher and student
    if($isTeacher)
    {
        //current user is a teacher
        $params['teacherID'] = $myID; 
        $params['studentID'] = $recipientID; 
    }
    else
    {
        //current user is a student
        $params['teacherID'] =  $recipientID;     
        $params['studentID'] =  $myID; 
    }

    //query to get the record of the current classChat from the database so that a message can be inserted referencing this classChat 
    $classChatQuery = "SELECT * FROM tp_class_chat where class_id  = :classID AND teacher_id = :teacherID AND student_id = :studentID limit 1";
    try
    {
        //get results
        $classChatResults = $recset->getRecordSet($classChatQuery,$params);
    }
    catch(Exception $e)
    {
        $error .= "ERROR An internal service error occured<br/>";
    }

    //if the current classChat is found
    if(count($classChatResults) > 0) 
    {
        //set result to first record returned from db
        $classChatResults =  $classChatResults[0];

        $params = array(); 
        //set parameters for pdo prepared statement to insert a message into database
        $params['classChatID'] =  $classChatResults['class_chat_id'];
        $params['sender'] = $myID;
        $params['recipient'] = $recipientID;
        $params['sendDate'] = time(); //set time to unix timestamp
        $params['seen'] = 0;
        $params['message'] = $chatMessage;

        //query to insert chat message
        $sendMessageQuery = "INSERT INTO tp_chat_messages 
        (class_chat_id,message_sender,message_recipient,message_send_date,message_seen,message_text)
        VALUES 
        (:classChatID,:sender,:recipient,:sendDate,:seen,:message)";

        try
        {   
            //insert into database
            $recset->writeToDB($sendMessageQuery,$params);
            //set resoinse message
            $responseMessage ='message sent successfully';
            //set response type to singal the chat page to update the onscreen messages
            $responseType ='getMessages';
        }
        catch(Exception $e)
        {
            $error .= "ERROR An internal error occured<br/>";
        }
      }
      else
      {
        //classChat not found return error
        $error .= 'error occured when attempting to make a connection, please try again';
      }
      //if any errors occured set the response type to error to tell that chat page to display the errors
      if(!empty($error))
      {
        $responseMessage = $error;
        $responseType ='error';
      }
      
      $response['responseData'] = array("message"=>$responseMessage,"data"=>$responseMessage);
      $response['responseType'] = $responseType; 
      //encode the response as json 
      echo json_encode($response);

    







?>