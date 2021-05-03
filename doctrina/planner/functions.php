<?php
function getConnection()
{
    try {
        $connection = new PDO("mysql:host=localhost;dbname=unn_w18015084",
            "unn_w18015084", "45kstI9");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
    catch (Exception $e) {
        throw new Exception("Connection error ". $e->getMessage(), 0, $e);
    }
}

function getDayClasses($dbConn, $date, $userID)
{
    // SQL query that retrieves the basic data for each class on the given day
    $sqlQuery = "SELECT class_desc, scheduled_class_start_time, scheduled_class_end_time, scheduled_class_date, subject_name, scheduled_class_id
		FROM tp_class
		INNER JOIN tp_scheduled_classes
		ON tp_class.class_id = tp_scheduled_classes.class_id
		INNER JOIN tp_subject
		ON tp_class.subject_id = tp_subject.subject_id
		INNER JOIN tp_class_members
		ON tp_class_members.class_id = tp_scheduled_classes.class_id
		WHERE tp_scheduled_classes.scheduled_class_date = :date
	    AND tp_class_members.user_id = :userID
		ORDER BY scheduled_class_start_time";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':date' => $date, ':userID' => $userID));

    // Store data retrieved in an array of Event objects
    $dayClasses = array();
    $i = 0;
    while ($rowObj = $stmt->fetchObject()) {
        $dayClasses[$i] = new Event($rowObj->class_desc, $rowObj->scheduled_class_start_time, $rowObj->scheduled_class_end_time,
            $rowObj->scheduled_class_date, $rowObj->subject_name, "class", $rowObj->scheduled_class_id);
        $i++;
    }
    return $dayClasses;
}

function getDayMeetings($dbConn, $date, $userID)
{
    // SQL query that retrieves the basic data for each meeting on the given day
    $sqlQuery = "SELECT meeting_desc, scheduled_meeting_start_time, scheduled_meeting_end_time, scheduled_meeting_date, subject_name, scheduled_meeting_id
		FROM tp_meeting
		INNER JOIN tp_scheduled_meetings
		ON tp_meeting.meeting_id = tp_scheduled_meetings.meeting_id
		INNER JOIN tp_subject
		ON tp_meeting.subject_id = tp_subject.subject_id
		INNER JOIN tp_meeting_members
		ON tp_meeting_members.meeting_id = tp_scheduled_meetings.meeting_id
		WHERE tp_scheduled_meetings.scheduled_meeting_date = :date
		AND tp_meeting_members.user_id = :userID
		ORDER BY scheduled_meeting_start_time";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':date' => $date, ':userID' => $userID));

    // Store data retrieved in an array of Event objects
    $dayMeetings = array();
    $i = 0;
    while ($rowObj = $stmt->fetchObject()) {
        $dayMeetings[$i] = new Event($rowObj->meeting_desc, $rowObj->scheduled_meeting_start_time, $rowObj->scheduled_meeting_end_time,
            $rowObj->scheduled_meeting_date, $rowObj->subject_name, "meeting", $rowObj->scheduled_meeting_id);
        $i++;
    }
    return $dayMeetings;
}

function getClassOrMeetingID($dbConn, $eventID, $eventType)
{
    if (strcmp($eventType, "class") == 0)
    {
        // SQL query that retrieves the class ID for a scheduled class or meeting
        $sqlQuery = "SELECT tp_class.class_id
    	FROM tp_class
    	INNER JOIN tp_scheduled_classes
		ON tp_class.class_id = tp_scheduled_classes.class_id
        WHERE scheduled_class_id = :eventID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':eventID' => $eventID));

        // Return the class id
        while ($rowObj = $stmt->fetchObject())
        {
            return $rowObj->class_id;
        }
    }
    if (strcmp($eventType, "meeting") == 0)
    {
        // SQL query that retrieves the meeting ID for a scheduled class or meeting
        $sqlQuery = "SELECT tp_meeting.meeting_id
    	FROM tp_meeting
    	INNER JOIN tp_scheduled_meetings
		ON tp_meeting.meeting_id = tp_scheduled_meetings.meeting_id
        WHERE scheduled_meeting_id = :eventID";

        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute(array(':eventID' => $eventID));

        // Return the class id
        while ($rowObj = $stmt->fetchObject())
        {
            return $rowObj->meeting_id;
        }
    }
    return null;
}

