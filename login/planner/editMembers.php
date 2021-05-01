<?php
include("functions.php");
include("../includes/functions.inc.php");
checkLoggedIn();
$userIDList = [];

$classID = isset($_REQUEST['classID']) ? $_REQUEST['classID'] : null;
$meetingID = isset($_REQUEST['meetingID']) ? $_REQUEST['meetingID'] : null;

$type = '';
if (isset($classID))
{
    $type = 'class';
}
if (isset($meetingID))
{
    $type = 'meeting';
}

$errors = [];

// Check if any option is selected
if(isset($_POST["groupMembers"]))
{
    // Retrieving each selected option
    foreach ($_POST['groupMembers'] as $selectedOption)
    {
        array_push($userIDList, $selectedOption);
    }
}
else {
    array_push($errors, 'No users selected');
}


$dbConn = getConnection();
if (strcmp($type, 'class') == 0)
{
    try
    {

        $dbConn->beginTransaction();

        // SQL query that deletes all members of a class
        $sql = "DELETE FROM tp_class_members
        WHERE class_id = :classID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':classID' => $classID));

        for ($i = 0; $i < sizeof($userIDList); $i++)
        {
            // SQL query that inserts a userID and classID pair
            $sql = "INSERT INTO tp_class_members (class_id, user_id)
            VALUES (:classID, :userID)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':classID' => $classID, ':userID' => $userIDList[$i]));
        }

        $dbConn->commit();
    }
    catch (Exception $e)
    {
        $dbConn->rollBack();
        array_push($errors, 'Query failed: '.$e->getMessage());
    }
}
if (strcmp($type, "meeting") == 0)
{
    try
    {
        $dbConn->beginTransaction();

        // SQL query that deletes all members of a class
        $sql = "DELETE FROM tp_meeting_members
        WHERE meeting_id = :meetingID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':classID' => $classID));

        for ($i = 0; $i < sizeof($userIDList); $i++)
        {
            // SQL query that inserts a userID and meetingID pair
            $sql = "INSERT INTO tp_meeting_members (meeting_id, user_id)
            VALUES (:meetingID, :userID)";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sql);

            // Execute the query using PDO
            $stmt->execute(array(':meetingID' => $meetingID, ':userID' => $userIDList[$i]));
        }

        $dbConn->commit();
    }
    catch (Exception $e)
    {
        $dbConn->rollBack();
        array_push($errors, 'Query failed: '.$e->getMessage());
    }
}


if (sizeof($errors) == 0)
{
    if (strcmp($type, "class") == 0)
    {
        header('Location: editMembersForm.php?classID=' . $classID);
    }
    if (strcmp($type, "meeting") == 0)
    {
        header('Location: editMembersForm.php?meetingID=' . $meetingID);
    }
}
else {
    include("../includes/pagefunctions.inc.php");
    echo pageStart("Edit event details", "style.css");
    echo createNav();
    echo createBanner();
    echo "<main>\n";
    foreach ($errors as $error)
    {
        echo "<p>".$error."</p>\n";
    }
    if (strcmp($type, "class") == 0)
    {
        echo "<a href='editMembersForm.php?classID='".$classID.">Back to members list</a>\n";
    }
    if (strcmp($type, "meeting") == 0)
    {
        echo "<a href='editMembersForm.php?meetingID='".$meetingID.">Back to members list</a>\n";
    }
    echo "</main>\n";
    echo pageEnd();
}
?>

