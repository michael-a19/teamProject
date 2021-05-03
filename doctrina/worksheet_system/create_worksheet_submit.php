<?php
include_once("../classes/recordset.class.php");
checkLoggedIn();

//make a list that would be populated by the result of validate_for
list($input, $errors) = validate_form();
//If $errors is not null there is errors
if ($errors) {
	show_errors($errors, $input);
} else {
    process_worksheet($input);
}

function validate_form() {
		//Create an array to store the inputs from the form
		$input = array();
		//Create an array to store error messages
		$errors = array();

		// Retrieve the values entered into the form and add them to the $input array
		//$input['worksheet_id'] = filter_has_var(INPUT_GET, 'worksheet_id') ? $_GET['worksheet_id'] : null;
		$input['worksheet_title'] = filter_has_var(INPUT_GET, 'worksheet_title') ? $_GET['worksheet_title'] : null;
		$input['worksheet_summary'] = filter_has_var(INPUT_GET, 'worksheet_summary') ? $_GET['worksheet_summary'] : null;
		$input['question1Question'] = filter_has_var(INPUT_GET, 'question1Question') ? $_GET['question1Question'] : null;
		$input['question1Answer'] = filter_has_var(INPUT_GET, 'question1Answer') ? $_GET['question1Answer'] : null;   	
		$input['totalScore'] = filter_has_var(INPUT_GET, 'totalScore') ? $_GET['totalScore'] : null;
		$input['id'] =  $_GET['id'];

		//Trim all of the variables to ensure there is no whitespace
		$input['worksheet_title'] = trim($input['worksheet_title']);
		$input['worksheet_summary'] = trim($input['worksheet_summary']);
		$input['question1Question'] = trim($input['question1Question']);
		$input['question1Answer'] = trim($input['question1Answer']);
		
		//Filter the text fields and ensure no scripting is used
		$input['worksheet_title'] = filter_var($input['worksheet_title'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$input['worksheet_summary'] = filter_var($input['worksheet_summary'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$input['question1Question'] = filter_var($input['question1Question'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$input['question1Answer'] = filter_var($input['question1Answer'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				
		return array($input, $errors);
}

//Function to process the worksheet using the validated inputs by passing them as parameters 
function process_worksheet($input) {
	try {	
		$database = new RecordSet();

		//Get a connection to the database
		$sql = "INSERT INTO tp_worksheet(worksheet_title, worksheet_summary, worksheet_total_score, worksheet_creation_time, class_id)
		VALUES (':worksheet_title', ':worksheet_summary', ':total_score', ':worksheet_creation_time', ':class_id')";
	
		$date = date('Y-m-d H:i:s');
				
		$database->writeToDB($sql, array("worksheet_title" => $input['worksheet_title'], "worksheet_summary" => $input['worksheet_summary'], "total_score" => $input['totalScore'], "worksheet_creation_time" => $date, "class_id" => $input['id']));
	} catch (Exception $e) {	//Catch any potential exceptions and display them
			echo "<p>The event has not been updated: " . $e->getMessage() . "</p>\n";
	}
}
function show_errors() {
	
}

?>