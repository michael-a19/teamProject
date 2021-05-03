<?php
    //Script to create a new page and form to allow a new user to be registered
    session_start(); 

    //check if the user is already logged in
    if( isset($_SESSION['user_id']) ){
        //redirect to homepage if already logged in
        header('location: ./index.php');
    }


    include_once("./includes/pagefunctions.inc.php");
    $error = "";

    echo pageStart("Login", "./styles/style.css");
    echo createBanner();
    echo '<main class="background1">'; 
    echo generateFormError();
?>
    <div id="main-heading">
        <h1>Create An Account</h1>
    </div>

        <div id="form-container" >
            <form id="account-form" method="post" action="./includes/register.inc.php">
                    
                <label class="label" for="fname">Enter your first name</label>
                <input class="reg-item" type="text" name="fname" required><br />
                <label class="form-error"></label>

                <label class="label"  for="lname">Enter your last name</label>
                <input class="reg-item"type="text" name="lname" required><br />
                <label class="form-error" id="error-lname"></label>


                <label class="label" for="email">Enter your email address</label>

                <input class="reg-item" type="email" name="email" required><br />

                <label class="label" for="password">Enter your password</label>

                <input type="password" class="reg-item" name="password" required><br>

                <label class="label" for="password2">Re-enter your password</label>

                <input type="password" class="reg-item"  name="password2" required><br> 

                <input type='submit' id="form-button"  value="Register Account" name="register"><br>
                    <a id="register-account" href="login.php">
                        <span>Login</span>
                    </a>
            </form>
                    
        </div>
</main>

    <script>
        //function to get an element by its id
        function getElement(id){
            return document.getElementById(id);
        }

        //get submit button
        let buttons = getElement('form-button');

        //add event listener to the buttton
        buttons.addEventListener('click', function(event) {
            if(!validateForm('account-form'))
            {
                event.preventDefault(); 
                //prevent submission
            }
        });
        //front end validation 
        function validateForm(formID){
            //get form
            let myForm = getElement(formID); 
            //get all input elements
            let inputs = myForm.getElementsByTagName("input");
            //define string to hold errors (will remove and assign tooltips to imputs maybe or show erroes on page)
            let errors = ""
            //check if each element is empty, if so set indicators to alert user to empty fields
            for(let i = 0; i < inputs.length; i++){
                if(inputs[i].value === "" || inputs[i] === " "){
                    errors += "empty " + inputs[i].name + " field "; 
                    inputs[i].style.border = "1px solid red";
                    inputs[i].style.backgroundColor = "pink";
                    inputs[i].setAttribute('placeholder', "* Required Field");
                }
            }
            //if errors occured then return false
            if(errors != ""){
                return false;
            }
            else {
                //return true if no errors
                return true; //no errors
            }
            
        }

        
    </script>

<?php
    pageEnd();
?>