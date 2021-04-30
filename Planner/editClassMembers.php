<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit class members</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

$userIDList = [];

$classID = isset($_REQUEST['classID']) ? $_REQUEST['classID'] : null;

$error = false;

// Check if any option is selected
if(isset($_POST["classList"]))
{
    // Retrieving each selected option
    foreach ($_POST['classList'] as $selectedOption)
    {
        array_push($userIDList, $selectedOption);
    }
}
else
{
    echo "<p>No students selected</p>";
    $error = true;
}


$dbConn = getConnection();
try
{
    $dbConn->beginTransaction();

    // SQL query that deletes all members of a class
    $sql = "DELETE FROM tp_class_members
    WHERE class_id = :classID";

    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sql);

    // Execute the query using PDO
    $stmt->execute(array(':classID' => $classID));

    for ($i = 0; $i < sizeof($userIDList); $i++)
    {
        // SQL query that inserts a userID and classID pair
        $sql = "INSERT INTO tp_class_members (class_id, user_id)
        VALUES (:classID, :userID)";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':classID' => $classID, ':userID' => $userIDList[$i]));
    }

    $dbConn->commit();
}
catch (Exception $e)
{
    $dbConn->rollBack();
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    $error = true;
}


if (!$error)
{
    header('Location: editClassMembersForm.php?classID=' . $classID);
}

?>
</body>
