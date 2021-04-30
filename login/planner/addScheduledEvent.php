<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add event</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

// generic fields
$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
$startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : null;
$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : null;
$eventID = isset($_REQUEST['eventID']) ? $_REQUEST['eventID'] : null;
$eventType = isset($_REQUEST['eventType']) ? $_REQUEST['eventType'] : null;

// class specific field
$classID = null;
if (strcmp($eventType, "class") == 0)
{
    $classID = isset($_REQUEST['class']) ? $_REQUEST['class'] : null;
}

// meeting specific fields
$subject = null;
$meetingDesc = null;
if (strcmp($eventType, "meeting") == 0)
{
    $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
    $meetingDesc = isset($_REQUEST['meetingDesc']) ? $_REQUEST['meetingDesc'] : null;
}

$error = false;

if (strcmp($eventType, "class") == 0)
{
    try
    {
        $dbConn = getConnection();
        // SQL query that adds a scheduled class
        $sql = "INSERT INTO tp_scheduled_classes (scheduled_class_date, scheduled_class_start_time, scheduled_class_end_time, class_id)
        VALUES (:date, :startTime, :endTime, :classID)";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':date' => $date, ':startTime' => $startTime, ':endTime' => $endTime, ':classID' => $classID));
    }
    catch (Exception $e)
    {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        $error = true;
    }
}
if (strcmp($eventType, "meeting") == 0)
{
    try
    {
        $dbConn = getConnection();

        $dbConn->beginTransaction();

        // SQL query that adds a meeting
        $sql = "INSERT INTO tp_meeting (meeting_desc, subject_id)
        VALUES (:desc, :subject)";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':desc' => $meetingDesc, ':subject' => $subject));


        // SQL query that adds a scheduled meeting
        $sql = "INSERT INTO tp_scheduled_meetings (scheduled_meeting_date, scheduled_meeting_start_time, scheduled_meeting_end_time, meeting_id)
        VALUES (:date, :startTime, :endTime, LAST_INSERT_ID())";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':date' => $date, ':startTime' => $startTime, ':endTime' => $endTime));

        $dbConn->commit();
    }
    catch (Exception $e)
    {
        $dbConn->rollBack();
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        $error = true;
    }
}
if (!$error)
{
    header('Location: updateSuccessful.php');
}
?>
</body>
