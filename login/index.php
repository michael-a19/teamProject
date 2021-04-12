<?php

    session_start(); 
    include_once('./includes/pagefunctions.inc.php');
    //if the logged in session variable doesn't not exist or if the variable is true user is not logged in
    if(!isset($_SESSION['loggedIn']) || !($_SESSION['loggedIn'])) 
    {
        header('location: login.php');
    }
    //print_r($_SESSION);
    //echo "page works";

    echo pageStart('Home', "./styles/style.css");
    //echo pageBanner(); move this banner into page banner and conditionally load the content, just keep logo if not logged in
    echo createNav();
echo createBanner();
echo "<main>";
    //main content for the page would be displayed here
?>



<?php
    echo "</main>"
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
<?php

    echo pageEnd();

?>