function getEventDetails($dbConn, $eventID, $eventType)
{
    if (strcmp($eventType, "class") == 0)
    {
        try
        {
            // SQL query that retrieves the details of a scheduled class given its ID
            $sqlQuery = "SELECT class_desc, scheduled_class_start_time, scheduled_class_end_time, scheduled_class_date, subject_name, scheduled_class_id
		    FROM tp_class
		    INNER JOIN tp_scheduled_classes
		    ON tp_class.class_id = tp_scheduled_classes.class_id
		    INNER JOIN tp_subject
		    ON tp_class.subject_id = tp_subject.subject_id
            WHERE scheduled_class_id = :eventID";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sqlQuery);

            // Execute the query using PDO
            $stmt->execute(array(':eventID' => $eventID));

            // Store data retrieved in an Event object
            while ($rowObj = $stmt->fetchObject())
            {
                $event = new Event($rowObj->class_desc, $rowObj->scheduled_class_start_time, $rowObj->scheduled_class_end_time,
                    $rowObj->scheduled_class_date, $rowObj->subject_name, "class", $rowObj->scheduled_class_id);
                return $event;
            }
        }
        catch (Exception $e)
        {
            echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        }
    }

    if (strcmp($eventType, "meeting") == 0)
    {
        try
        {
            // SQL query that retrieves the details of a scheduled meeting given its ID
            $sqlQuery = "SELECT meeting_desc, scheduled_meeting_start_time, scheduled_meeting_end_time, scheduled_meeting_date, subject_name, scheduled_meeting_id
		    FROM tp_meeting
		    INNER JOIN tp_scheduled_meetings
		    ON tp_meeting.meeting_id = tp_scheduled_meetings.meeting_id
		    INNER JOIN tp_subject
		    ON tp_meeting.subject_id = tp_subject.subject_id
		    WHERE scheduled_meeting_id = :eventID";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sqlQuery);

            // Execute the query using PDO
            $stmt->execute(array(':eventID' => $eventID));

            // Store data retrieved in an Event object
            while ($rowObj = $stmt->fetchObject())
            {
                $event = new Event($rowObj->meeting_desc, $rowObj->scheduled_meeting_start_time, $rowObj->scheduled_meeting_end_time,
                    $rowObj->scheduled_meeting_date, $rowObj->subject_name, "meeting", $rowObj->scheduled_meeting_id);
                return $event;
            }
        }
        catch (Exception $e)
        {
            echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        }
    }
    return null;
}

function displayStaff($dbConn, $eventID, $eventType)
{
    if (strcmp($eventType, "class") == 0)
    {
        try
        {
            // SQL query that retrieves the teachers for a class
            $sqlQuery = "SELECT user_surname, user_forename
            FROM tp_users
            INNER JOIN tp_class_members
            ON tp_users.user_id = tp_class_members.user_id
            INNER JOIN tp_scheduled_classes
            ON tp_scheduled_classes.class_id = tp_class_members.class_id
            WHERE scheduled_class_id = :eventID
            AND user_type_id = 2";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sqlQuery);

            // Execute the query using PDO
            $stmt->execute(array(':eventID' => $eventID));

            // Echo retrieved teacher info
            while ($rowObj = $stmt->fetchObject())
            {
                echo "<div>".$rowObj->user_forename." ".$rowObj->user_surname."</div>";
            }
        }
        catch (Exception $e)
        {
            echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        }
    }

    if (strcmp($eventType, "meeting") == 0)
    {
        try
        {
            // SQL query that retrieves the teachers for a meeting
            $sqlQuery = "SELECT user_surname, user_forename
            FROM tp_users
            INNER JOIN tp_meeting_members
            ON tp_users.user_id = tp_meeting_members.user_id
            INNER JOIN tp_scheduled_meetings
            ON tp_scheduled_meetings.meeting_id = tp_meeting_members.meeting_id
            WHERE scheduled_meeting_id = :eventID
            AND user_type_id = 2";

            // Prepare the sql statement using PDO
            $stmt = $dbConn->prepare($sqlQuery);

            // Execute the query using PDO
            $stmt->execute(array(':eventID' => $eventID));

            // Echo retrieved teacher info
            while ($rowObj = $stmt->fetchObject())
            {
                echo "<div>".$rowObj->user_forename." ".$rowObj->user_surname."</div>";
            }
        }
        catch (Exception $e)
        {
            echo "<p>Query failed: ".$e->getMessage()."</p>\n";
        }
    }

}

function displayDayColumn()
{
    for ($i = 0; $i < 24; $i++)
    {
        echo "<div class=\"day-cell\"></div>";
    }
}

function displayDayHeader($date)
{
    echo "<div class=\"day-header\">".date("D", $date)."<br>".date("d", $date)."</div>";
}

