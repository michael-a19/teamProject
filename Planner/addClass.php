<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add new class</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;

$error = false;

try {
    $dbConn = getConnection();

    try
    {
        $dbConn->beginTransaction();

        // SQL query that adds a new class
        $sql = "INSERT INTO tp_class (class_desc, subject_id)
    values (:name, :subject)";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':name' => $name, ':subject' => $subject));

        // SQL query that assigns the user to the class
        $sql = "INSERT INTO tp_class_members (user_id, class_id)
    values (:user_id, LAST_INSERT_ID())";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':user_id' => 1));

        $dbConn->commit();
    }
    catch (Exception $e)
    {
        $dbConn->rollBack();
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
