<?php
//signin.php page 
// include connect and header page content
include 'connect.php';
include 'header.php';

echo '<h3>Sign in</h3><br />';

// check to see if the user is already logged in
// if already logged in then no point in displaying the page
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true)
{
	// message to tell the user they are already signed in and link to signout
	echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
}
else
{
	// if the form to login has not been posted, show it on the page
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		echo '<form method="post" action="">
			Username: <input type="text" name="user_forename" /><br />
			Password: <input type="password" name="user_pass"><br />
			<input type="submit" value="Sign in" />
		 </form>';
	}
	else
	{
		// the form has been processed, data must be posted
		// declare an array for errors
		$errors = array();

		// check to see that a username has been set
		if(!isset($_POST['user_forename']))
		{
			// if username has not been set, add error message to array
			$errors[] = 'The username field must not be empty.';
		}

		// check to see that a password has been entered
		if(!isset($_POST['user_pass']))
		{
			// if password has not been entered, add error message to array
			$errors[] = 'The password field must not be empty.';
		}

		// check the array to see if there are any errors
		if(!empty($errors))
		{
			// if the array is not empty, display an error message to the user
			echo 'Oops.. one or more of the fields are not filled in correctly...<br /><br />';
			echo '<ul>';
			// list each of the error messages added to the array
			foreach($errors as $key => $value)
			{
				// listing the array messages
				echo '<li>' . $value . '</li>';
			}
			echo '</ul>';
		}
		else
		{
			// the form has no errors
			// mysql_real_escape_string - escaping special characters, sanitation and validation
			// sha1 - hash function to keep password secure in database
			$sql = "SELECT 
						user_id,
						user_forename,
						user_type_id
					FROM
						tp_users
					WHERE
					user_forename = '" . mysql_real_escape_string($_POST['user_forename']) . "'
					AND
						user_password = '" . sha1($_POST['user_password']) . "'";

			$result = mysql_query($sql);
			// if the result has an error
			if(!$result)
			{
				// display error message to the user
				echo 'Something went wrong while signing in. Please try again.';
			}
			// sql stmt successful
			else
			{
				// sql stmt returns empty results
				if(mysql_num_rows($result) == 0)
				{
					// sql stmt returns empty results, entered credential combination is incorrect
					// display error message to the user
					echo 'You have supplied a wrong user/password combination. Please try again.';
				}
				else
				{
					// credentials entered were successful
					// set the $_SESSION['signed_in'] variable to true
					$_SESSION['loggedIn'] = true;

					// set user_id and user_name values in the $_SESSION
					while($row = mysql_fetch_assoc($result))
					{
						$_SESSION['user_id'] 	= $row['user_id'];
						$_SESSION['user_forename'] 	= $row['user_forename'];
						$_SESSION['user_type_id'] = $row['user_type_id'];
					}

					// display welcome message to the user
					echo 'Welcome, ' . $_SESSION['user_forename'] . '. <br /><a href="forum.php">Proceed to the forum overview</a>.';
				}
			}
		}
	}
}

// include footer content to the webpage
include 'footer.php';
?>
