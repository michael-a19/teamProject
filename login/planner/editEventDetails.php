<?php
include("functions.php");
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();

// generic fields
$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
$startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : null;
$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : null;
$eventID = isset($_REQUEST['eventID']) ? $_REQUEST['eventID'] : null;
$eventType = isset($_REQUEST['eventType']) ? $_REQUEST['eventType'] : null;

$errors = [];

if (!sanitiseDate($date)) array_push($errors, "Date invalid");
if (!sanitiseTime($startTime)) array_push($errors, "Start time invalid");
if (!sanitiseTime($endTime)) array_push($errors, "End time invalid");
if ($endTime < $startTime) array_push($errors, "Start time must be before end time");
if (!sanitiseInt($eventID)) array_push($errors, "Event ID invalid");
if (strcmp($eventType, 'class') != 0 && strcmp($eventType, 'meeting') != 0) array_push($errors, "Event type invalid");

// meeting specific fields
$subject = null;
$meetingDesc = null;
$meetingID = null;
if (strcmp($eventType, "meeting") == 0)
{
    $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
    $meetingDesc = isset($_REQUEST['meetingDesc']) ? $_REQUEST['meetingDesc'] : null;
    $meetingID = isset($_REQUEST['meetingID']) ? $_REQUEST['meetingID'] : null;

    if (!sanitiseInt($subject)) array_push($errors, "Subject invalid");
    if (!sanitiseString($meetingDesc)) array_push($errors, "Meeting description invalid");
    if (!sanitiseInt($meetingID)) array_push($errors, "Meeting ID invalid");
}


if (empty($errors))
{
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
            array_push($errors, 'Query failed: '.$e->getMessage());
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
            array_push($errors, 'Query failed: '.$e->getMessage());
        }
    }
    if (empty($errors))
    {
        header('Location: updateSuccessful.php');
    }
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Edit event details", "style.css");
echo createNav();
echo createBanner();
echo "<main>\n";
foreach ($errors as $error)
{
    echo "<p>".$error."</p>\n";
}
echo "</main>\n";
echo pageEnd();
?>
