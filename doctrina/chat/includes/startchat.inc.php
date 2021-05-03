<?php 
    /**
     * @author Michael Anderson 
     * @author W18032122
     * 
     * This script provides the functionality start a chat with another user
     * it first checks if a classChat exists for the current users in this class 
     * If none are found it creates one
     * All messages are then loaded for the current users in this classChat
     * This script is also used to update the chat messages in the chat section
     */

     //empty variable to hold any chat messages that exist
    $messages = ""; 

    //check to ensure the correct data has been sent in the ajax request
    if( (isset($requestArray['requestData']['recipientID']))&&(isset($requestArray['requestData']['classID'])) )
    {
        $recipientID = $requestArray['requestData']['recipientID'];
        $classID     = $requestArray['requestData']['classID'];
    }

    //query to get the details of the current recipient 
    $recipientQuery = "SELECT tp_users.user_forename, tp_users.user_surname, tp_users.user_id, tp_user_type.user_type_desc, tp_users.user_online_status FROM tp_users 
    JOIN tp_user_type on tp_user_type.user_type_id = tp_users.user_type_id WHERE tp_users.user_id = :recipientID LIMIT 1"; 
    //set parameters for the pdo prepared statement
    $params['recipientID'] = $recipientID;

    try 
    {
        $recipientDetails = $recset->getRecordSet($recipientQuery,$params);
    }
    catch(Exception $e)
    {
        $error .= "ERROR an error occured when connecting to that user</br>";
    }

    //check if the query returned any results
    if(count($recipientDetails) > 0)
    {   
        $params = array(); 

        //assign results to the first record in the query results
        $recipientDetails = $recipientDetails[0];
        
        //check if a class chat already exists between both users for this class
        $classChatQuery = "SELECT * FROM tp_class_chat where class_id  = :classID AND teacher_id = :teacherID AND student_id = :studentID limit 1";
        
        //pdo parameters for current class id
        $params['classID'] = $classID;

        //check which user is the teacher and which is the student and set parameters accordingly 
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

        try
        {
            //get current class chat
            $classChatResults = $recset->getRecordSet($classChatQuery,$params);

            //if current class chat doesn't exist create one
            if(count($classChatResults) == 0) 
            {
                //if no classChat is found create one
                $createNewChatQuery = "INSERT INTO tp_class_chat (class_id, student_id, teacher_id) VALUES (:classID, :studentID, :teacherID)";
                $recset->writeToDB($createNewChatQuery,$params);
                // get the newly created classChat details from db
                $classChatResults = $recset->getRecordSet($classChatQuery,$params);
            }
        }
        catch(Exception $e)
        {
            $error .= "ERROR An error occured making a connection with that user<br/>";
        }

        $classChatResults =  $classChatResults[0];
        $params = array(); 
        
        //query to collect all messsages for current users in the this classChat
        $getMessagesQuery = "SELECT * FROM tp_chat_messages WHERE class_chat_id = :classChatID order by message_send_date asc";
        $params['classChatID'] = $classChatResults['class_chat_id'];

        try
        {
            $allMessages = $recset->getRecordSet($getMessagesQuery,$params);

            //check if any messages were returned
            if(count($allMessages)>0)
            {
                //create a banner showing the current chat recipient 
                $messages .= 
                            "<div id='chat-banner'>
                                you are now chatting with {$recipientDetails['user_forename']} {$recipientDetails['user_surname']}      
                            </div>";

                foreach($allMessages as $message)
                {
                    //if current user is the message sender use the correct function to create the chat message
                    if($message['message_sender'] == $myID)
                    {
                        //current users details are in session array 
                        $messages .= myMessage($message, $_SESSION);
                    }
                    else
                    {
                        //other users detials are stored in the recipientdetials array 
                        $messages .= otherMessage($message, $recipientDetails);
                    }
                }
            }
            else
            {
                //mo mesages found from current users
                $messages .= "<div id='error'>You have no previous messages for this user, send a message to start a new chat</div>";
            }


        }
        catch(Exception $e)
        {
            $error .="ERROR An error occured getting chat message<br/>";
        }
        
        
        // $response['responseData'] = array("message"=>"sendMessage", "data"=>"$messages");
        // $response['responseType'] = "startChat"; //in thingy update
        $responseMessage =  "sendMessage";
        $responseType = "startChat";
    }
    else
    {
        //user not found
        $error .="ERROR An error occured attempting to connect to that user"; 
    }
    //if any errors occured set resonse array to show them
    if(!empty($error))
    {
        $responseMessage = $error;
        $responseType ='error';
        $messages ="";
    }

    //send response encoded as json 
    $response['responseData'] = array("message"=>$responseMessage,"data"=>$messages);
    $response['responseType'] = $responseType;
    echo json_encode($response);



?>