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
?>

<div id="worksheet-page-wrapper">

<?php
$completed = filter_has_var(INPUT_GET, 'completed') ? $_GET['completed'] : null;
$worksheet_id = filter_has_var(INPUT_GET, 'worksheet_id') ? $_GET['worksheet_id'] : null;


$sqlQuery = "SELECT worksheet_question_id, worksheet_question_type, worksheet_question_score, worksheet_question_content 
			FROM tp_worksheet_question
			WHERE worksheet_id = :worksheet_id";
			
			$classParameters=array(); //Emptying the array
            $classParameters['worksheet_id'] = $worksheet_id;
			$classResult = "";
try
{
	$classResult = $database->getRecordSet($sqlQuery, $classParameters);
}

catch(Exception $e)
{
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
}

$sqlQuery = "SELECT worksheet_id, worksheet_title, worksheet_summary, worksheet_total_score
			FROM tp_worksheet
			WHERE worksheet_id = :worksheet_id";

			$classParameters=array();
		    $classParameters['worksheet_id'] = $worksheet_id;
			$worksheetDesc = "";
	
	try
	{
		$worksheetDesc = $database->getRecordSet($sqlQuery, $classParameters);
	}

	catch(Exception $e)
	{
		echo "<p>Query failed: ".$e->getMessage()."</p>\n";
	}
				
	foreach($worksheetDesc as $result){
		$title = $worksheetDesc['worksheet_title'];
		$worksheet_html = "<main> <h1>".$title."</h1><form>";
	
	}
	//Variable to bring back worksheets currently not started by a user
	foreach($classResult as $result) {
		$sqlQuery2 = "SELECT worksheet_answer_content
		FROM tp_worksheet_answer
		WHERE worksheet_question_id = :worksheet_question_id";

	$classParameters=array(); //Emptying the array
	$classParameters['worksheet_question_id'] = $result['worksheet_question_id'];

	try
	{
		$classResult2 = $database->getRecordSet($sqlQuery2, $classParameters);
	}
	catch(Exception $e)
	{
		$selectedClassDetails = "internal server error";
	}

	if($result['worksheet_question_type'] == 'single_choice'){			
		$worksheet_html .= "<p class='question'>".$result['worksheet_question_content']."</p>\n";
		
		foreach($classResult2 as $result2) {
			$worksheet_html .= "
			<label> ".$result2['worksheet_answer_content']."\n
				<input type='radio' name='".$result['worksheet_question_id']."'>
			</label>";		
		}
	}
	
	else if($result['worksheet_question_type'] == 'multiple_choice'){			
		$worksheet_html .= "<p class='question'>".$result['worksheet_question_content']."</p>\n";
	
	foreach($classResult2 as $result2) {
			$worksheet_html .= "
		<label> ".$result2['worksheet_answer_content']."\n
			<input type='checkbox' name='".$result['worksheet_question_id']."'>
		</label>";		
		}
	}
	
	else if($result['worksheet_question_type'] == 'textfield'){			
		$worksheet_html .= "<p class='question'>".$result['worksheet_question_content']."</p>\n";
		
	foreach($classResult2 as $result2) {
			$worksheet_html .= "
			<p hidden id='textfieldAnswer'>".$result2['worksheet_answer_content']."</p>
			<textarea name='".$result['worksheet_question_id']."'> </textarea>
		";		
		}
	}

}

$worksheet_html .= "</form></main></div></div>";
echo $worksheet_html;
echo pageEnd();

?>