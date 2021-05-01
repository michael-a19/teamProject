<?php
//category.php
// include the connection to database and header files
include 'connect.php';
include 'header.php';

//sql stmt to select the subject/category from $_GET['cat_id']
$sql = "SELECT
			cat_id,
			cat_name,
			cat_description
		FROM
			f_categories
		WHERE
			cat_id = " . mysql_real_escape_string($_GET['id']);

// store sql stmt in a variable
$result = mysql_query($sql);

// if statement - if sql stmt could not fetch results then display error message
if(!$result)
{
	echo 'The subject could not be displayed, please try again later.';
}
else
{
	// if sql stmt has no results then echo relevent message to user
	if(mysql_num_rows($result) == 0)
	{
		echo 'This subject does not exist.';
	}
	else
	{
		//else, print out the data to the page
		while($row = mysql_fetch_assoc($result))
		{
			echo '<h2>Topics in &prime;' . $row['cat_name'] . '&prime; subject category</h2><br />';
		}

		//sql statement to select topic information
		$sql = "SELECT
					topic_id,
					topic_subject,
					topic_date,
					topic_cat
				FROM
					f_topics
				WHERE
					topic_cat = " . mysql_real_escape_string($_GET['id']);
		
		// store sql stmt as a variable - result
		$result = mysql_query($sql);

		// if statement - if sql stmt could not fetch results then display error message
		if(!$result)
		{
			echo 'The topics could not be displayed, please try again later.';
		}
		else
		{
			// if sql stmt has no results then echo relevent message to user
			if(mysql_num_rows($result) == 0)
			{
				echo 'There are no topics in this category yet.';
			}
			else
			{
				//preperation of the table for results
				echo '<table border="1">
					  <tr>
						<th>Topic</th>
						<th>Created at</th>
					  </tr>';

				//   insert data into table
				while($row = mysql_fetch_assoc($result))
				{
					echo '<tr>';
						echo '<td class="leftpart">';
							echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a><br /><h3>';
						echo '</td>';
						echo '<td class="rightpart">';
							echo date('d-m-Y', strtotime($row['topic_date']));
						echo '</td>';
					echo '</tr>';
				}
			}
		}
	}
}

// include footer to webpage
include 'footer.php';
?>
