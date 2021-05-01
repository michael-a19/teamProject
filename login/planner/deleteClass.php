<?php
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();
$classID = filter_has_var(INPUT_GET, 'classID')
    ? $_GET['classID'] : null;

include 'functions.php';

$dbConn = getConnection();

$errors = [];

try
{
    $dbConn->beginTransaction();

    $sqlQuery = "DELETE FROM tp_class_members WHERE class_id = :classID";

    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':classID' => $classID));

    $sqlQuery = "DELETE FROM tp_scheduled_classes WHERE class_id = :classID";

    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':classID' => $classID));

    $sqlQuery = "DELETE FROM tp_class WHERE class_id = :classID";

    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':classID' => $classID));

    $dbConn->commit();
}
catch (Exception $e){
    $dbConn->rollBack();
    array_push($errors, 'Query failed: '.$e->getMessage());
}

if (sizeof($errors) == 0)
{
    header("location: updateSuccessful.php");
}
else{
    include("../includes/pagefunctions.inc.php");
    echo pageStart("Delete class", "style.css");
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
}
?>