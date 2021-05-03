<?php
    //functions that are not related to creating page content such as performing input validation
    
    /**
     * Function to check if the user logged in is a teacher or student 
     * @return bool: true if user is a teacher (session variable = 2) or false if the user is a student (session variable = 1)
     */
    function checkIfTeacher()
    {
        if(isset($_SESSION['type']))
        {
            if($_SESSION['type'] == 2)
            {
                //user is a teacher 
                return true; 
            }
        }
        return false;
    }

    /**
     * Function to check if a user is logged in, redirects to login page if user id is not set in session array 
     */
    function checkLoggedIn(){
        if(!isset($_SESSION['user_id'])) 
        {
            redirectToLogin();
        }
    }

    /**
     * Function to redirect a user to the login page
     * The user is first directed to the logout script which destroys the session array then directs to the login form
     */
    function redirectToLogin()
    {
        //send user to logout 
        header('location: /login/includes/logout.inc.php'); //this needs changed to the absolute path of login****************************
        die(); //prevents the rest of the script from running after the redirect 
    }


    /**
     * Function to validate and sanitise input by stripping whitespace, special characters nad slashes. 
     * @param String, the users input 
     * @return String, the validated user input
     */
    function validateInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input); 
        $input = htmlspecialchars($input);
        return $input;
    }


?>