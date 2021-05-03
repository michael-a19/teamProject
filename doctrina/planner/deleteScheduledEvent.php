<?php
include("functions.php");
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();
$eventID = filter_has_var(INPUT_GET, 'eventID')
    ? $_GET['eventID'] : null;
$eventType = filter_has_var(INPUT_GET, 'eventType')
    ? $_GET['eventType'] : null;


$errors = [];

if (!sanitiseInt($eventID)) array_push($errors, "Event ID invalid");
if (strcmp($eventType, 'class') != 0 && strcmp($eventType, 'meeting') != 0) array_push($errors, "Event type invalid");

if (empty($errors))
{
    try
    {
        $dbConn = getConnection();
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

            $sqlQuery = "DELETE FROM tp_meeting_members WHERE meeting_id = :meetingID";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sqlQuery);

            // Execute the query using PDO
            $stmt->execute(array(':meetingID' => $meetingID));

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
        array_push($errors, 'Query failed: '.$e->getMessage());
    }

    if (empty($errors))
    {
        header("location: updateSuccessful.php");
    }
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Delete scheduled event", "style.css");
echo createNav();
echo createBanner();
echo "<main>\n";
foreach ($errors as $error)
{
    echo "<p>".$error."</p>\n";
}
echo "<a href='plannerWeek.php'>Back to planner</a>\n";
echo "</main>";
echo pageEnd();

?>