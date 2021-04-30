<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Classes</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<h2>My classes</h2>
<a href="addClassForm.php">+ Add new class</a>
<?php
include("functions.php");

try
{
    $dbConn = getConnection();
    displayClasses($dbConn, 1);
}
catch (Exception $e)
{
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
}

function displayClasses($dbConn, $userID)
{
    // SQL query that retrieves the user's classes
    $sqlQuery = "SELECT class_desc, subject_name, tp_class.class_id
		FROM tp_class
		INNER JOIN tp_subject
		ON tp_class.subject_id = tp_subject.subject_id
		INNER JOIN tp_class_members
		ON tp_class_members.class_id = tp_class.class_id
		WHERE tp_class_members.user_id = :userID
		ORDER BY subject_name";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':userID' => $userID));


    $i = 0;
    while ($rowObj = $stmt->fetchObject()) {
        echo "<div>".$rowObj->class_desc."&#9;".$rowObj->subject_name."&#9;
        <a href='editClassForm.php?classID=".$rowObj->class_id."'>Edit</a>\n
        </div>\n";
        $i++;
    }
}
?>
</body>
