<?php 

function pageStart($title, $css) {
    //should probably put the fonts in as an option 
    $pageStart = <<<START
    <!DOCTYPE HTML>
    <html>
        <head>
            <meta charset="utf-8">

            <title>$title</title>

            <link rel="stylesheet" href="./styles/style.css" type="text/css">
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@200;300&family=Shadows+Into+Light&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
        </head>
        <body>
START;
    return $pageStart; 
}

function pageEnd(){
    $pageEnd = <<<END
        </body>
    </html>
END;
    return $pageEnd;
}


function generateFormError(){
    $error = ""; 
    if(isset($_SESSION['error']))
    {
        $errorMsg = $_SESSION['error'];
        //unset to prevent error persisting after page refresh
        unset($_SESSION['error']);
        $error = <<<ERROR
                <div id='error-banner'>
                    <span id="error-message">$errorMsg</span>
                </div>
ERROR;
    }
    return $error;
}


function checkSessionVariable($key)
{
    return isset($_SESSION[$key]);
}




    /**
     * function to create the navigation bar for the website [change the absolute path of the images if change directory name]
     */
    function createNav(){
        $navbar = <<<NAV
            <nav class="navbar"> 
                <ul class="navbar-nav">
                    <li class="menu-icon" id="menu-button">
                        <span class="nav-link">
                            <img class="burger-img" src="/login/images/icons/burger.svg">
                            <span class="link-text">A P P - N A M E </span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class="nav-img" src="/login/images/icons/chevronRight.svg">
                            <span class="link-text">option 1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class="nav-img" src="/login/images/icons/chevronRight.svg">
                            <span class="link-text">option 1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class ="nav-img" src="/login/images/icons/chevronRight.svg">
                            <span class="link-text">option 1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class ="nav-img" src="/login/images/icons/cog.svg">
                            <span class="link-text">Settings</span>
                        </a>
                    </li>
            </ul>
        </nav>
NAV;
        return $navbar;
    }

    /**
     * function to create the page banner and conditionally create an options menu if user is logged in
     */
    function createBanner() {
        //check if the user is logged in, if so display the logout dropdown
        $logoutMenu ="";
        if(isset($_SESSION['loggedIn']))
        {
            $name = $_SESSION['fname'] . " " .  $_SESSION['lname'];


            $logoutMenu = <<<LOGOUT
                <div id="banner-dropdown-container">
                    <button id="banner-dropdown-button">$name</button>
                    <div id="dropdown-visibility">
                        <div id="dropdown-content">
                            <a class="banner-dropdown-item" href="#">Link 1</a>
                            <a class="banner-dropdown-item" href="#">Link 2</a>
                            <a class="banner-dropdown-item" href="/login/includes/logout.inc.php">Sign out</a>
                        </div>
                    </div>
                </div>
LOGOUT;
        }
        //change the absolute value 
        $banner = <<<BANNER
            <div id="page-banner">
                <div id="banner-left-container">
                    <div id="logo-container">
                        <a id = "logo" href="">
                            <img src="/login/images/icons/logo2.png" width="500">
                        </a>
                    </div>
                </div>
                <div id="banner-right">
                    $logoutMenu
                </div>
            </div>
BANNER;
        return $banner;
    }
?>

