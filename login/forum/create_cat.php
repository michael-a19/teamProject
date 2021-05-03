<?php
//create_cat.php
include 'connect.php';
include 'header.php';

echo '<h2>Create a subject</h2>';
// if the user is not signed in OR the user_type_id (type) is not equal to '1'
// 2 = teacher 1= student
if(!isset($_SESSION['user_id']) | $_SESSION['type'] != 2 )
{
	//the user is not an admin
	echo 'Sorry, you do not have sufficient rights to access this page.';
}
else
{
	//the user has admin privilidges
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		// display the form 
		echo '<form method="post" action="">
			Subject name: <input type="text" name="cat_name" /><br />
			Subject description:<br /> <textarea name="cat_description" /></textarea><br /><br />
			<input type="submit" value="Add subject" />
		 </form>';
	}
	else
	{
		// the form has been posted, post sql insert script to database
		$sql = "INSERT INTO f_categories(cat_name, cat_description)
		   VALUES('" . mysql_real_escape_string($_POST['cat_name']) . "',
				 '" . mysql_real_escape_string($_POST['cat_description']) . "')";
		$result = mysql_query($sql);
		if(!$result)
		{
			// something has went wrong, display an error message
			echo 'Error: ' . mysql_error();
		}
		// provide user with success message
		else
		{
			echo 'New subject succesfully added.';
		}
	}
}

// include footer to webpage
include 'footer.php';
?>
