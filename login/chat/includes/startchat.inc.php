<?php 

//start chat, have to get all previous messages 
// $recipientID = "null";

// $test = "
//     <div> this is a test to return some data and insert into the thingy </div>";
// $response['responseType'] = "startChat";
// $response['responseData'] = array("data"=>$test);  
// echo json_encode($response);

/* probably better to put htet 
*  thingy in here to check and start a new chat since 
*send message is going to be used to much then 
can check if the message exist and return a new message (no messages exist with this user 
please send a message to start a chat etc fuck) */
// if( (isset($requestArray['requestData']['recipientID']) )&&( isset($requestArray['requestData']['classID']) ) )
// {
//     //$requestArray['requestData']['recipientID']
// }
/* (1) check if a classChat exists between both users - if not return a message to start enter   */
/* (2) Check for messages associated to that class Chat - if none exist return essage to start send one */
/* (3) load the messages ordered by time and return as a string that will be loaded on the other side and inserted*/
/* (4) profit????*/

//(1)
$messages = ""; //move further down or something 
//check if user is a teacher 


    $recipientID = "";
    $classID = "";
    if( (isset($requestArray['requestData']['recipientID']))&&(isset($requestArray['requestData']['classID'])) )
    {
        $recipientID = $requestArray['requestData']['recipientID'];
        $classID     = $requestArray['requestData']['classID'];
    }
    //define current users id

    $myID = $_SESSION['user_id'];

    /* (1) find the recipient */
    // get recipient details 
    $recipientQuery = "SELECT tp_users.user_forename, tp_users.user_surname, tp_users.user_id, tp_user_type.user_type_desc, tp_users.user_online_status FROM tp_users 
    JOIN tp_user_type on tp_user_type.user_type_id = tp_users.user_type_id WHERE tp_users.user_id = :recipientID LIMIT 1"; 
    $params['recipientID'] = $recipientID;

    $recipientDetails = $recset->getRecordSet($recipientQuery,$params);

    //if recipient details are found 
    if(count($recipientDetails) > 0)            // R E C I P I E N T  -  D E T A I L S
    {   
        /* (2) check if class chat already exists for this class and for these users (student can start chat with their class teacher)*/
        $params = array(); 
        //assign $recipientDetails to the first result in the results array
        $recipientDetails = $recipientDetails[0];
        
        //check if a class chat already exists between both users for this class
        $classChatQuery = "SELECT * FROM tp_class_chat where class_id  = :classID AND teacher_id = :teacherID AND student_id = :studentID limit 1";
        //if current user is teacher set teacher id to current userid

        $params['classID'] = $classID;
        if($isTeacher)
        {
            $params['teacherID'] = $myID; 
            $params['studentID'] = $recipientID; 
        }
        else
        {
            //current user is a student so set query paramaters as appropriate
            $params['teacherID'] =  $recipientID;     
            $params['studentID'] =  $myID; 
        }
        // //execute query 
        $classChatResults = $recset->getRecordSet($classChatQuery,$params);
        //check if classChat exists, if not create new instanct

        //none exist create new move to the thingy this should only just call it 
        //$classChatResults = $classChatResults[0];
        if(count($classChatResults) == 0) {
        //if(count($classChatResults) == 0) {
           // $messages .= "here";
             $createNewChatQuery = "INSERT INTO tp_class_chat (class_id, student_id, teacher_id) VALUES (:classID, :studentID, :teacherID)";
            //query parameters already set 
            //sould generate a chat id randomly and insert but for now this wwill work
            $recset->writeToDB($createNewChatQuery,$params);
            // get the newly created chat details from db
            $classChatResults = $recset->getRecordSet($classChatQuery,$params);
            //sleep (1);
            
        }
        //could do 1 by 1 really;
        /* (3) insert message into the database*/
        $classChatResults =  $classChatResults[0];


        $params = array(); 
        /* (something) get messages now */ 
        $getMessagesQuery = "SELECT * FROM tp_chat_messages WHERE class_chat_id = :classChatID order by message_send_date asc";
        $params['classChatID'] = $classChatResults['class_chat_id'];
        $allMessages = $recset->getRecordSet($getMessagesQuery,$params);
        //$messages .= "here";
        //vcheck if any messages are fouind 
        if(count($allMessages)>0)
        { 
            $messages .= "<div id='chat-banner'>
                you are now chatting with {$recipientDetails['user_forename']} {$recipientDetails['user_surname']}      
            </div>";
            foreach($allMessages as $message)
            {
                //other way is to make a call here to get the senders details which would work but is excessive
                //my idea is that all the details are already here in recipirent details and session 
                if($message['message_sender'] == $myID)
                {
                    //sender is current user
                    //current users details are in session array 
                    $messages .= myMessage($message, $_SESSION);
                }
                else
                {
                    //sender is other user
                    //other users detials are stored in the recipientdetials array 
                    $messages .= otherMessage($message, $recipientDetails);
                }
            }
   
        }
        else
        {
            //mo mesages found 
            $messages .= "<div id='error'>No previous messages for this user, send a message to start a new chat</div>";

        }
        //$messages .= "dinlg3";



        // $params = array(); 
        // $params['classChatID'] =  $classChatResults['classChatID'];
        // $params['sender'] = $myID;
        // $params['recipient'] = $recipientID;
        // $params['sendDate'] = time(); //set time to unix timestamp
        // $params['seen'] = 0;
        // $params['message'] = "test";///sent messsage text not done yet  
        // $params['deleted_sender'] = 0;
        // $params['deleted_recipient'] = 0;

        // $sendMessageQuery = "INSERT INTO chatMessages 
        // (classChatID,sender,recipient,sendDate,seen,message,deleted_sender,deleted_recipient) 
        // VALUES 
        // (:classChatID,:sender,:recipient,:sendDate,:seen,:message,:deleted_sender,:deleted_recipient)";

        // //insert into database
        // $recset->getRecordSet($sendMessageQuery,$params);

        /* (4) decide on what to do now, return empty object data with send message repsonse which will then call update? or just send an update? 
        means less redundant code tbh */ 

       
        $response['responseData'] = array("message"=>"sendMessage", "data"=>"$messages");
        $response['responseType'] = "startChat"; //in thingy update
        echo json_encode($response);

    }else
    {
        //user not found 
        $messages .= "can not connect to that user... please try again later"; //?
        $response['responseData'] = array("message"=>"error", "data"=>"don't");
        $response['responseType'] = "error"; //in thingy update
        echo json_encode($response);
    }




?>