<?php

    session_start(); 
    include_once('./includes/pagefunctions.inc.php');
    include_once('./includes/functions.inc.php');
    
    checkLoggedIn();
    //create page start
    echo pageStart('Home',""); ///////////////////change
   
    echo createNav();
    echo createBanner();
    
   
?>
<main>
    

</main>
<!-- <div id="home-schedule"> -->
<!-- add the schedule here -->

<!-- </div> -->

        <!-- </main> -->

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