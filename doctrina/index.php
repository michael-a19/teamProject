<?php
    /* this script creates the home page for the website */
    session_start(); 
    include_once('./includes/pagefunctions.inc.php');
    include_once('./includes/functions.inc.php');
    
    checkLoggedIn();
    echo pageStart('Home');
   
    echo createNav();
    echo createBanner();
    
   
?>
<main>
    <h2>Welcome <?php echo $_SESSION['user_forename'] . " " .  $_SESSION['user_surname'];?></h2>

</main>

<?php

    echo pageEnd();

?>