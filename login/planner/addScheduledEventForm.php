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

$eventType = filter_has_var(INPUT_GET, 'eventType')
    ? $_GET['eventType'] : null;
$date = filter_has_var(INPUT_GET, 'date')
    ? $_GET['date'] : date("Y-m-d");
//$date = date("Y-m-d", $date);
?>

<form method="get" action="addScheduledEvent.php">
    <?php
    if (strcmp($eventType, "class") == 0)
    {
        echo "<label> Class:\n";
        echo "<select name='class'>\n";
        displayClassSelectOptions();
        echo "</label>\n";
        echo "</select>\n";
    }
    ?>
    <label> Date:
        <?php
        echo "<input type='date' name='date' value='".$date."' required>\n";
        ?>
    </label>
    <label> Start time:
        <?php
        echo "<input type='time' name='startTime' required>\n";
        ?>
    </label>
    <label> End time:
        <?php
        echo "<input type='time' name='endTime' required>\n";
        ?>
    </label>
    <?php
    echo "<input type=\"hidden\" name=\"eventType\" value=\"".$eventType."\">\n";
    if (strcmp($eventType, "meeting") == 0)
    {
        // Extra meeting specific fields
        echo "<label> Description:\n";
        echo "<input type='text' name='meetingDesc' required>";
        echo "</label>\n";
        echo "<label> Subject:\n";
        echo "<select name='subject'>\n";
        displaySubjectSelectOptions(null);
        echo "</select>\n";
        echo "</label>\n";
    }
    echo "<input type='submit' value='Add new ".$eventType."'>\n";
    ?>
</form>
</body>
