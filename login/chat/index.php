<?php
    session_start();
    include_once("../includes/pagefunctions.inc.php"); //shit
    include_once("./includes/chatpagefunctions.inc.php");
    include_once("../includes/functions.inc.php");

    //check logged in 
    checkLoggedIn();
    //login section needs to set the type in sessioon to Teacher or Student
    $isTeacher = false; 
    if(checkIfTeacher())
    {
       $isTeacher = True;
    }

    
    //include_once("./includes/pagefunctions.inc.php");
    include_once("../classes/recordset.class.php"); //chnage to absolute path of the website (www.newnu etc)
    $recset = new RecordSet('./logindb.sqlite'); //from here will it be better to instantiate at top?
    //variable to hold the contact section 
    $contactSection = "";
    
    //get classID from the url or something 
    if($isTeacher)
    {
        //get if
        if(!isset($_GET['class']))
        {
            //redirect ot the page to select a class 
            header("location: ./classes.php");
            die();
        }
        //set class id
        $classID =  htmlspecialchars($_GET["class"]); 
        //get class details 
        //$classQuery = "SELECT className, classSubject from class WHERE classID = :classID limit 1";
        $classQuery = "SELECT tp_class.class_desc, tp_subject.subject_name FROM tp_class 
                       JOIN tp_subject ON tp_class.subject_id = tp_subject.subject_id 
                       WHERE tp_class.class_id  = :classID
                       LIMIT 1";





        $params['classID'] = $classID; //need to add the teacher id here too
        $classDetails = $recset->getRecordSet($classQuery, $params);
        //check query returned results 
        if(count($classDetails) > 0) {
            
            $classDetails = $classDetails[0];

            $contactSection .="
                       <div id='contact-banner'>
                           <span id='contact-banner-text'>class register for {$classDetails['class_desc']}</span>
                       </div>";

            //get students for class
           // $studentsQuery = "SELECT users.fname, users.lname, users.userID, users.type, users.online_status from classRegister
           // join users on users.userID = classRegister.studentID WHERE classRegister.classID = :classID";
            $studentsQuery = "SELECT tp_users.user_forename, tp_users.user_surname, tp_users.user_id, tp_user_type.user_type_desc, tp_users.user_online_status from tp_class_members
            join tp_users on tp_users.user_id = tp_class_members.user_id 
            JOIN tp_user_type on tp_user_type.user_type_id = tp_users.user_type_id 
            WHERE tp_class_members.class_id = :classID";
            $classStudents = $recset->getRecordSet($studentsQuery, $params);
            //check if students returned from database
            if(count($classStudents) > 0)
            {    
                //for each student found in class print details 
                foreach($classStudents as $studentRecord)
                {
                   $contactSection .= studentComponent($studentRecord, $classID);
                }
                $contactSection .= "<a href='./classes.php' class='student-wrapper-button'>Select another class</a>";
            }
            else
            {
                   //no students found for that class
                $contactSection .= "
                    <div id='students-error'>
                        {$classDetails['class_desc']} has no enrolled students
                        <a href='manageclass.php?class={$classID}'>Enroll students</a>
                    </div>";
                $contactSection .= "<a href='./classes.php' class='student-wrapper-button'>Select another class</a>";
            }//END OF STUDENTS GET PART 
        }
        else
        {
            //no class found 
            $contactSection .= "
            <div id='students-error'>
            Class does not exist <a href='createclass.php'>Create one</a>
            </div>";   
        }//END OF CLASS FIND DETAILS 

    }//END of is teacher 
    else 
    { //
        //user is a student so load the student view /////////joininh udrtd on techer id 
        //get the classes and the teacher details that teach that class for the current student user
        $studentClassesQuery = "SELECT tp_class.class_desc, tp_subject.subject_name, tp_class.class_id, tp_users.user_id, tp_users.user_forename, tp_users.user_surname, tp_users.user_online_status 
        from tp_class JOIN tp_subject on tp_subject.subject_id = tp_class.subject_id 
        Join tp_class_members on tp_class_members.class_id = tp_class.class_id 
        JOIN tp_class_teacher on tp_class_teacher.class_id = tp_class.class_id 
        JOIN tp_users on tp_users.user_id = tp_class_teacher.teacher_id 
        WHERE tp_class_members.user_id = :userID";
        $params = array(); 
        $params['userID'] = $_SESSION['user_id'];

        $studentsClasses = $recset->getRecordSet($studentClassesQuery, $params);;

        if(count($studentsClasses) > 0)
        {
            //if students classes are found loop through each class and display its contents 
            foreach($studentsClasses as $class)
                {
                   $contactSection .= classComponent($class);
                }
        }
        else
        {
            $contactSection = "<div id='students-error'>
            you are not currently enrolled in any classes
            </div>";   
            
            //student has nop classes 
            //you are currently enrolled on no classes
        }
        //get all classes for current student 
        //display in the contact section the same 
        //display teacher name also 
    }
    //$contactSection = "";
    
    

    echo pageStart("chat home", "./chat_styles/chat_style.css");
    echo createNav();
    echo createBanner(); 
     echo "<main>";
  
