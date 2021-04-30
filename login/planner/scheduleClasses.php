<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule classes</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

$day = isset($_REQUEST['day']) ? $_REQUEST['day'] : null;
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : null;
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : null;
$startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : null;
$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : null;
$repeat = isset($_REQUEST['repeat']) ? $_REQUEST['repeat'] : null;
$classID = isset($_REQUEST['classID']) ? $_REQUEST['classID'] : null;

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
if ($repeat == 2)
{
    $dayGap = 14;
}

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
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    $error = true;
}
if (!$error)
{
    header('Location: updateSuccessful.php');
}
?>
</body>