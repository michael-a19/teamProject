<?php 

function pageStart($title, $css, $js = "") {
    session_start(); 
    $jsLink = ""; 
    if(!empty($js))
    {
        $jsLink = "<script src={$js} type='text/javascript'></script>";
    }
    $pageStart = <<<START
    <!DOCTYPE HTML>
    <html>
        <head>
            <meta charset="utf-8">

            <title>$title</title>

            <link rel="stylesheet" href="/login/styles/style.css" type="text/css">
            <link rel="stylesheet" href=$css type="text/css">
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

function pageEnd(){
    $pageEnd = <<<END
           </div>
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
     * function to create the navigation bar for the website [change the absolute path of the images if change directory name]
     */
    function createNav(){
        $navbar = <<<NAV
            <nav class="navbar"> 
                <ul class="navbar-nav">
                    <li class="menu-icon" id="menu-button">
                        <span class="nav-link">
                            <img class="burger-img" src="/login/images/icons/burger.svg">
                            <!--<img class="burger-img" src="/group/login/images/icons/burger.svg">-->
                            <span class="link-text">A P P - N A M E </span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class="nav-img" src="/login/images/icons/chevronRight.svg">
                            <!--<img class="nav-img" src="/group/login/images/icons/chevronRight.svg">-->
                            <span class="link-text">option 1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/login/chat/index.php" class="nav-link">
                            <img class="nav-img" src="/login/images/icons/chevronRight.svg">
                            <!--<img class="nav-img" src="/group/login/images/icons/chevronRight.svg">-->
                            <span class="link-text">Class Chat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <img class="nav-img" src="/login/images/icons/chevronRight.svg">
                         <!--   <img class="nav-img" src="/group/login/images/icons/chevronRight.svg">-->
                            <span class="link-text">option 1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/login/planner/plannerWeek.php" class="nav-link">
                            <img class="nav-img" src="/login/images/icons/chevronRight.svg">
                            <span class="link-text">Planner</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <img class ="nav-img" src="/login/images/icons/cog.svg">
                            <!--<img class ="nav-img" src="/group/login/images/icons/cog.svg">-->
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
                            <a class="banner-dropdown-item" href="/login/index.php">Home</a>
                            <a class="banner-dropdown-item" href="/login/changeprofile.php">Profile</a>
                            <a class="banner-dropdown-item" href="/login/password.php">Password</a>
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
                        <a id = "logo" href="/login/index.php">
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

