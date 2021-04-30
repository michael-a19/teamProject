<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete class</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php

include("functions.php");

$classID = filter_has_var(INPUT_GET, 'classID')
    ? $_GET['classID'] : null;

$dbConn = getConnection();

$errors = false;

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
    $errors = true;
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
}

if (!$errors)
{
    header("location: updateSuccessful.php");
}
?>
</body>
