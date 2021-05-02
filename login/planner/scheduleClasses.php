<?php
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();
include("functions.php");


$day = isset($_REQUEST['day']) ? $_REQUEST['day'] : null;
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : null;
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : null;
$startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : null;
$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : null;
$repeat = isset($_REQUEST['repeat']) ? $_REQUEST['repeat'] : null;
$classID = isset($_REQUEST['classID']) ? $_REQUEST['classID'] : null;

$errors = [];

if (!sanitiseDate($startDate)) array_push($errors, "Start date invalid");
if (!sanitiseDate($endDate)) array_push($errors, "End date invalid");
if ($endDate < $startDate) array_push($errors, "Start date must be before end date");
if (!sanitiseTime($startTime)) array_push($errors, "Start time invalid");
if (!sanitiseTime($endTime)) array_push($errors, "End time invalid");
if ($endTime < $startTime) array_push($errors, "Start time must be before end time");
if (!sanitiseInt($classID)) array_push($errors, "Class ID invalid");

if (empty($errors))
{
    $unixDate = strtotime($startDate);
    $unixEndDate = strtotime($endDate);
    $dayOfWeekNum = date('N', $unixDate) - 1;
    $dayDif = $day - $dayOfWeekNum;

    if ($dayDif != 0)
    {
        if ($dayDif < 0)
        {
            $dayDif += 7;
        }
        $unixDate = strtotime("+".$dayDif." days", $unixDate);
    }

    $dayGap = 0;
    if ($repeat == 1)
    {
        $dayGap = 7;
    }
    else if ($repeat == 2)
    {
        $dayGap = 14;
    }
    else
    {
        array_push($errors, "Repeat value invalid");
    }
}

if (empty($errors))
{
    try
    {
        $dbConn = getConnection();

        $dbConn->beginTransaction();

        while ($unixDate <= $unixEndDate)
        {
            // SQL query that adds a scheduled class
            $sql = "INSERT INTO tp_scheduled_classes (scheduled_class_date, scheduled_class_start_time, scheduled_class_end_time, class_id)
        VALUES (:date, :startTime, :endTime, :classID)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':date' => date("Y-m-d",$unixDate), ':startTime' => $startTime, ':endTime' => $endTime, ':classID' => $classID));

            $unixDate = strtotime("+ ".$dayGap." days", $unixDate);
        }
        $dbConn->commit();
    }
    catch (Exception $e)
    {
        $dbConn->rollBack();
        array_push($errors, 'Query failed: '.$e->getMessage());
    }
    if (empty($errors))
    {
        header('Location: updateSuccessful.php');
    }
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Schedule classes", "style.css");
echo createNav();
echo createBanner();
echo "<main>\n";
foreach ($errors as $error)
{
    echo "<p>".$error."</p>\n";
}
echo "<a href='manageClasses.php'>Back to class list</a>\n";
echo "</main>";
echo pageEnd();

?>