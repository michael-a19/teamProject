<?php
//change the name of this to profile and put it in the settings folder so it says settings/profile in the url  OR OR 
//make a folder called profile and name this update?
/* get old password ask user to enter old password and new password if they match then set the password to the new one 


*/ 
session_start();
include_once('./classes/recordset.class.php'); 
include_once("./includes/pagefunctions.inc.php"); 
include_once("./includes/functions.inc.php"); 

//check if the user is logged in, if not redirect to login page
checkLoggedIn(); 

//db connection
$recset = new RecordSet("../logindb.sqlite");

$pageContent = "";

///get details of the logged in user 
$query = "SELECT * FROM tp_users WHERE user_id = :userID LIMIT 1"; 
$params['userID'] = $_SESSION['user_id']; //

$userDetails = $recset->getRecordSet($query, $params); 
if(is_array($userDetails))
{
    $userDetails = $userDetails[0]; 
    //set main to calc 100vh - 5rem
    $pageContent = <<<FORM
    

        <div id="main-heading">
            <h1>Update your profile details</h1>
        </div>
        <div id="inner-form-container" >
            <form id="account-form" method="post" action="./includes/updateprofile.inc.php">
                    
                <label class="label" for="fname">First name</label>
                <input class="reg-item" type="text" name="fname" required value={$userDetails['user_forename']}><br />
                <label class="form-error"></label>

                <label class="label"  for="lname">Last name</label>
                <input class="reg-item"type="text" name="lname" required value={$userDetails['user_surname']}><br />
                <label class="form-error" id="error-lname"></label>


                <label class="label" for="email">Enter your email address</label>

                <input class="reg-item" type="email" name="email" required value={$userDetails['user_email']}><br />
                
                <label class="label" for="password">Enter your password to confirm changes</label>
                <input type="password" class="reg-item" name="password" required><br>

                <input type='submit' id="form-button"  value="Save changes" name="save"><br>

            </form>
                    
        </div>
FORM;
}
else
{
    $pageContent = "<h2>Error loading account details</h2>"; //might be better to return to an error apge with a message
}
echo pageStart('Update profile', "./styles/style.css");
//echo pageBanner(); move this banner into page banner and conditionally load the content, just keep logo if not logged in
echo createNav();
echo createBanner();
echo "<main>";
echo notifcaitonBanner();
echo generateFormError();


echo $pageContent;
echo "</main>";
pageEnd();
?>