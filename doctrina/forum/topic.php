<?php
//topic.php page 
// include connect and header page
include 'connect.php';
include 'header.php';

// sql stmt to select topics based on GET'id'
$sql = "SELECT
			topic_id,
			topic_subject
		FROM
			f_topics
		WHERE
			f_topics.topic_id = " . mysql_real_escape_string($_GET['id']);

$result = mysql_query($sql);

// if the result could not be displayed
if(!$result)
{
	// display an error message to the user
	echo 'The topic could not be displayed, please try again later.';
}
else
{
	// if there are no topics in the database
	if(mysql_num_rows($result) == 0)
	{
		// display error message to the user
		echo 'This topic doesn&prime;t exist.';
	}
	else
	{
		while($row = mysql_fetch_assoc($result))
		{
			//display the posted data in a table
			echo '<table class="topic" border="1">
					<tr>
						<th colspan="2">' . $row['topic_subject'] . '</th>
					</tr>';

			//fetch the posts from the database
			$posts_sql = "SELECT
						f_posts.post_topic,
						f_posts.post_content,
						f_posts.post_date,
						f_posts.post_by,
						tp_users.user_id,
						tp_users.user_forename
					FROM
						f_posts
					LEFT JOIN
						tp_users
					ON
						f_posts.post_by = tp_users.user_id
					WHERE
						f_posts.post_topic = " . mysql_real_escape_string($_GET['id']);

			$posts_result = mysql_query($posts_sql);

			// if the post sql stmt
			if(!$posts_result)
			{
				// if there is an error with the sql stmt display error message
				echo '<tr><td>The posts could not be displayed, please try again later.</tr></td></table>';
			}
			else
			{
				// if the posts could be displayed then display them in the table
				while($posts_row = mysql_fetch_assoc($posts_result))
				{
					echo '<tr class="topic-post">
							<td class="user-post">' . $posts_row['user_forename'] . '<br/>' . date('d-m-Y H:i', strtotime($posts_row['post_date'])) . '</td>
							<td class="post-content">' . htmlentities(stripslashes($posts_row['post_content'])) . '</td>
						  </tr>';
				}
			}

			// // if the user is not loggedIn
			if(!isset($_SESSION['user_id']))
			{
				// display error message to sign in before the user is able to reply
				echo '<tr><td colspan=2>You must be <a href="../login.php">signed in</a> to reply. You can also <a href="../register.php">sign up</a> for an account.';
			}
			else
			{
				//show the table which allows the user to reply to the messages
				echo '<tr><td colspan="2"><h2>Reply:</h2><br />
					<form id="replyBox" method="post" action="reply.php?id=' . $row['topic_id'] . '">
						<textarea name="reply-content"></textarea><br /><br />
						<input type="submit" value="Submit reply" />
					</form></td></tr>';
			}

			//close the table tags
			echo '</table>';
		}
	}
}

// include the footer to the page
include 'footer.php';
?>
