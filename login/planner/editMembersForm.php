<?php
session_start();
include("../includes/pagefunctions.inc.php");
echo pageStart("Edit members list", "editMembersStyle.css", "Person.js");
echo createNav();
echo createBanner();
include("../includes/functions.inc.php");
checkLoggedIn();
include("functions.php");

?>
<main>
    <script type="text/javascript">
        var userList = [];

        function displayFilteredUsers()
        {
            var yearGroup = document.getElementById('year-group').value;
            var userTypeID = document.getElementById('user-type').value;
            var html = '';
            for (let i = 0; i < userList.length; i++)
            {
                console.log("userTypeID: " + userTypeID + " yearGroup: " + yearGroup);
                if ((userTypeID == 2 && userList[i].userType == 2) || (userTypeID == 1 && userTypeID == userList[i].userType && yearGroup == userList[i].yearGroup))
                {
                    html += "<option data-surname='" + userList[i].surname +
                        "' data-forename='" + userList[i].forename +
                        "' value='" + userList[i].userID +
                        "' data-yearGroup='" + userList[i].yearGroup +
                        "' data-userType='" + userList[i].userType +
                        "'> " + userList[i].surname + ", " + userList[i].forename + "</option>\n";
                }
            }
            document.getElementById("left-select-list").innerHTML = html;
        }

        function selectedToRightList()
        {
            var leftSelectedUsers = [];
            var options = document.getElementById('left-select-list').options;
            var rightOptions = document.getElementById('right-select-list').options;
            for (let i = 0; i < options.length; i++)
            {
                if (options[i].selected)
                {
                    var alreadyInRightlist = false;
                    for (let j = 0; j < rightOptions.length; j++)
                    {
                        if (options[i].getAttribute('value') == rightOptions[j].getAttribute('value'))
                        {
                            alreadyInRightlist = true;
                        }
                    }
                    if (!alreadyInRightlist)
                    {
                        leftSelectedUsers.push(new Person(options[i].getAttribute('data-surname'),
                            options[i].getAttribute('data-forename'),
                            options[i].getAttribute('value'),
                            options[i].getAttribute('data-yeargroup'),
                            options[i].getAttribute('data-usertype')));
                    }
                }
            }
            var rightList = document.getElementById("right-select-list").options;
            for (let i = 0; i < leftSelectedUsers.length; i++)
            {
                var option = new Option(leftSelectedUsers[i].surname + ", " + leftSelectedUsers[i].forename);
                option.setAttribute('data-surname', leftSelectedUsers[i].surname);
                option.setAttribute('data-forename', leftSelectedUsers[i].forename);
                option.setAttribute('value', leftSelectedUsers[i].userID);
                option.setAttribute('data-yeargroup', leftSelectedUsers[i].yearGroup);
                option.setAttribute('data-usertype', leftSelectedUsers[i].userType);
                rightList.add(option);
            }
        }

        function selectedToRemove()
        {
            var options = document.getElementById('right-select-list').options;
            for (let i = options.length - 1; i >= 0; i--)
            {
                if (options[i].selected)
                {
                    options.remove(i);
                }
            }
        }

        function toggleYearGroup()
        {
            if (document.getElementById('user-type').value == 2)
            {
                document.getElementById('year-group').disabled = true;
            }
            else
            {
                document.getElementById('year-group').disabled = false;
            }
        }

        function selectAllRightList()
        {
            var options = document.getElementById('right-select-list').options;
            for (let i = 0; i < options.length; i++)
            {
                options[i].selected = true;
            }
        }
    </script>
    <a href="manageClasses.php">&#8592; Class list</a>
    <?php
    $classID = filter_has_var(INPUT_GET, 'classID')
        ? $_GET['classID'] : null;

    $meetingID = filter_has_var(INPUT_GET, 'meetingID')
        ? $_GET['meetingID'] : null;

    $type = '';
    if (isset($meetingID))
    {
        $type = 'meeting';
    }
    if (isset($classID))
    {
        $type = 'class';
    }

    retrieveAllUsers();
    ?>


    <div id="class-members-rows-wrapper">
        <form method="post" action="editMembers.php" onsubmit="selectAllRightList()">
            <div id="class-members-filters">
                <label> Yr group:
                    <input id="year-group" type="number" name="yearGroup" min=7 max=13 value="7">
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
                    <select id="right-select-list" multiple name="groupMembers[]">
                        <?php
                        if (strcmp($type, "class") == 0)
                        {
                            displayMembersList($classID, $type);
                        }
                        if (strcmp($type, "meeting") == 0)
                        {
                            displayMembersList($meetingID, $type);
                        }
                        ?>
                    </select>
                    <?php
                    if (strcmp($type, "class") == 0) {
                        echo "<input name='classID' value='" . $classID . "' hidden>";
                    }
                    if (strcmp($type, "meeting") == 0) {
                        echo "<input name='meetingID' value='" . $meetingID . "' hidden>";
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
    <?php
    function displayMembersList($classOrMeetingId, $type)
    {
        if (strcmp($type, "class") == 0)
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
                $stmt->execute(array(':class_id' => $classOrMeetingId));

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
        if (strcmp($type, "meeting") == 0)
        {
            try
            {
                $dbConn = getConnection();

                // SQL query that gets the user list for a class
                $sqlQuery = " SELECT user_surname, user_forename, tp_users.user_id, IFNULL(user_year_group, 0) 'user_year_group', user_type_id
                FROM tp_users
                INNER JOIN tp_meeting_members
                ON tp_meeting_members.user_id = tp_users.user_id
                WHERE tp_meeting_members.meeting_id = :meeting_id
                ORDER BY user_surname
                ";

                // Prepare the sql statement using PDO
                $stmt = $dbConn->prepare($sqlQuery);

                // Execute the query using PDO
                $stmt->execute(array(':meeting_id' => $classOrMeetingId));

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
</main>
<?php
echo pageEnd();
?>