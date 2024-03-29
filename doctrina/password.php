<?php 
    //create page and form to allow a user to change their password 
    session_start();
    include_once('./classes/recordset.class.php'); 
    include_once("./includes/pagefunctions.inc.php"); 
    include_once("./includes/functions.inc.php"); 
    //check if the user is logged in, if not redirect to login page
    checkLoggedIn();

    //db connection
    $recset = new RecordSet("../logindb.sqlite");

    $pageContent = "";
    //create the form for the user
    $pageContent = <<<FORM
        <div id="main-heading">
            <h1>Update password</h1>
        </div>
   
        <div id="form-container" >
            <form id="account-form" method="post" action="./includes/updatepassword.inc.php">
                    
              
                
                <label class="label" for="oldPpassword">Please enter your current password</label>
                <input type="password" class="reg-item" name="oldPassword" required><br>
                <label class="label" for="newPassword">Please enter your new password</label>
                <input type="password" class="reg-item" name="newPassword" required><br>
                <label class="label" for="newPassword2">Re-nter your new password</label>
                <input type="password" class="reg-item" name="newPassword2" required><br>

                <input type='submit' id="form-button"  value="Save changes" name="save"><br>

            </form>
                    
        </div>
FORM;

echo pageStart('Update profile', "./styles/style.css");

echo createNav();
echo createBanner();
echo "<main>";
echo notifcaitonBanner();
echo generateFormError();

//display the page content
echo $pageContent;
echo "</main>";
pageEnd();
?>
