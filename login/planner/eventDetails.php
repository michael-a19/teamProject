<?php
include("../includes/pagefunctions.inc.php");
echo pageStart("Event details", "style.css");
echo createNav();
echo createBanner();
include("Event.php");
include("functions.php");
include("../includes/functions.inc.php");
checkLoggedIn();
$isTeacher = false;
if(checkIfTeacher())
{
    $isTeacher = true;
}
?>
<main>
    <?php
    $eventID = filter_has_var(INPUT_GET, 'eventID')
        ? $_GET['eventID'] : null;
    $eventType = filter_has_var(INPUT_GET, 'eventType')
        ? $_GET['eventType'] : null;

    if ($isTeacher)
    {
        echo "<a href='editEventDetailsForm.php?eventID=".$eventID."&eventType=".$eventType."'>Edit details</a>";
    }

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
</main>
<?php
echo pageEnd();
?>
