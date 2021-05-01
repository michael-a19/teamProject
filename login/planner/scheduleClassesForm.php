<?php
session_start();
include("../includes/pagefunctions.inc.php");
echo pageStart("Schedule classes", "style.css");
echo createNav();
echo createBanner();
include("functions.php");
include("../includes/functions.inc.php");
checkLoggedIn();
?>
<main>
    <a href="manageClasses.php">&#8592; Class list</a>
    <?php


    $classID = filter_has_var(INPUT_GET, 'classID')
        ? $_GET['classID'] : null;

    $classDesc = null;

    $dbConn = getConnection();

    $sqlQuery = "SELECT class_desc
		FROM tp_class
		WHERE class_id = :classID";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':classID' => $classID));

    while ($rowObj = $stmt->fetchObject())
    {
        $classDesc = $rowObj->class_desc;
    }
    echo "<h2>Schedule classes for: ".$classDesc."</h2>";
    ?>
    <form method="get" action="scheduleClasses.php">
        <label> Day:
            <select name="day">
                <option value="0">Monday</option>
                <option value="1">Tuesday</option>
                <option value="2">Wednesday</option>
                <option value="3">Thursday</option>
                <option value="4">Friday</option>
                <option value="5">Saturday</option>
                <option value="6">Sunday</option>
            </select>
        </label>
        <label> Start date:
            <input type='date' name='startDate' required>
        </label>
        <label> End date:
            <input type='date' name='endDate' required>
        </label>
        <br>
        <label> Start time:
            <input type='time' name='startTime' required>
        </label>
        <label> End time:
            <input type='time' name='endTime' required>
        </label>
        <br>
        <label> Repeat:
            <select name="repeat">
                <option value="1">Weekly</option>
                <option value="2">Every 2 weeks</option>
            </select>
        </label>
        <?php
        echo "<input type='hidden' name='classID' value='".$classID."'>\n";
        ?>
        <input type='submit' value='Schedule classes'>
    </form>
</main>
<?php
echo pageEnd();
?>