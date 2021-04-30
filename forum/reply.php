<?php
// reply.php page
// include links to connect and header php files
include 'connect.php';
include 'header.php';

// if someone tries to access reply.php directly by url
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
	// display error message 
	echo 'This file cannot be called directly.';
}
else
{
	// check for sign in status
	if(!$_SESSION['signed_in'])
	{
		echo 'You must be signed in to post a reply.';
	}
	else
	{
		// sql stmt for logged in user posting a reply
		// inserting the reply into the database table
		// NOW() function used to insert current time/date stamp
		// mysql_real_escape_string - escapes special characters
		$sql = "INSERT INTO
					f_posts(post_content,
						  post_date,
						  post_topic,
						  post_by)
				VALUES ('" . $_POST['reply-content'] . "',
						NOW(),
						'" . mysql_real_escape_string($_GET['id']) . "',
						'" . $_SESSION['user_id'] . "')";

		$result = mysql_query($sql);

		// if the posting of the data to the database fails
		if(!$result)
		{
			// display an error message to the user
			echo 'Your reply has not been saved, please try again later.';
		}
		else
		{
			// if the post was successful, successful message displayed to user with link to post
			echo 'Your reply has been saved, check out <a href="topic.php?id=' . htmlentities($_GET['id']) . '">the topic</a>.';
		}
	}
}

// include footer content on webpage
include 'footer.php';
?>
