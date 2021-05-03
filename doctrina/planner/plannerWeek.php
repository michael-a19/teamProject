<?php
session_start();
include("../includes/pagefunctions.inc.php");
echo pageStart("Planner", "style.css", "plannerJSFunctions.js");
echo createNav();
echo createBanner();
require_once('functions.php');
include('Event.php');
include('Deadline.php');
include("../includes/functions.inc.php");
checkLoggedIn();
$isTeacher = false;
if(checkIfTeacher())
{
    $isTeacher = true;
}

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
<main>
    <div class="planner-page">
        <div class="planner">
            <div class="planner-header">
                <div class="dropdown">
                    <button onclick="toggleViewDropDown()" class="dropbtn">Week</button>
                    <div id="view-btn" class="dropdown-content">
                        <?php
                        echo "<a href=\"plannerDay.php?date=".$date."\">Day</a>\n";
                        echo "<a href=\"plannerWeek.php?date=".$date."\">Week</a>\n";
                        ?>
                    </div>
                </div>
                <?php
                echo "<button onclick=\"changeDate('".date("Y-m-d", strtotime("-1 week", $days[0]))."')\" class=\"backWeek\">&#9664;</button>\n";
                echo "<h1>".date("D d/m/Y", $days[0])." - ".date("D d/m/Y", $days[6])."</h1>\n";
                echo "<button onclick=\"changeDate('".date("Y-m-d", strtotime("+1 week", $days[0]))."')\" class=\"forwardWeek\">&#9654;</button>\n";
                ?>
                <div class="add-event-btns">
                    <?php
                    if ($isTeacher)
                    {
                        echo "<a href='addScheduledEventForm.php?eventType=class&date=".$monday."'>Add scheduled class</a><br>\n";
                        echo "<a href='addScheduledEventForm.php?eventType=meeting&date=".$monday."'>Add scheduled meeting</a>\n";
                    }
                    ?>
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
                        displayDayEvents(date("Y-m-d", $monday), $_SESSION['user_id']);
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
                        displayDayEvents(date("Y-m-d", strtotime("+1 day", $monday)), $_SESSION['user_id']);
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
                        displayDayEvents(date("Y-m-d", strtotime("+2 days", $monday)), $_SESSION['user_id']);
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
                        displayDayEvents(date("Y-m-d", strtotime("+3 days", $monday)), $_SESSION['user_id']);
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
                        displayDayEvents(date("Y-m-d", strtotime("+4 days", $monday)), $_SESSION['user_id']);
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
                        displayDayEvents(date("Y-m-d", strtotime("+5 days", $monday)), $_SESSION['user_id']);
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
                        displayDayEvents(date("Y-m-d", strtotime("+6 days", $monday)), $_SESSION['user_id']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="upcoming-events">
            <h2>Upcoming dates</h2>
            <?php
            displayUpcomingDeadlines($_SESSION['user_id']);
            ?>
        </div>
    </div>
</main>
<?php
echo pageEnd();
?>
