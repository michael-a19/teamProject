<?php

session_start();
include("../includes/pagefunctions.inc.php");
include_once("../includes/functions.inc.php");
include_once("../classes/recordset.class.php");

checkLoggedIn();

$database = new RecordSet();
echo pageStart("Worksheet", "worksheet_section_style.css");
echo createNav();
echo createBanner();

//GET class_id
$class_id = filter_has_var(INPUT_GET, 'class_id') ? $_GET['class_id'] : null;

$page = "<main><h1>Create a worksheet</h1>
		
	<!--Name-->
		<form method='get' action='create_worksheet_submit.php'>
			<label>
				Set Title 
				<input type='text' name='title'/>
			</label>

			<!--Worksheet_Summary-->
			<textarea name='worksheet_summary'> </textarea>

		<fieldset><legend>Question 1</legend>
			<label>Enter a Question 
					<input type ='text' name = 'question1Question'/>
			</label>
			
			<label>Enter an Answer 
					<input type ='text' name = 'question1Answer'/>
			</label>
			
			<label>Enter Score for this Question
				<input type ='number' name='question1Score'/> 	
			</label>
			
		</fieldset>
		
		<label>Total Score for Worksheet is
			<input name='totalScore' id='totalScore' type ='number' readonly/>
		</label>
		
		<input type='text' value=".$class_id." name='id'/>
		
		<input type='submit' value='Submit' disabled/> <p>Button disabled to avoid errors</p>
		</form>
	</main>";
	echo $page;
?>