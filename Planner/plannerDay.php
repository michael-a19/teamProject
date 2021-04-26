<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Planner</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<body>
<?php
require_once('functions.php');
include('Event.php');
include('Deadline.php');
$date = filter_has_var(INPUT_GET, 'date')
    ? $_GET['date'] : date("Y-m-d");
$unixDate = strtotime($date);

?>
<div class="planner-page">
    <div class="planner">
        <div class="planner-header">
            <div class="dropdown">
                <button onclick="toggleViewDropDown()" class="dropbtn">Day</button>
                <div id="view-btn" class="dropdown-content">
                    <?php
                    echo "<a href=\"plannerDay.php?date=".$date."\">Day</a>";
                    echo "<a href=\"plannerWeek.php?date=".$date."\">Week</a>";
                    echo "<a href=\"#\">Month</a>";
                    ?>
                </div>
            </div>
            <?php
            echo "<button onclick=\"changeDate('".date("Y-m-d", strtotime("-1 day", $unixDate))."')\" class=\"backWeek\">&#9664;</button>\n";
            echo "<h1>".date("l jS F Y", $unixDate)."</h1>\n";
            echo "<button onclick=\"changeDate('".date("Y-m-d", strtotime("+1 day", $unixDate))."')\" class=\"forwardWeek\">&#9654;</button>\n";
            ?>
            <div class="dropdown">
                <button onclick="toggleShowDropdown()" class="dropbtn">Dropdown</button>
                <div id="show-btn" class="dropdown-content">
                    <a href="#">Link 1</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a>
                </div>
            </div>
        </div>
        <div class="table">
            <div class="times-column">
                <div class="times-header">&nbsp;</div>
                <div class="times-cell">00:00</div>
                <div class="times-cell">01:00</div>
                <div class="times-cell">02:00</div>
                <div class="times-cell">03:00</div>
                <div class="times-cell">04:00</div>
                <div class="times-cell">05:00</div>
                <div class="times-cell">06:00</div>
                <div class="times-cell">07:00</div>
                <div class="times-cell">08:00</div>
                <div class="times-cell">09:00</div>
                <div class="times-cell">10:00</div>
                <div class="times-cell">11:00</div>
                <div class="times-cell">12:00</div>
                <div class="times-cell">13:00</div>
                <div class="times-cell">14:00</div>
                <div class="times-cell">15:00</div>
                <div class="times-cell">16:00</div>
                <div class="times-cell">17:00</div>
                <div class="times-cell">18:00</div>
                <div class="times-cell">19:00</div>
                <div class="times-cell">20:00</div>
                <div class="times-cell">21:00</div>
                <div class="times-cell">22:00</div>
                <div class="times-cell">23:00</div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($unixDate);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", $unixDate));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="upcoming-events">
        <h2>Upcoming dates</h2>
        <?php
        displayUpcomingDeadlines();
        ?>
    </div>
</div>
</body>
