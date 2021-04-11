<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Planner</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
require_once('functions.php');
include('Event.php');
include('Deadline.php');
//$weekEvents = getWeekEvents();
$date = filter_has_var(INPUT_GET, 'date')
    ? $_GET['date'] : date("Y-m-d");
$unixDate = strtotime($date);
$dayOfWeekNum = date('N', $unixDate) - 1;
$monday = $date;
// If not monday
if ($dayOfWeekNum > 0)
{
    $monday = strtotime("-".$dayOfWeekNum." days", $unixDate);
}
else {
    $monday = $unixDate;
}

$days = [$monday];
for ($i = 1; $i < 7; $i++)
{
    $days[$i] = strtotime("+".$i." days", $monday);
}


?>
<div class="planner-page">
    <div class="planner">
        <div class="planner-header">
            <div class="dropdown">
                <button onclick="toggleViewDropDown()" class="dropbtn">Dropdown</button>
                <div id="view-btn" class="dropdown-content">
                    <a href="#">Link 1</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a>
                </div>
            </div>
            <?php
            echo "<button onclick=\"changeWeek('".date("Y-m-d", strtotime("-1 week", $days[0]))."')\" class=\"backWeek\">&#9664;</button>\n";
            echo "<h1>".date("D", $days[0])." ".date("d/m/Y", $days[0])." - ".date("D", $days[6])." ".date("d/m/Y", $days[6])."</h1>\n";
            echo "<button onclick=\"changeWeek('".date("Y-m-d", strtotime("+1 week", $days[0]))."')\" class=\"forwardWeek\">&#9654;</button>\n";
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
                displayDayHeader($days[0]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", $monday));
                    ?>
                </div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($days[1]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", strtotime("+1 day", $monday)));
                    ?>
                </div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($days[2]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", strtotime("+2 days", $monday)));
                    ?>
                </div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($days[3]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", strtotime("+3 days", $monday)));
                    ?>
                </div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($days[4]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", strtotime("+4 days", $monday)));
                    ?>
                </div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($days[5]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", strtotime("+5 days", $monday)));
                    ?>
                </div>
            </div>
            <div class="column">
                <?php
                displayDayHeader($days[6]);
                ?>
                <div class="day-wrapper">
                    <?php
                    displayDayColumn();
                    displayDayEvents(date("Y-m-d", strtotime("+6 days", $monday)));
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
<script>
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function toggleViewDropDown() {
        document.getElementById("view-btn").classList.toggle("show");
    }

    function toggleShowDropdown()
    {
        document.getElementById("show-btn").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    function changeWeek(date)
    {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set("date", date);
        window.location.search = searchParams.toString();
    }
</script>
</body>
