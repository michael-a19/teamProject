<?php
session_start();
include("../includes/pagefunctions.inc.php");
include("../includes/functions.inc.php");
echo pageStart("Add new class", "style.css");
echo createNav();
echo createBanner();
include("functions.php");
checkLoggedIn();
?>
<main>
    <a href="manageClasses.php">&#8592; Class list</a>
    <h2>
        Add new class
    </h2>
    <form action="addClass.php" method="get">
        <label> Name:
            <input type="text" name="name" required>
        </label>
        <br>
        <label> Subject:
            <select name="subject">
                <?php
                displaySubjectSelectOptions();
                ?>
            </select>
        </label>
        <br>
        <input type="submit" value="Create">
    </form>
</main>

<?php
echo pageEnd();
?>