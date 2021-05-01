<?php
session_start();
include("../includes/pagefunctions.inc.php");
echo pageStart("My classes", "style.css");
echo createNav();
echo createBanner();
include("../includes/functions.inc.php");
checkLoggedIn();
include("functions.php");
?>
<main>
    <h2>My classes</h2>
    <a href="addClassForm.php">+ Add new class</a>
    <?php


    try
    {
        $dbConn = getConnection();
        displayClasses($dbConn, $_SESSION['user_id']);
    }
    catch (Exception $e)
    {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }

    function displayClasses($dbConn, $userID)
    {
        // SQL query that retrieves the user's classes
        $sqlQuery = "SELECT class_desc, subject_name, tp_class.class_id
		FROM tp_class
		INNER JOIN tp_subject
		ON tp_class.subject_id = tp_subject.subject_id
		INNER JOIN tp_class_members
		ON tp_class_members.class_id = tp_class.class_id
		WHERE tp_class_members.user_id = :userID
		ORDER BY subject_name";
        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':userID' => $userID));


        $i = 0;
        while ($rowObj = $stmt->fetchObject()) {
            echo "<div>".$rowObj->class_desc."&#9;".$rowObj->subject_name."&#9;
        <a href='editClassForm.php?classID=".$rowObj->class_id."'>Edit</a>\n
        </div>\n";
            $i++;
        }
    }
    ?>
</main>
<?php
echo pageEnd();
?>
