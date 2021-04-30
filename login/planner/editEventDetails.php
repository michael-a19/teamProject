<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit event details</title>
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

// meeting specific fields
$subject = null;
$meetingDesc = null;
$meetingID = null;
if (strcmp($eventType, "meeting") == 0)
{
    $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
    $meetingDesc = isset($_REQUEST['meetingDesc']) ? $_REQUEST['meetingDesc'] : null;
    $meetingID = isset($_REQUEST['meetingID']) ? $_REQUEST['meetingID'] : null;
}

$error = false;

if (strcmp($eventType, "class") == 0)
{
    try
    {
        $dbConn = getConnection();
        // SQL query that updates a scheduled class
        $sql = "UPDATE tp_scheduled_classes
        SET scheduled_class_date = :date, scheduled_class_start_time = :startTime, 
        scheduled_class_end_time = :endTime
        WHERE scheduled_class_id = :eventID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':date' => $date, ':startTime' => $startTime, ':endTIme' => $endTime, ':eventID' => $eventID));
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

        // SQL query that updates a scheduled meeting
        $sql = "UPDATE tp_scheduled_meetings
        SET scheduled_meeting_date = :date, scheduled_meeting_start_time = :startTime, 
        scheduled_meeting_end_time = :endTime
        WHERE scheduled_meeting_id = :eventID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':date' => $date, ':startTime' => $startTime, ':endTime' => $endTime, ':eventID' => $eventID));

        // SQL query that updates a meeting
        $sql = "UPDATE tp_meeting SET meeting_desc = :desc, subject_id = :subject
        WHERE meeting_id = :meetingID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':desc' => $name, ':subject' => $subject, ':meetingID' => $classID));

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