function displayDayEvents($date, $userID)
{
    try
    {
        $dbConn = getConnection();

        $events = array_merge(getDayClasses($dbConn, $date, $userID), getDayMeetings($dbConn, $date, $userID));

        // Display each event using its length in time to determine its height in the timetable
        for ($i = 0; $i < sizeof($events); $i++)
            {
                $startTime = 60 * (int)date('H', strtotime($events[$i]->start_time)) + (int)date('i', strtotime($events[$i]->start_time));
                $endTime = 60 * (int)date('H', strtotime($events[$i]->end_time)) + (int)date('i', strtotime($events[$i]->end_time));
                $position = timeToPercentage($startTime);
                $height = timeLengthToHeight($startTime, $endTime);
                echo "<a href='eventDetails.php?eventID=".$events[$i]->scheduled_event_id."&eventType=".$events[$i]->event_type."' 
                class='event' style=\"top: ".$position."%; height: ".$height."%;\">".$events[$i]->desc."</a>\n";
            }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}

function timeToPercentage($time)
{
    return $time / 1440 * 100;
}

function timeLengthToHeight($startTime, $endTime)
{
    return timeToPercentage($endTime - $startTime);
}

function getUpcomingDeadlines($dbConn, $userID)
{
    // SQL query that retrieves the basic data for a user's associated deadlines
    $sqlQuery = "SELECT user_id, tp_deadlines.deadline_id, deadline_type, deadline_desc, subject_name, deadline_date, deadline_time
    FROM tp_deadlines INNER JOIN tp_subject ON tp_deadlines.subject_id = tp_subject.subject_id 
    INNER JOIN tp_student_deadlines ON tp_student_deadlines.deadline_id = tp_deadlines.deadline_id 
    WHERE user_id = :userID
    ORDER BY deadline_date";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':userID' => $userID));

    // Store data retrieved in a Deadline object
    $studentDeadlines = array();
    $i = 0;
    while ($rowObj = $stmt->fetchObject()) {
        $studentDeadlines[$i] = new Deadline($rowObj->deadline_id, $rowObj->deadline_type, $rowObj->deadline_desc,
            $rowObj->subject_name, $rowObj->deadline_date, $rowObj->deadline_time);
        $i++;
    }
    return $studentDeadlines;
}

function displayUpcomingDeadlines($userID)
{
    try
    {
        $dbConn = getConnection();

        $deadlines = getUpcomingDeadlines($dbConn, $userID);

        // Display each deadline retrieved
        for ($i = 0; $i < sizeof($deadlines); $i++)
        {
            echo "<p class=\"deadline\" >".$deadlines[$i]->desc." ".date("d M, Y", strtotime($deadlines[$i]->date))." 
            ".date("H:i",  strtotime($deadlines[$i]->time))."</p>\n";
        }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}

function displaySubjectSelectOptions($default)
{
    try
    {
        $dbConn = getConnection();

        // SQL query that retrieves all subject names and ids
        $sqlQuery = "SELECT subject_name, subject_id
		FROM tp_subject
		ORDER BY subject_name";
        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute();

        // Echo the option values for a select tag for the subject
        while ($rowObj = $stmt->fetchObject()) {
            if ($default == $rowObj->subject_id)
            {
                echo "<option value='".$rowObj->subject_id."' selected='selected'>".$rowObj->subject_name." </option>\n";
            }
            else {
                echo "<option value='".$rowObj->subject_id."'>".$rowObj->subject_name."</option>\n";
            }
        }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}

function displayClassSelectOptions()
{
    try
    {
        $dbConn = getConnection();

        // SQL query that retrieves the class descriptions and ids
        $sqlQuery = "SELECT class_desc, class_id
		FROM tp_class
		ORDER BY class_desc";
        // Prepare the sql statement using PDO
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query using PDO
        $stmt->execute();

        // Echo the option values for a select tag for the class
        while ($rowObj = $stmt->fetchObject())
        {
            echo "<option value='".$rowObj->class_id."'>".$rowObj->class_desc."</option>\n";
        }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}

function sanitiseString(&$var)
{
    if (!empty($var) && strlen($var) < 50)
    {
        $var = trim($var);
        $tempVar = filter_var($var, FILTER_SANITIZE_STRING);
        if (strcmp($var, $tempVar) != 0) return false;
        return true;
    }
    return false;
}

function sanitiseInt(&$var)
{
    if (!empty($var) && strlen($var) < 50 && filter_var($var, FILTER_VALIDATE_INT))
    {
        $var = trim($var);
        return true;
    }
    return false;
}

function sanitiseDate(&$var)
{
    $var = trim($var);
    return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $var);
}

function sanitiseTime($var)
{
    $var = trim($var);
    return preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/", $var);
}
?>