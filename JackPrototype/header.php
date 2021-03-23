<!DOCTYPE html lang="en">
<head>
 	<title>Forum Prototype</title>
	<!-- <link rel="stylesheet" href="style.css" type="text/css"> -->
</head>
<body>
<h1>Forum Prototype</h1>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="index.php">Home</a>
		<a class="item" href="create_topic.php">Create a topic</a>
		<a class="item" href="create_cat.php">Create a subject</a>

		<div id="userbar">
		<?php
		if($_SESSION['signed_in'])
		{
			echo 'Hello <b>' . htmlentities($_SESSION['user_name']) . '</b>. Not you? <a class="item" href="signout.php">Sign out</a>';
		}
		else
		{
			echo '<a class="item" href="signin.php">Sign in</a> or <a class="item" href="signup.php">Create an account</a>';
		}
		?>
		</div>
	</div>
		<div id="content">
