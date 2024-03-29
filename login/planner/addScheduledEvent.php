<?php
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();

include("functions.php");

$errors = [];

// generic fields
$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
$startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : null;
$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : null;
$eventType = isset($_REQUEST['eventType']) ? $_REQUEST['eventType'] : null;

if (!sanitiseDate($date)) array_push($errors, "Date invalid");
if (!sanitiseTime($startTime)) array_push($errors, "Start time invalid");
if (!sanitiseTime($endTime)) array_push($errors, "End time invalid");
if ($endTime < $startTime) array_push($errors, "Start time must be before end time");
if (strcmp($eventType, 'class') != 0 && strcmp($eventType, 'meeting') != 0) array_push($errors, "Event type invalid");

// class specific field
$classID = null;
if (strcmp($eventType, "class") == 0) {
    $classID = isset($_REQUEST['class']) ? $_REQUEST['class'] : null;

    if (!sanitiseInt($classID)) array_push($errors, "Class ID invalid");
}

// meeting specific fields
$subject = null;
$meetingDesc = null;
if (strcmp($eventType, "meeting") == 0) {
    $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
    $meetingDesc = isset($_REQUEST['meetingDesc']) ? $_REQUEST['meetingDesc'] : null;

    if (!sanitiseInt($subject)) array_push($errors, "Subject invalid");
    if (!sanitiseString($meetingDesc)) array_push($errors, "Meeting description invalid");
}

if (empty($errors)) {
    if (strcmp($eventType, "class") == 0) {
        try {
            $dbConn = getConnection();
            // SQL query that adds a scheduled class
            $sql = "INSERT INTO tp_scheduled_classes (scheduled_class_date, scheduled_class_start_time, scheduled_class_end_time, class_id)
            VALUES (:date, :startTime, :endTime, :classID)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':date' => $date, ':startTime' => $startTime, ':endTime' => $endTime, ':classID' => $classID));
        } catch (Exception $e) {
            array_push($errors, 'Query failed: ' . $e->getMessage());
        }
    }
    if (strcmp($eventType, "meeting") == 0) {
        try {
            $dbConn = getConnection();

            $dbConn->beginTransaction();

            // SQL query that adds a meeting
            $sql = "INSERT INTO tp_meeting (meeting_desc, subject_id)
            VALUES (:desc, :subject)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':desc' => $meetingDesc, ':subject' => $subject));

            $meetingID = $dbConn->lastInsertId();

            // SQL query that adds a scheduled meeting
            $sql = "INSERT INTO tp_scheduled_meetings (scheduled_meeting_date, scheduled_meeting_start_time, scheduled_meeting_end_time, meeting_id)
            VALUES (:date, :startTime, :endTime, :meetingID)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':date' => $date, ':startTime' => $startTime, ':endTime' => $endTime, ':meetingID' => $meetingID));

            // SQL query that assigns the user to the class
            $sql = "INSERT INTO tp_meeting_members (user_id, meeting_id)
            values (:user_id, :meetingID)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':user_id' => $_SESSION['user_id'], ':meetingID' => $meetingID));

            $dbConn->commit();
        } catch (Exception $e) {
            $dbConn->rollBack();
            array_push($errors, 'Query failed: ' . $e->getMessage());
        }
    }
    if (empty($errors)) {
        header('Location: updateSuccessful.php');
    }
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Add scheduled event", "style.css");
echo createNav();
echo createBanner();
echo "<main>\n";
foreach ($errors as $error) {
    echo "<p>" . $error . "</p>\n";
}
echo "<a href='plannerWeek.php'>Back to planner</a>\n";
echo "</main>";
echo pageEnd();
?>