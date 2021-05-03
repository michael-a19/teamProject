<?php
    /**
     * @author Michael Anderson 
     * @author W18032122
     * 
     * This script holds the various functions used within the chat section
     */

    /**
     * Function to generate the current users chat messages
     * 
     * @param messageDetails: an array consisting of the message details from the database
     * @param userDetails: an array of the current users details
     * @return String: a string that consists of the html needed to show the users message
     */
    function myMessage($messageDetails, $userDetails){
        $seen = isSeen($messageDetails['message_seen']);
        $date = date('D d M Y, G:i A',$messageDetails['message_send_date']);
        $myMessage = <<<MESSAGE
        <div class="message-wrapper">
            <div class="message-name align-right right-text">
                {$userDetails['user_forename']} {$userDetails['user_surname']}  
            </div>
            <div class="message-text align-right my-message" onclick="dave(event)" data-messageID={$messageDetails['chat_message_id']}>
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
    
    /**
     * Function to generate the chat recipients chat messages
     * 
     * @param messageDetails: an array consisting of the message details from the database
     * @param userDetails: an array of the other users details
     * @return String: a string that consists of the html needed to show the recipients messages
     */
    function otherMessage($messageDetails, $userDetails){
        
        $deleteOption = "<div id='show-delete' onclick='dave(event)'> </div>";
        $seen = isSeen($messageDetails['message_seen']);
        $date = date('D d M Y, G A',$messageDetails['message_send_date']);
        $otherMessage = <<<MESSAGE
        <div class="message-wrapper">
            <div class="message-name align-left left-text">
                {$userDetails['user_forename']} {$userDetails['user_surname']} 
            </div>
            <div class="message-text align-left other-message" onclick="dave(event)" data-messageID={$messageDetails['chat_message_id']}">
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

    /**
     * A helper function to help show whether a message has been seen or not 
     * @param num an int that represents the message seen status, 0 for unseen and 1 for seen
     * @return String
     */
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

    /**
     * A function to display a chosen classes details 
     * @param $class, an array consisting of the database details for one class
     * @param $count the count of total students in that class
     * @return String representing the html to show a chosen classes details
     */
    function showClassDetails($class, $count)
    {
        $classDetails = "
            <div id='expanded-class-details'>
                <div id='class-details'>
                    <span>subject: {$class['subject_name']}</span>
                    <span>class size: {$count}</span>
                </div>
                <div id='class-title'>
                    <h2>{$class['class_desc']}</h2>
                </div>
                    <div id='class-options-wrapper'>
                        <a class='class-option' href='./index.php?class={$class['class_id']}'><span >start class chat</span></a>
                        <a class='class-option' href='/doctrina/planner/manageClasses.php'><span >manage class</span></a>
                        <a class='class-option' href='/doctrina/planner/addClassForm.php'><span >Create a new class</span></a>
                    </div>
            </div>
        ";
        return $classDetails;
    }

     

    /**
     * A function to create a student contact item for the contacts list, 
     * called when logged in user is a teacher 
     * @param $student: an array consisting of the student details from the database
     * @param $classID: the chosen classID attached to the contact as an attribute to allow
     *  js to collect it and use it in an ajax call
     * @return String representing the student contact
     *  */             
    function studentComponent($student,$classID){
        //default set the image to appear when the user is offline(red dot around user name)
        $statusImage = "./chat_images/offline.png";
        //add class styling to create a red shadow around red dot
        $statusBorder = "offline";
        //check if the recipient is offline
        if($student['user_online_status'] == 1) 
        {
            //change the image to represent user online (green dot appears next to name)
            $statusImage = "./chat_images/online.png";
            //add class styling to create a green shadow around image
            $statusBorder = "online";
        }
        //create the html to represent a class contact
        $studentComp = <<<STUDENT
            <div class="class-link" data-classID={$classID} data-userID={$student['user_id']} onclick="selectChatUser(event)">
                <!--<div class="student-name">-->
                    {$student['user_forename']} {$student['user_surname']}
                    <br/><!--maybe?? don't know might be crap looking-->
                    <img class={$statusBorder} src={$statusImage} width='10'>
                    <!--</div>-->
            </div>
STUDENT;
        return $studentComp;
    }
    /**
     * A function to create a class contact item for the contacts list, 
     * called when logged in user is a student, it displays the class details and the teachers name 
     * @param $classID: the chosen classID attached to the contact as an attribute to allow
     *  js to collect it and use it in an ajax call
     * @return String representing the class contact
     *  */  
    function classComponent($class){
        //default set the image to appear when the user is offline(red dot around user name)
        $statusImage = "./chat_images/offline.png";
        //add class styling to create a red shadow around red dot
        $statusBorder = "offline";
        //check if the recipient is offline
        if($class['user_online_status'] == 1) 
        {
            //change the image to represent user online (green dot appears next to name)
            $statusImage = "./chat_images/online.png";
            //add class styling to create a green shadow around image
            $statusBorder = "online";
        }
        //create the html to represent a class contact
        $studentComp = <<<STUDENT
            <div class="class-link" data-classID={$class['class_id']} data-userID={$class['user_id']} onclick="selectChatUser(event)">
                    Subject: {$class['class_desc']}<br/>
                    Teacher: {$class['user_forename']} {$class['user_surname']}<br/>
                    <img class={$statusBorder} src={$statusImage} width='10'>
            </div>
STUDENT;
        return $studentComp;
    }

?>