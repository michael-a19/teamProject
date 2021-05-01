<?php
//signup.php page 
// include connect and header files
include 'connect.php';
include 'header.php';

echo '<h3>Sign up</h3><br />';

// if the signup form has not been posted already, display on webpage
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    echo '<form method="post" action="">
 	 	Username: <input type="text" name="user_forename" /><br />
 		Password: <input type="password" name="user_pass"><br />
		Password again: <input type="password" name="user_pass_check"><br />
		E-mail: <input type="email" name="user_email"><br />
 		<input type="submit" value="Sign Up" />
 	 </form>';
}
else
{
    // the form has been submitted
	// declare array for error messages to be stored to
	$errors = array();

	// if the username is set
	if(isset($_POST['user_forename']))
	{
		// check fror alphanumeric characters
		if(!ctype_alnum($_POST['user_forename']))
		{
			// add error message to array
			$errors[] = 'The username can only contain letters and digits.';
		}
		// check username length
		if(strlen($_POST['user_forename']) > 30)
		{
			// add error message to array
			$errors[] = 'The username cannot be longer than 30 characters.';
		}
	}
	// username field is empty
	else
	{
		// add error message to array
		$errors[] = 'The username field must not be empty.';
	}

	// check to see if password is set
	if(isset($_POST['user_pass']))
	{
		// check to see if passwords match
		if($_POST['user_pass'] != $_POST['user_pass_check'])
		{
			// add error message to array
			$errors[] = 'The two passwords did not match.';
		}
	}
	else
	{
		// add error message to array
		$errors[] = 'The password field cannot be empty.';
	}

	// if error array is not empty
	if(!empty($errors))
	{
		// display error message to the user on web page
		echo 'Oops.. one or more of the fields are not filled in correctly...<br /><br />';
		echo '<ul>';
		// for each error message display in a list
		foreach($errors as $key => $value)
		{
			echo '<li>' . $value . '</li>';
		}
		echo '</ul>';
	}
	else
	{
		//the form has been posted, insert data to database table
		// the form has no errors
		// mysql_real_escape_string - escaping special characters, sanitation and validation
		// sha1 - hash function to keep password secure in database
		$sql = "INSERT INTO
					f_users(user_forename, user_pass, user_email ,user_date, user_type_id)
				VALUES('" . mysql_real_escape_string($_POST['user_forename']) . "',
					   '" . sha1($_POST['user_pass']) . "',
					   '" . mysql_real_escape_string($_POST['user_email']) . "',
						NOW(),
						0)";

		$result = mysql_query($sql);
		//if the result has an error
		if(!$result)
		{
			// display an error message to the user
			echo 'Something went wrong while signing up. Please try again.';
		}
		else
		{
			// display success message to the user and link to the forum page
			echo 'Succesfully registered. You can now <a href="../login.php">sign in</a> and start posting!';
		}
	}
}

// include footer file to webpage
include 'footer.php';
?>
