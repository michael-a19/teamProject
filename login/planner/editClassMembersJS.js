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
    {
        var options = document.getElementById('right-select-list').options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].selected)
            {
                options.remove(i);
            }
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