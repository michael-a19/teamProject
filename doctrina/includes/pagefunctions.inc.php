<?php 
    //functions used to create a html page

    /**
     * Function to create the starting html of a page
     * @param Stirng page title
     * @param Stirng PATH TO CSS FILE
     * @param Stirng path to js file
     */
function pageStart($title, $css="", $js = "") {
    $cssLink = "";
    $jsLink = ""; 
    if(!empty($js))
    {
        $jsLink = "<script src={$js} type='text/javascript'></script>";
    }
    if(!empty($css))
    {
        $cssLink = "<link rel='stylesheet' href=$css type='text/css'>";
    }
    $pageStart = <<<START
    <!DOCTYPE HTML>
    <html>
        <head>
            <meta charset="utf-8">

            <title>$title</title>

            <link rel="stylesheet" href="/doctrina/styles/style.css" type="text/css">
            $cssLink
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@200;300&family=Shadows+Into+Light&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
            $jsLink
        </head>
        <body>
            <div id="full-page-container">
START;

    return $pageStart; 
}

/**
 * Function to create the ending of the html
 */
function pageEnd(){
    $pageEnd = <<<END
           </div>
        </body>
    </html>
END;
    return $pageEnd;
}

/**
 * Generates an displays an error if one occures
 */
function generateFormError(){
    $error = ""; 
    if(isset($_SESSION['error']))
    {
        $errorMsg = $_SESSION['error'];
        unset($_SESSION['error']);
        $error = <<<ERROR
                <div id='error-banner'>
                    <span id="error-message">$errorMsg</span>
                </div>
ERROR;
    }
    return $error;
}

/**
 * Creates a notification bar to display notifications similar to the error bar
 */
function notifcaitonBanner() {
    $notifcation = ""; 
    if(isset($_SESSION['notifcation']))
    {
        $notifcationMsg = $_SESSION['notifcation'];
        //unset to prevent notifcation persisting after page refresh
        unset($_SESSION['notifcation']);
        $notifcation = <<<NOTIFICATION
                <div id='notifcation-banner'>
                    <span id="notifcation-message">$notifcationMsg</span>
                </div>
NOTIFICATION;
    }
    return $notifcation;
}

function checkSessionVariable($key)
{
    return isset($_SESSION[$key]);
}




    /**
     * function to create the navigation bar for the website
     */
    function createNav(){
        $teacherOption = "";
        if( (isset($_SESSION['type'])) && ($_SESSION['type'] == 2) )
        {
            $teacherOption = "
                <li class='nav-item'>
                    <a href='/doctrina/planner/manageClasses.php' class='nav-link'>
                    <img class='nav-img' src='/doctrina/images/icons/chevronRight.svg'>
                    <span class='link-text'>Manage classes</span>
                </a>
            </li>";
        }
        $navbar = <<<NAV
            <nav class="navbar"> 
                <ul class="navbar-nav">
                    <li class="menu-icon" id="menu-button">
                        <span class="nav-link">
                            <img class="burger-img" src="/doctrina/images/icons/burger.svg">
                            <span class="link-text">A P P - N A M E </span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="/doctrina/quiz/classeslist.php" class="nav-link">
                            <img class="nav-img" src="/doctrina/images/icons/chevronRight.svg">
                            <span class="link-text">Class quiz</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/doctrina/chat/index.php" class="nav-link">
                            <img class="nav-img" src="/doctrina/images/icons/chevronRight.svg">
                            <span class="link-text">Class Chat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/doctrina/forum/forum.php" class="nav-link">
                        <img class="nav-img" src="/doctrina/images/icons/chevronRight.svg">
                            <span class="link-text">Forum</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/doctrina/planner/plannerWeek.php" class="nav-link">
                            <img class="nav-img" src="/doctrina/images/icons/chevronRight.svg">
                            <span class="link-text">Planner</span>
                        </a>
                    </li>
                    {$teacherOption}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class ="nav-img" src="/doctrina/images/icons/cog.svg">
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
        if(isset($_SESSION['user_id']))
        {
            $name = $_SESSION['user_forename'] . " " .  $_SESSION['user_surname'];

            //change banner classes to drop down settings or some shit 
            $logoutMenu = <<<LOGOUT
                <div id="banner-dropdown-container">
                    <button id="banner-dropdown-button">$name</button>
                    <div id="dropdown-visibility">
                        <div id="dropdown-content">
                            <span class="banner-dropdown-title" >Account settings</span>
                            <a class="banner-dropdown-item" href="/doctrina/index.php">Home</a>
                            <a class="banner-dropdown-item" href="/doctrina/changeprofile.php">Profile</a>
                            <a class="banner-dropdown-item" href="/doctrina/password.php">Password</a>
                            <a class="banner-dropdown-item" href="/doctrina/includes/logout.inc.php">Sign out</a>
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
                        <a id = "logo" href="/doctrina/index.php">
                            <img src="/doctrina/images/icons/logo2.png" height="50">
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

