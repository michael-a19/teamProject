<?php
include("functions.php");
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();
?>
<?php

$errors = [];

$classID = filter_has_var(INPUT_GET, 'classID')
    ? $_GET['classID'] : null;

if (!sanitiseInt($classID)) array_push($errors, "Class ID invalid");

if (empty($errors))
{
    try
    {
        $dbConn = getConnection();

        $sqlQuery = "DELETE FROM tp_scheduled_classes WHERE class_id = :classID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':classID' => $classID));
    }
    catch (Exception $e){
        array_push($errors, 'Query failed: '.$e->getMessage());
    }

    if (empty($errors))
    {
        header("location: updateSuccessful.php");
    }
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Delete scheduled classes", "style.css");
echo createNav();
echo createBanner();
echo "<main>\n";
foreach ($errors as $error)
{
    echo "<p>".$error."</p>\n";
}
echo "<a href='manageClasses.php'>Back to class list</a>\n";
echo "</main>";
echo pageEnd();
?>
