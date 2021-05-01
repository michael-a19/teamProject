<?php
include("../includes/pagefunctions.inc.php");
echo pageStart("Update successful", "style.css");
echo createNav();
echo createBanner();
include("functions.php");
?>
<main>
    <a href="manageClasses.php">Class list</a>
    <p>Update successful</p>
</main>
<?php
echo pageEnd();
?>
