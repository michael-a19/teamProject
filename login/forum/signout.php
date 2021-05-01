<?php
// error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// error_reporting(E_ALL ^ E_DEPRECATED);

//signout.php page
// include links to connect and header php files
include 'connect.php';
include 'header.php';

echo '<h2>Sign out</h2>';

//if the user is signed in
if($_SESSION['loggedIn'] == true)
{
	//unset all variables and set them as NULL
	$_SESSION['signed_in'] = NULL;
	$_SESSION['user_forename'] = NULL;
	$_SESSION['user_id']   = NULL;

	// display success message to user
	echo 'Succesfully signed out.';
	// redirect the user to the homepage after signout
	// header('Location: forum.php');
	echo "<script>window.top.location='forum.php'</script>";

}
else
{
	// if the user is not signed in, display message and option to sign in.
	echo 'You are not signed in. Would you <a href="../login.php">like to</a>?';
}

// include footer to webpage
include 'footer.php';
?>