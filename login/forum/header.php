<!-- Declare doc type and lang -->
<!DOCTYPE html lang="en">
<!-- HTML Head information -->
<head>
 	<title>Forum</title>
	 <!-- Link to CSS style sheet -->
	<link rel="stylesheet" href="style1.css" type="text/css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<!-- Begin body -->
<body id="forumBody">


<nav class="navbar"> 
            <ul class="navbar-nav">
                <li class="menu-icon" id="menu-button">
                    <span class="nav-link">
                        <img class="burger-img" src="../images/icons/burger.svg">
                        <span class="link-text">A P P - N A M E </span>
                    </span>
                </li>
                <li class="nav-item">
                    <a href="../index.php" class="nav-link">
                        <img class="nav-img" src="../images/icons/chevronRight.svg">
                        <span class="link-text">Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="planner.php" class="nav-link">
                        <img class="nav-img" src="../images/icons/chevronRight.svg">
                        <span class="link-text">Planner</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="forum.php" class="nav-link">
                        <img class ="nav-img" src="../images/icons/chevronRight.svg">
                        <span class="link-text">Forum</span>
                    </a>
                </li>

				<li class="nav-item">
                    <a href="../chat/index.php" class="nav-link">
                        <img class ="nav-img" src="../images/icons/chevronRight.svg">
                        <span class="link-text">Chat</span>
                    </a>
                </li>

				<li class="nav-item">
                    <a href="../login.php" class="nav-link">
                        <img class ="nav-img" src="../images/icons/chevronRight.svg">
                        <span class="link-text">Sign in</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <img class ="nav-img" src="../images/icons/cog.svg">
                        <span class="link-text">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div id="banner">

        </div>

		<script>
        let menuButton = document.getElementById('menu-button');
        menuButton.addEventListener('click', toggleMenu);

        function toggleMenu(){
            // console.log("works here");
            // let menu = document.getElementsByClassName('navbar'); 

            // if(menu[0].style.width === '5rem'){
            //     menu[0].style.width = '16rem';
            // }else {
            //     menu[0].style.width = '5rem';
            // }

            // console.log("fired");
            
            
        }
    </script>


<?php 
require_once('functions.php');
echo createBanner();

?>

<script>
//wrap in onload event to prevent from loading before page finishes 
//put into folder 
    function getElement(id){
        return document.getElementById(id);
    }

    //get drop down button 
    let dropdownButton = getElement("banner-dropdown-button");
    //add event listener to button 
           //get drop down button 
    dropdownButton.addEventListener('click', showDropDown);

    function showDropDown(){

        //get the drop down menu 
        let dropdownMenu = getElement("dropdown-visibility");
        //toggle the display value of the menu 
        if(dropdownMenu.style.display === "none"){
            dropdownMenu.style.display = 'block';
        }else {
            dropdownMenu.style.display = "none";
        }

    }
</script> 

<h1 id="titleHeader">Student Forum</h1>
	<div id="contentWrapper"> 
	<div id="optionBar">
	<!-- Links -->
		<a class="item" href="forum.php">Home</a>
		<a class="item" href="create_topic.php">Create a topic</a>
		<a class="item" href="create_cat.php">Create a subject</a>

		<!-- Right hand side login/create account bar -->
		<div id="infoBar">
		
        <!-- <?php
		error_reporting(E_ALL & ~E_NOTICE);
		// if user is signed in
		if($_SESSION['user_id'])
		{
			// display message including users user_name. Include link to signout
			echo 'Welcome <b>' . htmlentities($_SESSION['user_forename']) . '</b>. <a class="item" href="signout.php">Sign out</a>';
		}
		else
		{
			// if user is not signed in then display signin link or create account
			echo '<a class="item" href="../login.php">Sign in</a> or <a class="item" href="../register.php">Create an account</a>';
		}
		?> -->
		</div>
	</div>
		<div id="mainContent">
