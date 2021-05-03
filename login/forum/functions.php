<?php
    // function to create the page banner and conditionally create an options menu if user is logged in
    function createBanner() {
        //check if the user is logged in, if so display the logout dropdown
        $logoutMenu ="";
        if(isset($_SESSION['user_id']))
        {
            $name = $_SESSION['user_forename'] . " " . $_SESSION['user_surname'];

            //change banner classes to drop down settings
            $logoutMenu = <<<LOGOUT
                <div id="banner-dropdown-container">
                    <button id="banner-dropdown-button">$name</button>
                    <div id="dropdown-visibility">
                        <div id="dropdown-content">
                            <span class="banner-dropdown-title" >Account settings</span>
                            <a class="banner-dropdown-item" href="../index.php">Home</a>
                            <a class="banner-dropdown-item" href="../changeprofile.php">Profile</a>
                            <a class="banner-dropdown-item" href="../password.php">Password</a>
                            <a class="banner-dropdown-item" href="../includes/logout.inc.php">Sign out</a>
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
                        <a id = "logo" href="../index.php">
                            <img src="../images/icons/logo2.png" width="500">
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