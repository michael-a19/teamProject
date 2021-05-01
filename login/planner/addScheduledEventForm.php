<?php
session_start();
include("../includes/pagefunctions.inc.php");
echo pageStart("Edit event details", "style.css");
echo createNav();
echo createBanner();

include("../includes/functions.inc.php");
checkLoggedIn();

include("Event.php");
include("functions.php");

$eventType = filter_has_var(INPUT_GET, 'eventType')
    ? $_GET['eventType'] : null;
$date = filter_has_var(INPUT_GET, 'date')
    ? $_GET['date'] : date("Y-m-d");
//$date = date("Y-m-d", $date);
?>
<main>
    <form method="get" action="addScheduledEvent.php">
        <?php
        if (strcmp($eventType, "class") == 0)
        {
            echo "<label> Class:\n";
            echo "<select name='class'>\n";
            displayClassSelectOptions();
            echo "</select>\n";
            echo "</label>\n";
        }
        ?>
        <br>
        <label> Date:
            <?php
            echo "<input type='date' name='date' value='".$date."' required>\n";
            ?>
        </label>
        <br>
        <label> Start time:
            <?php
            echo "<input type='time' name='startTime' required>\n";
            ?>
        </label>
        <br>
        <label> End time:
            <?php
            echo "<input type='time' name='endTime' required>\n";
            ?>
        </label>
        <br>
        <?php
        echo "<input type=\"hidden\" name=\"eventType\" value=\"".$eventType."\">\n";
        if (strcmp($eventType, "meeting") == 0)
        {
            // Extra meeting specific fields
            echo "<label> Description:\n";
            echo "<input type='text' name='meetingDesc' required>";
            echo "</label>\n";
            echo "<br>";
            echo "<label> Subject:\n";
            echo "<select name='subject'>\n";
            displaySubjectSelectOptions(null);
            echo "</select>\n";
            echo "</label>\n";
            echo "<br>";
        }
        echo "<input type='submit' value='Add new ".$eventType."'>\n";
        ?>
    </form>
</main>
<?php
echo pageEnd();
?>
