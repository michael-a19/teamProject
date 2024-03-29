<?php
include("functions.php");
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();

$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
$classID = isset($_REQUEST['classID']) ? $_REQUEST['classID'] : null;

$errors = [];

if (!sanitiseString($name)) array_push($errors, "Class name invalid");
if (!sanitiseInt($subject)) array_push($errors, "Subject invalid");
if (!sanitiseInt($classID)) array_push($errors, "Class ID invalid");

if (empty($errors))
{
    try {
        $dbConn = getConnection();

        // SQL query that updates a class
        $sql = "UPDATE tp_class SET class_desc = :name, subject_id = :subject
        WHERE class_id = :classID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':name' => $name, ':subject' => $subject, ':classID' => $classID));
    } catch (Exception $e) {
        array_push($errors, 'Query failed: ' . $e->getMessage());
    }

    if (empty($errors)) {
        header('Location: manageClasses.php');
    }
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Edit class", "style.css");
echo createNav();
echo createBanner();
echo "<main>\n";
foreach ($errors as $error) {
    echo "<p>" . $error . "</p>\n";
}
echo "<a href='manageClasses.php'>Back to class list</a>\n";
echo "</main>";
echo pageEnd();

?>