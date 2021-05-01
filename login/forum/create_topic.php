<?php
//create_topic.php
include 'connect.php';
include 'header.php';

echo '<h2>Create a topic</h2>';
// if the user is not signed in OR the user_type_id is not equal to '1'
if(!isset($_SESSION['user_id']) | $_SESSION['type'] != 2)
{
	echo 'Sorry, you do not have sufficient rights to access this page.';
}
else
{
	//the user is signed in
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		// displat the form
		// pull the subjects from the database to use in the dropdown menu
		$sql = "SELECT
					cat_id,
					cat_name,
					cat_description
				FROM
					f_categories";

		$result = mysql_query($sql);

		// if there is no result
		if(!$result)
		{
			// display and error message to the user
			echo 'Error while selecting from database. Please try again later.';
		}
		else
		{
			if(mysql_num_rows($result) == 0)
			{
				//there are no subjects listed, so a topic can't be added
				if($_SESSION['type'] == 1)
				{
					// gives the user an error message to create a subject first
					echo 'You have not created any subjects yet.';
				}
				else
				{
					echo 'Before you can post a topic, you must wait for an admin to create some subjects.';
				}
			}
			else
			{
				// display the form on the page
				echo '<form method="post" action="">
					Topic Name: <input type="text" name="topic_subject" /><br />
					Subject: ';

				echo '<select name="topic_cat">';
					while($row = mysql_fetch_assoc($result))
					{
						echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
					}
				echo '</select><br />';

				echo 'Message: <br /><textarea name="post_content" /></textarea><br /><br />
					<input type="submit" value="Create topic" />
				 </form>';
			}
		}
	}
	else
	{
		//start the transaction
		$query  = "BEGIN WORK;";
		$result = mysql_query($query);

		// if there is an error with the result variable sql stmt
		if(!$result)
		{
			// display an error message to the user
			echo 'An error occured while creating your topic. Please try again later.';
		}
		else
		{

			// the form has posted, the values need to be posted to the database
			//insert topics to topics table
			$sql = "INSERT INTO
						f_topics(topic_subject,
							   topic_date,
							   topic_cat,
							   topic_by)
				   VALUES('" . mysql_real_escape_string($_POST['topic_subject']) . "',
							   NOW(),
							   '" . mysql_real_escape_string($_POST['topic_cat']) . "',
							   '" . $_SESSION['user_id'] . "'
							   )";

			$result = mysql_query($sql);
			if(!$result)
			{
				// error - display error message to user
				echo 'An error occured while inserting your data. Please try again later.<br /><br />' . mysql_error();
				$sql = "ERROR;";
				$result = mysql_query($sql);
			}
			else
			{
				//add init post to the database in the posts table
				//retrieve the id of the created topic above
				$topicid = mysql_insert_id();

				$sql = "INSERT INTO
							f_posts(post_content,
								  post_date,
								  post_topic,
								  post_by)
						VALUES
							('" . mysql_real_escape_string($_POST['post_content']) . "',
								  NOW(),
								  '" . $topicid . "',
								  '" . $_SESSION['user_id'] . "'
							)";
				$result = mysql_query($sql);

				if(!$result)
				{
					// error occured, display error message to the user
					echo 'An error occured while inserting your post. Please try again later.<br /><br />' . mysql_error();
					$sql = "ERROR;";
					$result = mysql_query($sql);
				}
				else
				{
					// no errors, stmt's worked, commit to database
					$sql = "COMMIT;";
					$result = mysql_query($sql);

					// confirmation message to the user. Link to preview new forum
					echo 'You have succesfully created <a href="topic.php?id='. $topicid . '">your new topic</a>.';
				}
			}
		}
	}
}

// include footer to web page
include 'footer.php';
?>
