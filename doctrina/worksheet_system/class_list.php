<?php 
session_start();
include_once("../includes/functions.inc.php");
include_once("../includes/pagefunctions.inc.php");
include_once("../classes/recordset.class.php");

$database = new RecordSet();
//Checks if a user is logged 
//User is redirected to the login page if not
checkLoggedIn();
    
$classesList = "";
$selectedClassDetails = "Please select the class you wish to view";

//Checks if the user is a teacher using session['type'] variable
//type == 2 == teacher,  type == 1 == student
$selectedClassID = "";
if(isset($_GET['class']))
{
	$selectedClassID = htmlspecialchars($_GET['class']);
}
if(checkIfTeacher())
{
	//Query for the teacher's section
	//query to get classes that the logged in teacher teaches 
	$getClasses = "SELECT tp_class.class_id, tp_class.class_desc, tp_subject.subject_name, tp_class.class_year_group 
	FROM tp_class 
    join tp_subject on tp_subject.subject_id = tp_class.subject_id
    join tp_class_teacher on tp_class_teacher.class_id = tp_class.class_id 
    WHERE tp_class_teacher.teacher_id = :teacher_id";

	$parameters['teacher_id'] = $_SESSION['user_id']; //get users id    
        try 
        {
            $teachersClasses = $database->getRecordSet($getClasses, $parameters);
        }
        catch(Exception $e)
        {
            $classesList = "internal server error";
        }
       
        //Checks for results
        if(count($teachersClasses) > 0)
        {
            foreach($teachersClasses as $class)
            {
                //Displays the relevant information for a teacher's classes 
                $classesList .= "<a class='teacher-classes-link' href='./class_list.php?class={$class['class_id']}'>
                        <div class='teacher-class'>
                            Class: {$class['class_desc']} </br>
                            Subject: {$class['subject_name']} </br>
                            Year Group: {$class['class_year_group']} </br>
                        </div>
                    </a>";
            }
            //If there is a class selected, get the related results
            if(!empty($selectedClassID))
            {
                //The teacher's selected class
                $getClassDetails = "SELECT tp_class.class_id, tp_class.class_desc, tp_subject.subject_name, tp_class.class_year_group 
                FROM tp_class 
                join tp_subject on tp_subject.subject_id = tp_class.subject_id
                join tp_class_teacher on tp_class_teacher.class_id = tp_class.class_id 
                WHERE tp_class_teacher.teacher_id = :teacher_id AND tp_class.class_id = :class_id";
                
                $classParameters['teacher_id'] = $_SESSION['user_id'];
                $classParameters['class_id'] = $selectedClassID;

                try
                {
                    $classResult = $database->getRecordSet($getClassDetails, $classParameters);
                    $classResult = $classResult[0];
                }
                catch(Exception $e)
                {
                    $selectedClassDetails = "internal server error";
                }
                //Displaya the desired details of the selected class
                $selectedClassDetails = "
                        <div id='class-details'>		
							<div id='class-heading'>							
								<div id='class-type'>
									{$classResult['subject_name']}
								</div>
							
								<div id='class-title'>
									{$classResult['class_desc']}    
								</div>
                            
								<div id='class-teacher'>
									Teacher: {$_SESSION['user_forename']}   {$_SESSION['user_surname']}   
								</div>
							
							</div>
                            
							<div id='selected-class-buttons'>
								<a class='class-option-link' href='create_worksheet.php?class_id={$class['class_id']}'><button class='class-option-button'>Create a Worksheet</button></a>
                            </div>
							</br>
										
							<div id='selected-class-subheadings'>
								<div class='work-list'>
									<h3>Assigned Work</h3>
								</div>
								
								<div class='work-list'>
									<h3>Worksheet Drafts</h3>
								</div>
								
								<div class='work-list'>	
									<h3>Student Submissions</h3>
								</div>
                            </div>
                        </div> ";
            }
        }
        else 
        {
            //if no results for classes are found, display this message - provides the user with a link to the add class page
            $classesList .= "<div class='teacher-class'><p>You currently do not have any classes. Please create them <em><b><a href='/login/planner/addClassForm.php'>here</a></em></b></p></div>";
        }
}
    else //if the user is a teacher, get all the classes the student is enrolled on 
    {
        //SQL query that gets all of the classes for a logged in student
        $userClasses = "SELECT tp_class.class_desc, tp_class.class_id, tp_subject.subject_name, tp_users.user_id, tp_users.user_surname, tp_users.user_online_status, tp_users.user_forename, tp_class.class_year_group
                        FROM tp_class JOIN tp_subject ON tp_subject.subject_id = tp_class.subject_id 
                        JOIN tp_class_members ON tp_class_members.class_id = tp_class.class_id 
                        JOIN tp_class_teacher ON tp_class_teacher.class_id = tp_class.class_id 
                        JOIN tp_users ON tp_users.user_id = tp_class_teacher.teacher_id 
                        WHERE tp_class_members.user_id = :userID";

        //gets the logged in user's id
        $studentParameters['userID'] = $_SESSION['user_id'];

        try
        {
            $allClasses = $database->getRecordSet($userClasses, $studentParameters);
        }
        catch(Exception $e)
        {
            $classesList = "internal server error ".$e->getMessage();
        }
      
        if(count($allClasses) > 0)
        {
            //goes through each student class and concatenates it to a string to be displayed
            foreach($allClasses as $class)
                {
                    $classesList .= "
                                <a class='teacher-classes-link' href='./class_list.php?class={$class['class_id']}'>
                                    <div class='teacher-class'>
                                        Class: {$class['class_desc']} </br>
                                        Subject: {$class['subject_name']} </br>
                                        Teacher: {$class['user_forename']} {$class['user_surname']} </br>
                                    </div>
                                </a>";
                }
				
                //gets selected details of a class for the student
                if(!empty($selectedClassID))
                {
                    //for students
                    $getClassDetails = "SELECT tp_class.class_desc, tp_subject.subject_name, tp_class.class_id, tp_users.user_id, tp_users.user_forename, tp_users.user_surname, tp_users.user_online_status 
                    from tp_class JOIN tp_subject on tp_subject.subject_id = tp_class.subject_id 
                    Join tp_class_members on tp_class_members.class_id = tp_class.class_id 
                    JOIN tp_class_teacher on tp_class_teacher.class_id = tp_class.class_id 
                    JOIN tp_users on tp_users.user_id = tp_class_teacher.teacher_id 
                    WHERE tp_class_members.user_id = :user_id
                    AND tp_class.class_id = :class_id";
                    $classParameters=array(); //Empties the array
                    $classParameters['user_id'] = $_SESSION['user_id'];
                    $classParameters['class_id'] = $selectedClassID;						

					//SQL query to bring back worksheets not started by a user
					$sqlQuery = "SELECT tp_worksheet.worksheet_id, worksheet_title, worksheet_total_score
					FROM tp_worksheet
					JOIN tp_worksheet_taken_stats
					ON tp_worksheet.worksheet_id = tp_worksheet_taken_stats.worksheet_id
					WHERE user_id = :user_id
					AND class_id = :class_id			
					AND worksheet_ts_status = :number
					ORDER BY tp_worksheet.worksheet_id";
					
					$classParameters2=array();
                    $classParameters2['user_id'] = $_SESSION["user_id"];
					$classParameters2['class_id'] = $selectedClassID;
                    $classParameters2['number'] = 0;
	
	
					//SQL query to bring back worksheets that are saved but not uploaded for a user
					$sqlQuery2 = "SELECT worksheet_title, worksheet_total_score
					FROM tp_worksheet
					JOIN tp_worksheet_taken_stats
					ON tp_worksheet.worksheet_id = tp_worksheet_taken_stats.worksheet_id
					WHERE user_id = :user_id
					AND class_id = :class_id			
					AND worksheet_ts_status = :number
					ORDER BY tp_worksheet.worksheet_id";
					
					$classParameters3=array(); //Emptying the array
                    $classParameters3['user_id'] = $_SESSION["user_id"];
					$classParameters3['class_id'] = $selectedClassID;
                    $classParameters3['number'] = 1;


					//SQL query to bring back worksheets uploaded for a user
					$sqlQuery3 = "SELECT worksheet_title, worksheet_total_score
					FROM tp_worksheet
					JOIN tp_worksheet_taken_stats
					ON tp_worksheet.worksheet_id = tp_worksheet_taken_stats.worksheet_id
					WHERE user_id = :user_id
					AND class_id = :class_id			
					AND worksheet_ts_status = :number
					ORDER BY tp_worksheet.worksheet_id";
					
					$classParameters4=array(); //Emptying the array
                    $classParameters4['user_id'] = $_SESSION["user_id"];
					$classParameters4['class_id'] = $selectedClassID;
                    $classParameters4['number'] = 2;

						
                    try
                    {
                        $classResult = $database->getRecordSet($getClassDetails, $classParameters);
						$classResult2 = $database->getRecordSet($sqlQuery,  $classParameters2);
						$classResult3 = $database->getRecordSet($sqlQuery2,  $classParameters3);
						$classResult4 = $database->getRecordSet($sqlQuery3,  $classParameters4);                        
                    }
                    catch(Exception $e)
                    {
                        $selectedClassDetails = "internal server error";
                    }
					
					//Variable to bring back worksheets currently not started by a user
					$incompletedWork = "";

					foreach($classResult2 as $result) {
						$incompletedWork .= "<div class='work-item'>
												<a class='worksheet-link' href=worksheet.php?worksheet_id={$result['worksheet_id']}&completed=0>{$result['worksheet_title']}</a>
											</div>\n";
						
					}
					
					//Variable to bring back worksheets saved but not uploaded by a user
					$savedWork = "";
					foreach($classResult3 as $result) {
						$savedWork .= "<div class='work-item'>
												<a class='worksheet-link' href=worksheet.php?worksheet_id={$result['worksheet_id']}&completed=1>{$result['worksheet_title']}</a>
											</div>\n";
					}
					
					//Variable to bring back worksheets that have been uploaded by a user
					$completedWork = "";
					foreach($classResult4 as $result) {
						$completedWork .= "<div class='work-item'>
												<a class='worksheet-link' href=worksheet.php?worksheet_id={$result['worksheet_id']}&completed=2>{$result['worksheet_title']}</a>	
											</div>\n";
					}					
					
                    //sets the details of the class
                    if(count($classResult)>0) //If there is at least 1 class, display the following	
                    {
                        $classResult = $classResult[0];
                        $selectedClassDetails = "
                                    <div id='class-details'>
										<div id='class-heading'>
											<div id='class-type'>
												{$classResult['subject_name']}
											</div>
									
											<div id='class-title'>
												{$classResult['class_desc']}    
											</div>
                                    
											<div id='class-teacher'>
												Your Teacher: {$classResult['user_forename']}   {$classResult['user_surname']}   
											</div>
										</div>
										
										</br>
										
										<div id='selected-class-subheadings'>
											<div class='work-list'>
												<h3>Unstarted Worksheets</h3>
												</br>
													$incompletedWork
											</div>

											<div class='work-list'>
												<h3>Continue a Worksheet</h3>
												</br>
												<a class='worksheet-link' href='./class_list.php?class={$class['class_id']}'>
													$savedWork
												</a>
											</div>
										
											<div class='work-list'>
												<h3>Submitted Worksheets</h3>
												</br>
												<a class='worksheet-link' href='./class_list.php?class={$class['class_id']}'>												
													$completedWork
												</a>
											</div>											
										</div>
									</div> ";   
                        }
                }
        }
        else //if there are no results found for the student
        {
            //if no results are found, display this message
            $classesList .= "<div class='teacher-class'> You are currently not enrolled on any classes. Please contact a teacher if you think this is a mistake!</div>";
        }
    }
		//Creation of page setup
        echo pageStart("Class Work", "worksheet_section_style.css");
        
		//Creation of Navigation and banner
		echo createNav();
        echo createBanner(); 
        echo "<main>";

?>
    <div id="test-page-wrapper">
		<div id="classlist-content-wrapper">
			<div id="teacher-class-list">
				<div id="classes-title">
					<h2>Your Classes</h2>
				</div>
				<?php echo  $classesList; ?>
			</div>
			<div id="chosen-class">
			<?php echo $selectedClassDetails; ?>
			</div>
		</div>
	</div>
    
	<?php
        echo "</main>";
        echo pageEnd();
    ?>