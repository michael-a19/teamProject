<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit class members list</title>
    <link href="editClassMembersStyle.css" rel="stylesheet" type="text/css">
    <script src="Person.js"></script>
</head>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<?php
include("functions.php");

$classID = filter_has_var(INPUT_GET, 'classID')
    ? $_GET['classID'] : null;

?>
<script type="text/javascript">
    var userList = [];
</script>
<?php

retrieveAllUsers();
?>

<div id="class-members-wrapper">
    <div id="class-members-rows-wrapper">
        <div id="class-members-filters">
            <form method="get" action="editClassMembers.php">
                <label> Yr group:
                    <input type="number" name="yearGroup" min=7 max=13>
                </label>
                <label> User type:
                    <select>
                        <option value="1">Students</option>
                        <option value="2">Teachers</option>
                    </select>
                </label>
                <input type="button" value="Apply filters" onclick="displayFilteredUsers()">
            </form>
        </div>
        <div id="class-members-columns-wrapper">
            <div class="class-members-left-column">

            </div>
            <div class="class-members-middle-column">
                <button id="selected-to-right-list">&#8594;</button>
                <button id="selected-to-left-list">&#8592;</button>
                <button>Apply changes</button>
                <button>Discard changes</button>
            </div>
            <div class="class-members-right-column">
                <?php
                displayClassMembersList($classID)
                ?>
            </div>
        </div>
    </div>
</div>
</body>

<?php
function displayClassMembersList($classID)
{
    try
    {
        $dbConn = getConnection();

        // SQL query that gets the user list for a class
        $sqlQuery = " SELECT user_surname, user_forename
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
            echo "<div>".$rowObj->user_surname.", ".$rowObj->user_forename."</div>";
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
        $sqlQuery = " SELECT user_forename, user_surname, user_id, user_year_group, user_type_id
        FROM tp_users
        ORDER BY user_surname
        ";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array());

        echo "<script type=\"text/javascript\">";

        echo "var userList = [];\n";

        while ($rowObj = $stmt->fetchObject()) {
            echo "userList.push(new Person(".$rowObj->user_surname.", ".$rowObj->user_forename.", 
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

<script type="text/javascript">
    function displayFilteredUsers()
    {
        console.log("hello");
        for (let i = 0; i < userList.length; i++)
        {
            document.getElementById("class-members-left-column").innerHTML = "<div class='list-element'> " +
                userList[i].surname + ", " + userList[i].forename + "</div>";
        }
    }
</script>
