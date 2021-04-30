<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add new class</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="plannerJSFunctions.js"></script>
</head>
<?php
include("functions.php")
?>
<body>
<a href="manageClasses.php">&#8592; Class list</a>
<h2>
    Add new class
</h2>
<form action="addClass.php" method="get">
    <label> Name:
        <input type="text" name="name" required>
    </label>
    <label>
        <select name="subject">
            <?php
            displaySubjectSelectOptions();
            ?>
        </select>
    </label>
    <input type="submit" value="Create">
</form>
</body>