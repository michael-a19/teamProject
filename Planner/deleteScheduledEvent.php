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

$eventID = filter_has_var(INPUT_GET, 'eventID')
    ? $_GET['eventID'] : null;
$eventType = filter_has_var(INPUT_GET, 'eventType')
    ? $_GET['eventType'] : null;

$dbConn = getConnection();

$errors = false;

try
{
    if (strcmp($eventType, "class") == 0)
    {
        $sqlQuery = "DELETE FROM tp_scheduled_classes WHERE scheduled_class_id = :eventID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':eventID' => $eventID));
    }
    if (strcmp($eventType, "meeting") == 0)
    {
        $dbConn->beginTransaction();

        $meetingID = getClassOrMeetingID($dbConn, $eventID, 'meeting');

        $sqlQuery = "DELETE FROM tp_scheduled_meetings WHERE scheduled_meeting_id = :eventID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':eventID' => $eventID));

        $sqlQuery = "DELETE FROM tp_meeting
        WHERE meeting_id = :meetingID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':meetingID' => $meetingID));

        $dbConn->commit();
    }

}
catch (Exception $e){
    $errors = true;
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
}

if (!$errors)
{
    header("location: updateSuccessful.php?eventID=".$eventID."&eventType=".$eventType."&meetingID=".$meetingID);
}
?>
</body>
