<?php
include("../includes/functions.inc.php");
session_start();
checkLoggedIn();

include("functions.php");

$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;

$errors = [];

if (!sanitiseString($name)) array_push($errors, "Class name invalid");
if (!sanitiseInt($subject)) array_push($errors, "Subject invalid");

$dbConn = getConnection();

if (empty($errors))
{
    try
    {
        $dbConn->beginTransaction();

        // SQL query that adds a new class
        $sql = "INSERT INTO tp_class (class_desc, subject_id)
        values (:name, :subject)";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':name' => $name, ':subject' => $subject));

        // SQL query that assigns the user to the class
        $sql = "INSERT INTO tp_class_members (user_id, class_id)
        values (:user_id, LAST_INSERT_ID())";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sql);

        // Execute the query using PDO
        $stmt->execute(array(':user_id' => $_SESSION['user_id']));

        $dbConn->commit();
    }
    catch (Exception $e)
    {
        $dbConn->rollBack();
        array_push($errors, 'Query failed: '.$e->getMessage());
    }
}
if (empty($errors))
{
    header('Location: manageClasses.php');
}
include("../includes/pagefunctions.inc.php");
echo pageStart("Add class", "style.css");
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

