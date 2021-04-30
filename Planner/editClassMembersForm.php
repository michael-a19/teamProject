<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit class members list</title>
    <link href="editClassMembersStyle.css" rel="stylesheet" type="text/css">
    <script src="Person.js"></script>
    <script src="editClassMembersJS.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

$classID = filter_has_var(INPUT_GET, 'classID')
    ? $_GET['classID'] : null;

retrieveAllUsers();
?>


<div id="class-members-rows-wrapper">
    <form method="post" action="editClassMembers.php" onsubmit="selectAllRightList()">
        <div id="class-members-filters">
            <label> Yr group:
                <input id="year-group" type="number" name="yearGroup" min=7 max=13>
            </label>
            <label> User type:
                <select id="user-type" onchange="toggleYearGroup()">
                    <option value="1">Students</option>
                    <option value="2">Teachers</option>
                </select>
            </label>
            <input type="button" value="Apply filters" onclick="displayFilteredUsers()">
        </div>
        <div id="class-members-columns-wrapper">
            <div id="class-members-left-column">
                <select id="left-select-list" multiple>

                </select>
            </div>
            <div id="class-members-middle-column">
                <input type="button" id="selected-to-right-list" onclick="selectedToRightList()" value ="&#8594;"><br>
                <input type="button" id="selected-to-left-list" onclick="selectedToRemove()" value="&#8592;"><br>
                <input type="submit" id="update-members-list" value="Apply changes">
            </div>
            <div id="class-members-right-column">
                <select id="right-select-list" multiple name="classList[]">
                    <?php
                    displayClassMembersList($classID)
                    ?>
                </select>
                <?php
                echo "<input name='classID' value='".$classID."' hidden>";
                ?>
            </div>
        </div>
    </form>
</div>
</body>

<?php
function displayClassMembersList($classID)
{
    try
    {
        $dbConn = getConnection();

        // SQL query that gets the user list for a class
        $sqlQuery = " SELECT user_surname, user_forename, tp_users.user_id, IFNULL(user_year_group, 0) 'user_year_group', user_type_id
        FROM tp_users
        INNER JOIN tp_class_members
        ON tp_class_members.user_id = tp_users.user_id
        WHERE tp_class_members.class_id = :class_id
        ORDER BY user_surname
        ";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':class_id' => $classID));

        while ($rowObj = $stmt->fetchObject()) {
            echo "<option data-surname='" . $rowObj->user_surname .
                "' data-forename='" . $rowObj->user_forename .
                "' value='" . $rowObj->user_id .
                "' data-yearGroup='" . $rowObj->user_year_group .
                "' data-userType='" . $rowObj->user_type_id .
                "'> " . $rowObj->user_surname . ", " . $rowObj->user_forename . "</option>\n";

        }
    }
    catch (Exception $e)
    {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}

function retrieveAllUsers()
{
    try
    {
        $dbConn = getConnection();

        // SQL query that gets the entire user list
        $sqlQuery = " SELECT user_forename, user_surname, user_id, IFNULL(user_year_group, 0) 'user_year_group', user_type_id
        FROM tp_users
        ORDER BY user_surname
        ";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array());

        echo "<script type=\"text/javascript\">";

        while ($rowObj = $stmt->fetchObject()) {
            echo "userList.push(new Person('".$rowObj->user_surname."', '".$rowObj->user_forename."', 
            ".$rowObj->user_id.", ".$rowObj->user_year_group.", ".$rowObj->user_type_id."));\n";
        }
        echo "</script>";
    }
    catch (Exception $e)
    {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}
?>