?>

    <div id="chat-page-wrapper">

    <!--nav and shit go here-->
        <div id="chat-content-wrapper"> 
            <!--only for the main page content not borders and shit-->
            <!--the side pannel and the other thing will go here-->
            <div id="contacts-wrapper">
                <!-- generate this with php and set a custom attribute to the id value then get 
                in the js below with document..getAttribute();-->
                <?php
                    echo $contactSection;
                ?>
            </div> <!-- end of contacts wrapper -->
            <div id="chat-wrapper"> 
                <!-- chat messages here --> 
               
                <div id="messages-container"><!--this is what i need to get to update-->
                    
                    
            
                </div><!--end of message-container-->

                <!-- <div id="chat-box">changing this style-->

                <div id="chat-box-visibility" class="hidden">
                    <div id="chat-box" class="hidden">
                        <input class="chat-text-box chat-input" id="chat-text"type="text" placeholder="enter your message">
                        <input class="chat-input chat-button" id="chat-send" type="submit">

                    </div><!--end of chat-stuff-->
                </div><!--end of chat-visibility-->
            </div><!--end of chat-wrapper-->
        </div> 
</div>
        <!--end of page-wrapper  full page shit -->
<?php
echo "</main>";
echo pageEnd();
?>
        <script type="application/javascript">
        
            //add event listener to prevent js loading until page does 
           // window.addEventListener('load', (event) => {

            //global variable for chosen chat recipent and the current classID
            let SELECTED_CHAT_RECIPIENT = "";
            let CURRENT_CLASSID = "";

            //function to select an element by it's ID
            function getByID(elementID){
                return document.getElementById(elementID);
            }

            
            //get chatbar containing send button adn text input 
            let chatBar = getByID("chat-box");

            //get the button used to sent a message
            let chatButton = getByID("chat-send");
            //add an event listener to that button 
            chatButton.addEventListener("click", (event) => {
                    sendMessage(event);
                    
            });

            updateChat();

            //get the chat text box
            let chatTextBox = getByID("chat-text");
            //add eventlistener to allow pressing enter to send a message
            chatTextBox.addEventListener("keyup", event => {
                if(event.keyCode == 13){
                    sendMessage();
                }
            
            });

            
            //function to handle the response from the chat api
            /*
            * param json object recieved from the api
            */ 
            function handleResponse(responseData, responseType) {
                //alert(responseData.responseData); //raw json object /////////////////////////////////////////////////
                //alert(responseData.responseData.message);
                //alert(responseType);
                console.log("respData " + responseData);
                console.log("type "+responseType);
                //the div that contains the chat messages

                let messageContainer = getByID("messages-container");
                
                //check if the result is empty, if not proceed
                
                //if(responseData.trim() != ""){

                    //Get the response from the api and parse it
                    /** could do some cool stuff here too with the status from api check if set to 200 if not show error etc */
                    
                    //  let responseObject = JSON.parse(responseData);
                    // console.log(responseObject); // no parse needed alrady parsed by fetch
                    //response is now a js object containing the data sent from the api in response to the request

                    //check logged in value from api, if not set send to the login form?
                    /*
                    if(typeof(responseObject) != 'undefined')
                    //check if the response is an object and logged in is not set to null
                    if(typeof(responseObject) === 'object' && responseObject.loggedIn !== null)
                    //think this last one is the one tbh check if logged in is object and if it is false
                    if(typeof(responseObject.loggedIn) === 'object' && (!responseObject.loggedIn)) {
                        //redirect if not set to logged in 
                        window.location = 'login.php' //although the page demands the user be logged in so wah probably pointless
                    }else{ put switch here }
                    */
                    //get the contact part of the screen 
                    let chatWindow = getByID()
                    let contactWindow = getByID(); //this one pointless because the contacts part is auto generatred 

                    /****** check the it is set first? */
                    //decide action based on the responseType from the response from the API 
                        switch(responseData.responseType){ // already seperating the array out yo  switch(responseObject.responseType) {
                        case "sendMessage":
                           /// include("./sendMessage.inc.php");
                           alert("message sent sort out the text first tho");
                        break;

                        case "getMessages":
                                getMessages(); //update the messages
                        break;

                        case "startChat":
                            
                            //.innerHTML = responseData.responseData.data;
                            //alert(responseData.message);
                            //alert("it works here yo");
                            //let messageContainer = getByID("messages-container"); 
                            messageContainer.innerHTML = responseData.responseData.data; //probably clean it up and just pass reponse data in 
                            //scroll chat window to bottom to show newest messages first 
                            messageContainer.scrollTo(0,messageContainer.scrollHeight);

                            console.log("works?");
                        break;
                        
                        case "error":
                            //set error message details over top of the screen?? pop up delete maybe
                            alert(responseData.responseData.message); //if messsage is set 

                        break;
                        default:
                            //alert("what");
                        break;
                        resetTextBox(); ///here?
                    }
                //}
            }//end of handleResponse (delete these)

           
            //function to make a request to the api 
            function makeRequest(requestData_, requestType_) {
                
                //ajax request. 
                //create object here with data send to api and use callback is handleresponse

                //using fetch 
                data = {
                    requestType:requestType_,
                    requestData:requestData_
                }
                let URL = "./includes/chatapi.inc.php";
                fetch(URL, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'}, //removed a , from inside cyurly 
                    body: JSON.stringify(data),
                    })
                    .then(response => response.json()) //text()/////////////////////////////////////////////////////
                    .then(data => { 
                            console.log("in fetch ",data);
                            //handleResponse(data.responseData, data.responseType); //responseData are is data not message do data 
                            handleResponse(data, data.responseType); //responseData are is data not message do data 
                        })
                        .catch( (error) => {
                            console.error('Error:', error);
                        });
                    }//end of makeRequest
            
            
                function sendMessage(event) {
                    let textBox = getByID("chat-text"); 
                    let text = textBox.value.trim(); 
                    //make sure isn't empty 
                    
                    if(text === "")
                    {
                        //do the thing send the data to the thing 
                        alert("empty message");
                    }
                    else
                    {
                        makeRequest({
                        recipientID:SELECTED_CHAT_RECIPIENT,
                        classID:CURRENT_CLASSID,
                        message:text
                    }, "sendMessage");
                    resetTextBox();
                    }     
                }
            

            //function to select the user from the contacts list to start chat
            function selectChatUser(event){
                
                // if(event.target.className == "student-name"){
                //     console.log("this true");
                //     event.target.parentElement.classList.add("selected");
                // }else{
                //     event.target.classList.add("selected");
                // }

               //et allContacts = document.querySelectorAll('.student-wrapper');
               //       allContacts.forEach((item,index) => {
               //           if(item !== event.target )
               //           item.classList.remove("selected"); /******************************************************sorty out  */
               //       });

                //if(SELECTED_CHAT_RECIPIENT == "") could also have the side slide over or something when select chatter
                
               

                chatBar.classList.remove("hidden");

                //alert(event.target.attributes.data-userID);
                let selectedRecipientID = event.target.getAttribute("data-userID");
                let currentClassID = event.target.getAttribute("data-classID");

                //if target id is student-name then user has clicked on child element so get ID from parent element
                if(event.target.className == "student-name" ){
                    selectedRecipientID = event.target.parentNode.getAttribute("data-userID"); 
                    currentClassID = event.target.parentNode.getAttribute("data-classID"); 
                }
                
                //get the chat id and user id from the chosen contact
                SELECTED_CHAT_RECIPIENT = selectedRecipientID;
                CURRENT_CLASSID = currentClassID;

                console.log("the thing is " + SELECTED_CHAT_RECIPIENT + " " + CURRENT_CLASSID );
                let chatBoxVisibility = getByID("chat-box-visibility");
                let textBox = getByID("chat-text");
                let chatButton = getByID("chat-send"); 

                //show the chat box after selecting a user
                chatBoxVisibility.classList.remove("hidden");
                chatBoxVisibility.classList.remove("visible");
                // textBox.setAttribute("readonly", false);
                // chatButton.setAttribute("disabled", false);
               // alert(SELECTED_CHAT_RECIPIENT);
                //alert(CURRENT_CLASSID);
                //get the chatshit
            //    makeRequest({
            //         recipientID:SELECTED_CHAT_RECIPIENT,
            //         classID:CURRENT_CLASSID 
            //    }, "startChat");
                getMessages();
            }
        
            function getMessages(){
                makeRequest({
                    recipientID:SELECTED_CHAT_RECIPIENT,
                    classID:CURRENT_CLASSID 
                }, "startChat");
            }
            //function to reset the text box (put in send message?)
            function resetTextBox(){
                let textBox = getByID("chat-text");
                textBox.value = "";
                textBox.focus();
            }

            //function to refresh chat and load new messages
            // function enableChat(){
            //     let textBox = getByID("chat-text");
            //     let chatButton = getByID("chat-send"); 
            //     if(SELECTED_CHAT_RECIPIENT === "")
            //     {
            //         textBox.readOnly = true; 
            //         chatButton.disabled = true;
            //     }
                
            // }
                    function updateChat() {
                      // timer to reload images every few seconds to get new messages!!!!!!!! 
                       //how do they do it relaly 
                        setInterval(function(){
                            //do here what do 
                            //depletes mobile data very quicjky
                            //alert("message")
                            if(SELECTED_CHAT_RECIPIENT != "")
                            {
                                //console.log("user id clicked " + userID);
                                //console.log("yes");
                                //var chat = _("label-chat"); 
                                // chat.checked = true; //think this is just for styling?
                                //chatPress(event);
                                //pass json to the api that has the the userid on the retrieve frm the api 
                                getMessages();
                                console.log("updated");
                            }
                        }, 5000); //wait 5 seconds, production set to 2 seconds or 2000ms
                    }

            </script>

         </script>

<?php 
    


    function studentComponent($student,$classID){
        $studentComp = <<<STUDENT
            <div class="student-wrapper" data-classID={$classID} data-userID={$student['user_id']} onclick="selectChatUser(event)">
                <div class="student-name">
                    {$student['user_forename']} {$student['user_surname']}
                </div>
            </div>
STUDENT;
        return $studentComp;
    }
    //will this even work with the classID stuff? or will it not work
    //change for styling
    function classComponent($class){
        $studentComp = <<<STUDENT
            <div class="student-wrapper" data-classID={$class['class_id']} data-userID={$class['user_id']} onclick="selectChatUser(event)">
                <div class="student-name">
                    Subject: {$class['class_desc']}<br/>
                    Teacher: {$class['user_forename']} {$class['user_surname']}
                </div>
            </div>
STUDENT;
        return $studentComp;
    }
?>