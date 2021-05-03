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

<?php
include("../includes/pagefunctions.inc.php");
echo createNav();
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

	</div>
		<div id="mainContent">
