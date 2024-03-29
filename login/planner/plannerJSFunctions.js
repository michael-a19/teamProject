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

function changeDate(date)
{
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("date", date);
    window.location.search = searchParams.toString();
}
