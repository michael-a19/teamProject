<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit class</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include_once("functions.php");
$classID = filter_has_var(INPUT_GET, 'classID')
    ? $_GET['classID'] : null;
?>
<form method="get" action="editClass.php">
    <?php
    $className = '';
    $subjectName = '';
    $subjectID = 0;
    try
    {
        $dbConn = getConnection();

        $sqlQuery = "SELECT class_desc, subject_name, tp_class.subject_id
		FROM tp_class
		INNER JOIN tp_subject
		ON tp_subject.subject_id = tp_class.subject_id
		WHERE class_id = :class_id
		";
        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':class_id' => $classID));

        while ($rowObj = $stmt->fetchObject()) {
            $className = $rowObj->class_desc;
            $subjectName = $rowObj->subject_name;
            $subjectID = $rowObj->subject_id;
        }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
    ?>
    <label>Name:
        <?php
            echo "<input type=\"text\" name=\"name\" value=\"".$className."\">";
        ?>
    </label>
    <label>
        <select name="subject">
            <?php
            displaySubjectSelectOptions($subjectID);
            ?>
        </select>
    </label>
    <?php
    echo "<input type=\"hidden\" name=\"classID\" value=\"".$classID."\">";
    ?>
    <input type="submit" value="Apply changes">
</form>
<br>
<?php
echo "<a href=\"editClassMembers.php?classID=".$classID."\">Edit members list</a>";
?>

</body>
