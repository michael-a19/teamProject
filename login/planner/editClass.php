<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit class</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
$classID = isset($_REQUEST['classID']) ? $_REQUEST['classID'] : null;

$error = false;

try {
    $dbConn = getConnection();

    try
    {
        // SQL query that updates a class
        $sql = "UPDATE tp_class SET class_desc = :name, subject_id = :subject
        WHERE class_id = :classID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':name' => $name, ':subject' => $subject, ':classID' => $classID));
    }
    catch (Exception $e)
    {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        $error = true;
    }
}
catch (Exception $e)
{
    echo "<p>Failed to connect to database: ".$e->getMessage()."</p>\n";
    $error = true;
}

if (!$error)
{
    header('Location: manageClasses.php');
}

?>
</body>
