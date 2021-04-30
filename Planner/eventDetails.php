<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event details</title>
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

echo "<a href='editEventDetailsForm.php?eventID=".$eventID."&eventType=".$eventType."'>Edit details</a>";

try
{
    $dbConn = getConnection();
    $event = getEventDetails($dbConn, $eventID, $eventType);
    echo "<h2>".$event->desc."</h2>\n";
    echo "<div>".$event->date."</div>\n";
    echo "<div>".$event->start_time." - ".$event->end_time."</div>\n";
    echo "<div>".$event->subject."</div>\n";
    echo "<div>Teachers: </div>\n";
    displayStaff($dbConn, $eventID, $eventType);
}
catch (Exception $e)
{
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
}


?>
</body>
