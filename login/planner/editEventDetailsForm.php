<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit event details</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php

include("Event.php");
include("functions.php");

$eventID = filter_has_var(INPUT_GET, 'eventID')
    ? $_GET['eventID'] : null;
$eventType = filter_has_var(INPUT_GET, 'eventType')
    ? $_GET['eventType'] : null;

$event = null;
$dbConn = null;

try
{
    $dbConn = getConnection();
    $event = getEventDetails($dbConn, $eventID, $eventType);
}
catch (Exception $e)
{
    echo "<p>Query failed: " . $e->getMessage() . "</p>\n";
}

echo "<h2>Edit details for: ".$event->desc."</h2>";
?>

<form method="get" action="editEventDetails.php">
    <label> Date:
        <?php
        echo "<input type='date' name='date' value='".$event->date."'>\n";
        ?>
    </label>
    <label> Start time:
        <?php
        echo "<input type='time' name='startTime' value='".$event->start_time."'>\n";
        ?>
    </label>
    <label> End time:
        <?php
        echo "<input type='time' name='endTime' value='".$event->end_time."'>\n";
        ?>
    </label>
    <?php
    echo "<input type=\"hidden\" name=\"eventID\" value=\"".$eventID."\">\n";
    echo "<input type=\"hidden\" name=\"eventType\" value=\"".$eventType."\">\n";
    if (strcmp($eventType, "meeting") == 0) {
        // Extra meeting specific fields
        $meetingDesc = '';
        $subjectName = '';
        $subjectID = 0;
        try {
            $dbConn = getConnection();

            $sqlQuery = "SELECT meeting_desc, subject_name, tp_meeting.subject_id
		    FROM tp_meeting
		    INNER JOIN tp_subject
		    ON tp_subject.subject_id = tp_meeting.subject_id
		    WHERE meeting_id = :meeting_id
		    ";
            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sqlQuery);

            // Execute the query using PDO
            $stmt->execute(array(':meeting_id' => getClassOrMeetingID($dbConn, $eventID, $eventType)));

            while ($rowObj = $stmt->fetchObject()) {
                $meetingDesc = $rowObj->meeting_desc;
                $subjectName = $rowObj->subject_name;
                $subjectID = $rowObj->subject_id;
            }
        }
        catch (Exception $e)
        {
            echo "<p>Query failed: " . $e->getMessage() . "</p>\n";
        }

        echo "<label> Description:\n";

            echo "<input type=\"text\" name=\"meetingDesc\" value=\"" . $meetingDesc . "\">";

        echo "</label>\n";
        echo "<label> Subject:\n";
        echo "<select name='subject'>\n";
                displaySubjectSelectOptions($subjectID);
        echo "</select>\n";
        echo "</label>\n";
        echo "<input type=\"hidden\" name=\"meetingID\" value=\"" . getClassOrMeetingID($dbConn, $eventID, $eventType) . "\">";
    }
    ?>
    <input type="submit" value="Apply changes">
</form>
<br>
<?php
if (strcmp($eventType, "class") == 0)
{
    echo "<a href='editClassForm.php?classID=".getClassOrMeetingID($dbConn, $eventID, $eventType)."'>Edit class details</a>\n";
}
echo "<a href='deleteScheduledEvent.php?eventID=".$eventID."&eventType=".$eventType."'>Delete</a>\n";
?>

</body>
