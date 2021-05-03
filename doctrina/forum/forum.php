<?php
//forum.php page
// include links to connect and header php files
include 'connect.php';
include 'header.php';

// sql stmt to gather data on subjects, and subject topics
$sql = "SELECT
			f_categories.cat_id,
			f_categories.cat_name,
			f_categories.cat_description,
			COUNT(f_topics.topic_id) AS topics
		FROM
			f_categories
		LEFT JOIN
			f_topics
		ON
			f_topics.topic_id = f_categories.cat_id
		GROUP BY
			f_categories.cat_name, f_categories.cat_description, f_categories.cat_id";

$result = mysql_query($sql);

// if there is an error with results
if(!$result)
{
	// display error message to the user
	echo 'The categories could not be displayed, please try again later.';
}
else
{
	if(mysql_num_rows($result) == 0)
	{
		// if there are no results then display error message to the user
		echo 'No subjects defined yet.';
	}
	else
	{
		//displaying the table
		echo '<table border="1">
			  <tr>
				<th>Subject</th>
				<th>Last topic</th>
			  </tr>';

		// insert data from database into the table
		while($row = mysql_fetch_assoc($result))
		{
			echo '<tr>';
				echo '<td class="leftpart">';
					echo '<h3><a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
				echo '</td>';
				echo '<td class="rightpart">';

				// pull topic data and date
					$topicsql = "SELECT
									topic_id,
									topic_subject,
									topic_date,
									topic_cat
								FROM
									f_topics
								WHERE
									topic_cat = '" . $row['cat_id'] . "'
								ORDER BY
									topic_date
								DESC
								LIMIT
									1";

					$topicsresult = mysql_query($topicsql);

					// if sql stmt is not returned then display error message
					if(!$topicsresult)
					{
						// error message
						echo 'Last topic could not be displayed.';
					}
					else
					{
						if(mysql_num_rows($topicsresult) == 0)
						{
							// if there are no topics in the databse, display arror message
							echo 'There are no topics defined.';
						}
						else
						{
							// display result in the table. Last topic added to the table and date added.
							while($topicrow = mysql_fetch_assoc($topicsresult))
							echo '<a href="topic.php?id=' . $topicrow['topic_id'] . '">' . $topicrow['topic_subject'] . '</a> at ' . date('d-m-Y', strtotime($topicrow['topic_date']));
						}
					}
				echo '</td>';
			echo '</tr>';
		}
	}
}

// include footer to webpage.
include 'footer.php';
 ?>
