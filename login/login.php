<?php
    //php script to allow a user to login, creates a page containing a login form
    session_start(); 
    //redirect if user is already logged in
    if( isset($_SESSION['user_id']) ){
        //redirect to homepage
        header('location: ./index.php');
    }
    include_once("./includes/pagefunctions.inc.php");
    
    $error = "";

    echo pageStart("Login");
    echo createBanner();
    echo "<main class='background2'>";
    echo generateFormError(); 
?>
        <div id="main-heading">
            <h1>Account Login</h1>
        </div>

        <div id="form-container" >
            <form id="account-form" method="post" action="./includes/login.inc.php">
            
                <label class="label" for="email">Enter your email address</label>

                <input class="reg-item" type="email" name="email" required><br />

                <label class="label" for="password">Enter your password</label>

                <input type="password" class="reg-item" name="password" required><br>

                <input type='submit' id="form-button"  value="Login" name="login">
                <br>
                <a id="register-account" href="./register.php">
                    <span>Register an account</span>
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

            if(!validateForm('account-form')){

                //if comes back false there are empty fields 
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
            let errors = "";

            //check if each element is empty and assign css to them to indicate required fields
            for(let i = 0; i < inputs.length; i++){
                if(inputs[i].value === "" || inputs[i] === " "){
                    errors += "empty " + inputs[i].name + " field "; //add tooltip or to div, this just for testing
                    inputs[i].style.border = "1px solid red";
                    inputs[i].style.backgroundColor = "pink";
                    inputs[i].setAttribute('placeholder', "* Required Field");
                }
            }
            //return false if errors 
            if(errors != ""){
                return false;
            }
            else {
                return true; //no errors
            }
        }
    </script>


<?php
    pageEnd();
?>