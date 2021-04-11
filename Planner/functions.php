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

function getMonthEvents()
{

}

function getWeekEvents($dbConn, $weekStartingDate)
{
    $weekEvents = [];
    for ($i = 0; $i < 7; $i++)
    {
        $weekEvents[$i] = getDayEvents($dbConn, $weekStartingDate);
        $weekStartingDate = strtotime("+1 day", $weekStartingDate);
    }
    return $weekEvents;
}

function getDayEvents($dbConn, $date)
{
    // SQL query that retrieves the basic data for each event on the given day
    $sqlQuery = "SELECT event_desc, scheduled_event_start_time, scheduled_event_end_time, scheduled_event_date, subject_name, event_type, scheduled_event_id
		FROM tp_event
		INNER JOIN tp_scheduled_events
		ON tp_event.event_id = tp_scheduled_events.event_id
		INNER JOIN tp_subject
		ON tp_event.subject_id = tp_subject.subject_id
		WHERE tp_scheduled_events.scheduled_event_date = :date
		ORDER BY scheduled_event_start_time";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute(array(':date' => $date));

    // Store data retrieved in an array
    $dayEvents = array();
    $i = 0;

    while ($rowObj = $stmt->fetchObject()) {
        $dayEvents[$i] = new Event($rowObj->event_desc, $rowObj->scheduled_event_start_time, $rowObj->scheduled_event_end_time,
            $rowObj->scheduled_event_date, $rowObj->subject_name, $rowObj->event_type, $rowObj->scheduled_event_id);
        $i++;
    }
    return $dayEvents;
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

function displayDayEvents($date)
{
    try
    {
        $dbConn = getConnection();

        $events = getDayEvents($dbConn, $date);
        for ($i = 0; $i < sizeof($events); $i++)
            {
                $startTime = 60 * (int)date('H', strtotime($events[$i]->start_time)) + (int)date('i', strtotime($events[$i]->start_time));
                $endTime = 60 * (int)date('H', strtotime($events[$i]->end_time)) + (int)date('i', strtotime($events[$i]->end_time));
                $position = timeToPercentage($startTime);
                $height = timeLengthToHeight($startTime, $endTime);
                echo "<div class=\"event\" style=\"top: ".$position."%; height: ".$height."%;\">".$events[$i]->desc."</div>\n";
            }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}

function displayWeekEvents($startingDate)
{

}

function timeToPercentage($time)
{
    return $time / 1440 * 100;
}

function timeLengthToHeight($startTime, $endTime)
{
    return timeToPercentage($endTime - $startTime);
}

function getUpcomingDeadlines($dbConn)
{
    // SQL query that retrieves the basic data for each event on the given day
    $sqlQuery = "SELECT user_id, tp_deadlines.deadline_id, deadline_type, deadline_desc, subject_name, deadline_date, deadline_time
    FROM tp_deadlines INNER JOIN tp_subject ON tp_deadlines.subject_id = tp_subject.subject_id 
    INNER JOIN tp_student_deadlines ON tp_student_deadlines.deadline_id = tp_deadlines.deadline_id 
    WHERE user_id = '1'
    ORDER BY deadline_date";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query using PDO
    $stmt->execute();

    // Store data retrieved in an array
    $studentDeadlines = array();
    $i = 0;

    while ($rowObj = $stmt->fetchObject()) {
        $studentDeadlines[$i] = new Deadline($rowObj->deadline_id, $rowObj->deadline_type, $rowObj->deadline_desc,
            $rowObj->subject_name, $rowObj->deadline_date, $rowObj->deadline_time);
        $i++;
    }
    return $studentDeadlines;
}

function displayUpcomingDeadlines()
{
    try
    {
        $dbConn = getConnection();

        $deadlines = getUpcomingDeadlines($dbConn);
        for ($i = 0; $i < sizeof($deadlines); $i++)
        {
            echo "<p class=\"deadline\" >".$deadlines[$i]->desc." ".$deadlines[$i]->date." ".$deadlines[$i]->time."</p>\n";
        }
    }
    catch (Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
}
?>