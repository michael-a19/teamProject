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

?>
<main>
    <div class="planner-page">
        <div class="planner">
            <div class="planner-header">
                <div class="dropdown">
                    <button onclick="toggleViewDropDown()" class="dropbtn">Day</button>
                    <div id="view-btn" class="dropdown-content">
                        <?php
                        echo "<a href=\"plannerDay.php?date=".$date."\">Day</a>\n";
                        echo "<a href=\"plannerWeek.php?date=".$date."\">Week</a>\n";
                        ?>
                    </div>
                </div>
                <?php
                echo "<button onclick=\"changeDate('".date("Y-m-d", strtotime("-1 day", $unixDate))."')\" class=\"backWeek\">&#9664;</button>\n";
                echo "<h1>".date("l jS F Y", $unixDate)."</h1>\n";
                echo "<button onclick=\"changeDate('".date("Y-m-d", strtotime("+1 day", $unixDate))."')\" class=\"forwardWeek\">&#9654;</button>\n";
                ?>
                <div class="add-event-btns">
                    <?php
                    if ($isTeacher)
                    {
                        echo "<a href='addScheduledEventForm.php?eventType=class&date=".$date."'>Add scheduled class</a><br>\n";
                        echo "<a href='addScheduledEventForm.php?eventType=meeting&date=".$date."'>Add scheduled meeting</a>\n";
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
                    displayDayHeader($unixDate);
                    ?>
                    <div class="day-wrapper">
                        <?php
                        displayDayColumn();
                        displayDayEvents(date("Y-m-d", $unixDate), $_SESSION['user_id']);
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
    <script type="text/javascript">
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
    </script>
</main>
<?php
echo pageEnd();
?>

