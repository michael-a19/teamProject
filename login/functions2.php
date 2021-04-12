<?php 


//     /**
//      * function to create the navigation bar for the website
//      */
//     function createNav(){
//         $navbar = <<<NAV
//             <nav class="navbar"> 
//                 <ul class="navbar-nav">
//                     <li class="menu-icon" id="menu-button">
//                         <span class="nav-link">
//                             <img class="burger-img" src="/group/login/images/icons/burger.svg">
//                             <span class="link-text">A P P - N A M E </span>
//                         </span>
//                     </li>
//                     <li class="nav-item">
//                         <a href="#" class="nav-link">
//                             <img class="nav-img" src="/group/login/images/icons/chevronRight.svg">
//                             <span class="link-text">option 1</span>
//                         </a>
//                     </li>
//                     <li class="nav-item">
//                         <a href="#" class="nav-link">
//                             <img class="nav-img" src="/group/login/images/icons/chevronRight.svg">
//                             <span class="link-text">option 1</span>
//                         </a>
//                     </li>
//                     <li class="nav-item">
//                         <a href="#" class="nav-link">
//                             <img class ="nav-img" src="/group/login/images/icons/chevronRight.svg">
//                             <span class="link-text">option 1</span>
//                         </a>
//                     </li>
//                     <li class="nav-item">
//                         <a href="#" class="nav-link">
//                             <img class ="nav-img" src="/group/login/images/icons/cog.svg">
//                             <span class="link-text">Settings</span>
//                         </a>
//                     </li>
//             </ul>
//         </nav>
//     NAV;
//         return $navbar;
//     }

//     /**
//      * function to create the page banner and conditionally create an options menu if user is logged in
//      */
//     function createBanner() {
//         //check if the user is logged in, if so display the logout dropdown
//         $logoutMenu ="";
//         if(isset($_SESSION['loggedIn']))
//         {
//             $name = $_SESSION['fname'] . " " .  $_SESSION['lname'];
//             $logoutMenu = <<<LOGOUT
//                 <div id="banner-dropdown-container">
//                     <button id="banner-dropdown-button">$name</button>
//                     <div id="dropdown-visibility">
//                         <div id="dropdown-content">
//                             <a class="banner-dropdown-item" href="#">Link 1</a>
//                             <a class="banner-dropdown-item" href="#">Link 2</a>
//                             <a class="banner-dropdown-item" href="/includes/logout.inc.php">Sign out</a>
//                         </div>
//                     </div>
//                 </div>
// LOGOUT;
//         }
//         //change the absolute value 
//         $banner = <<<BANNER
//             <div id="page-banner">
//                 <div id="banner-left-container">
//                     <div id="logo-container">
//                         <a id = "logo" href="">
//                             <img src="/group/login/images/icons/logo2.png" width="500">
//                         </a>
//                     </div>
//                 </div>
//                 <div id="banner-right">
//                     $logoutMenu
//                 </div>
//             </div>
// BANNER;
//         return $banner;
//     }



?>