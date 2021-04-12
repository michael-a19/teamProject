<?php
    session_start(); 

    if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn'] == True){
        //redirect to homepage
        header('location: ./index.php');
    }


    include_once("./includes/pagefunctions.inc.php");
    $error = "";

    echo pageStart("Login", "./styles/style.css");
    echo createBanner();
    echo '<div id="page-container" class="background1">'; 
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
    </div>

    <script>
        //validate stuff trim and that 
        function getElement(id){
            return document.getElementById(id);
        }
        //get submit button
        let buttons = getElement('form-button');
        //add event listener to the buttton
        buttons.addEventListener('click', function(event) {
            if(!validateForm('account-form'))
            {
                console.log('errors');
                //if comes back false there are empty fields 
                event.preventDefault(); 
                //prevent submission and show error
            }
            
            //if no erros tehn submit allowed
            
        });
        //front end validation 
        //have script validation too
        function validateForm(formID){
            //get form
            let myForm = getElement(formID); 
            //get all input elements
            let inputs = myForm.getElementsByTagName("input");
            //define string to hold errors (will remove and assign tooltips to imputs maybe or show erroes on page)
            let errors = ""
            //check if each element is empty 
            for(let i = 0; i < inputs.length; i++){
                if(inputs[i].value === "" || inputs[i] === " "){
                    errors += "empty " + inputs[i].name + " field "; 
                    inputs[i].style.border = "1px solid red";
                    inputs[i].style.backgroundColor = "pink";
                    inputs[i].setAttribute('placeholder', "* Required Field");
                }
            }
            
            if(errors != ""){
                //replace the alert with something better 
                alert(errors);  //remove this section alltogether 
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