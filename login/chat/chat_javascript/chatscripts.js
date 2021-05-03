/**
 * @author Michael Anderson
 * @author W18032122
 */
//add event listener to prevent js loading until page does 
// window.addEventListener('load', (event) => {

/**
 * function to select an element by it's ID
 * @param String: the elements ID
 * @return The html element or null if not found
*/
function getByID(elementID) {
    return document.getElementById(elementID);
}

//global variable for chosen chat recipent and the current classID
let SELECTED_CHAT_RECIPIENT = "";
let CURRENT_CLASSID = "";

//get chatbar containing send button and text input 
let chatBar = getByID("chat-box");

//get the button used to send a message
let chatButton = getByID("chat-send");
//add an event listener to that button 
chatButton.addEventListener("click", (event) => {
    sendMessage(event);

});

//call the function to set the interval and refresh the chat messages
updateChat();

//get the chat text box 
let chatTextBox = getByID("chat-text");

//add eventlistener to allow pressing enter to send a message
chatTextBox.addEventListener("keyup", event => {
    if (event.keyCode == 13) {
        sendMessage();
    }
});



/** 
 * Function to handle the response sent from the chat API router
 * The response from the chat api will contain a string called responseType
 * This function collects that string and uses it to decide which functionality to run
* @param json object recieved from the api containing a response type and an object of data
*/
function handleResponse(responseData, responseType) {

    //get the div to contain the chat messages
    let messageContainer = getByID("messages-container");

    //decide action based on the responseType from the response from the API 
    switch (responseData.responseType) { 
        case "sendMessage":
            /// include("./sendMessage.inc.php");
            alert("message sent sort out the text first tho");
            break;

        case "getMessages":
            //this updates the chat messages
            getMessages(); 
            break;
            //start the chat with the recipient

        case "startChat":

            //add the returned messages to the innerHTML of the chat message container 
            messageContainer.innerHTML = responseData.responseData.data; //probably clean it up and just pass reponse data in 
            //scroll chat window to bottom to show newest messages first 
            messageContainer.scrollTo(0, messageContainer.scrollHeight);
            break;

        case "error":
            //display any errors returned from the chat api as a popup
            alert(responseData.responseData.data);
            break;

        default:
            break;
            resetTextBox();
    }
    
}


/**
 * Function to make AJAX requests to the chat API 
 * 
 * @param requestData_ object to hold any data to be posted to the api 
 * @param requestType_ string to specify the request type
 */
function makeRequest(requestData_, requestType_) {

    //create object to hold data and request type
    data = {
        requestType: requestType_,
        requestData: requestData_
    }
    //define the chat api script
    let URL = "./includes/chatapi.inc.php";
    //send request to the chat api
    fetch(URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            //pass the response data to the callback handleResponse function
            handleResponse(data, data.responseType); 
        })
        .catch((error) => {
            console.error('Error occured when making request:', error);
        });
}

/**
 * Function to send a chat message 
 * Text is taken from the textbox and sent to the chat api
 * @param event 
 */
function sendMessage(event) {
    //get the chatbox element
    let textBox = getByID("chat-text");
    //get and trim the text within it
    let text = textBox.value.trim();

    //prevent empty string being sent
    if (text === "") { 
        alert("Error, empty messages not allowed");
    }
    else {
        //send an ajax request to the chat api with the chatid recipient id and message contents 
        //specifying to save the message to the database
        makeRequest({
            recipientID: SELECTED_CHAT_RECIPIENT,
            classID: CURRENT_CLASSID,
            message: text
        }, "sendMessage");
        //reset the text input field and set focus
        resetTextBox();
    }
}


/**
 * A function to select the chat recipient from the contact list and set global variables 
 * to the class id and chosen recipients chat ID then to call the get message function to get
 * all chat messages if they exist
 * @param event: from the contact item 
 */
function selectChatUser(event) {

    //set the global variables to equal the selected recipient and class id 
    let selectedRecipientID = event.target.getAttribute("data-userID");
    let currentClassID = event.target.getAttribute("data-classID");

    //get the chat id and user id from the chosen contact
    SELECTED_CHAT_RECIPIENT = selectedRecipientID;
    CURRENT_CLASSID = currentClassID;

    //get the container around the chat input button and text field
    let chatBoxVisibility = getByID("chat-box-visibility");
    //let textBox = getByID("chat-text");
    //let chatButton = getByID("chat-send");

    //show the chat box after selecting a user
    chatBoxVisibility.classList.remove("hidden");
    chatBoxVisibility.classList.remove("visible");

    //call the get message function to collect all messages
    getMessages();
}

/**
 * A function to get the previous and current messages between the user and recipient for this class
 */
function getMessages() {
    //set the json object to send to the chat api to include the current classid and recipient user id
    makeRequest({
        recipientID: SELECTED_CHAT_RECIPIENT,
        classID: CURRENT_CLASSID
    }, "startChat");
}

/**
 * function to empty the chat box, used after a message is sent
 */
function resetTextBox() {
    // the chat text input box
    let textBox = getByID("chat-text");
    //set to empty string 
    textBox.value = "";
    //set to focus to allow fast typing
    textBox.focus();
}

/**
 * Function to set and interval to refresh the chat messages and display new messages
 */
function updateChat() {

    setInterval(function () {
        
        //don't start interval until the chat recipient is chosen
        if (SELECTED_CHAT_RECIPIENT != "") {
            
            //get messsages from database
            getMessages();
        }
    }, 5000); //wait 5 seconds
}
