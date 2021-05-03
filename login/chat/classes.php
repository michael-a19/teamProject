<?php
    /**
     * @author Michael Anderson 
     * @author W18032122
     * 
     * This script creates a page that allows a teacher to view all the classes they teach
     * Teachers can click on any class they teach and be directed to a page that displays all students in that class
     * Students can not access this page, if a student tries they will be redirected to the main chat page
     */
    session_start();


    include_once("../classes/recordset.class.php");
    include_once("../includes/pagefunctions.inc.php");
    include_once("../includes/functions.inc.php");
    include_once("./includes/chatfunctions.inc.php");

    //check if the user is logged in, method redirects user to the login page if they are not logged in
    checkLoggedIn();

    //check if the user is a teacher, if the user is a student redirect them to the chat page
    if(!checkIfTeacher())
    { 
        header("location: ./index.php");
    }

    //define the wrapper to hold the class details 
    $chosenClassDetails = "
                <div id='class-options-wrapper'>
                    <a class='class-option' href=''><span >Create a new class</span></a>
                </div>  
                ";

    //variable to hold the chosen class ID 
    $chosenClass = "";

    //variable to hold anys error
    $error= "";

    //get the chosen class ID if it exists in the URL
    if(isset($_GET['class']))
    {
        $chosenClass =  htmlspecialchars($_GET["class"]);
    }
    
    //create an instance of the recordset to query the database
    $recset = new RecordSet();

    //query to get a list of all classes that a teacher teaches
    $classesQuery = "SELECT tp_class.class_id, tp_class.class_desc, tp_subject.subject_name, tp_class.class_year_group FROM tp_class 
    join tp_class_teacher on tp_class_teacher.class_id = tp_class.class_id 
    join tp_subject on tp_subject.subject_id = tp_class.subject_id
    WHERE tp_class_teacher.teacher_id = :teacherID";

    $params = array();
    //set the teacherID to return the classes for the current user 
    $params['teacherID'] = $_SESSION['user_id']; 

    try 
    {
        //get the classes from the database
        $classes = $recset->getRecordSet($classesQuery, $params);
    }
    catch(Exception $e)
    {   
        //set classes to an empty array to prevent error when checking the number of results
        $classes = array();
        $error .= "ERROR An error occured connecting to your classes<br/>";
    }

    //variable to hold the hmtl for the list of classes
    $classList = ""; 

    //check if any results are found
    if(count($classes) > 0)
    {
        //iterate through the results and save each class details to a string 
        foreach($classes as $class)
        {
            //if a class is selected and that class is found in the results then save the classes details to display later
            if( (isset($chosenClass)) && ($chosenClass == $class['class_id']) )
            {
                //get count of students in the selected class
                $countQuery = "SELECT count(*) as classCount from tp_class_members where class_id = :classID";
                $countParams['classID'] = $class['class_id'];

                try 
                {
                    $classCount =  $recset->getRecordSet($countQuery, $countParams);
                    $classCount = $classCount[0]['classCount'];
                }
                catch(Exception $e)
                {
                    $classCount = "error";
                }

                //save the details of the chosen class to a variable
                $chosenClassDetails = showClassDetails($class,$classCount);
                //add the selected class attribute to the chosen class in the list so that it can be easily identified
                $classList .= "<a class='class-link selected' href='./classes.php?class={$class['class_id']}'>{$class['class_desc']}</a>";
                continue;

            }
            //add each class from the database results to the class list 
            $classList .= "<a class='class-link' href='./classes.php?class={$class['class_id']}'>{$class['class_desc']}</a>";
        }
    }
    else
    {
        //no classes found 
        $classList .= "you have no classes <a href='/login/planner/manageClasses.php'>create one</a>"; 
        
    }
    //if any errors occured show at the top of the class details section of the page
    if(!empty($error))
    {
        $temp = $chosenClassDetails;
        $chosenClassDetails = "<div id='chat-error'>" . $error . "</div>". $temp;
    }

    //create page here 
    echo pageStart("all classes", "./chat_styles/chat_style.css");
    echo createNav();
    echo createBanner(); 
    echo "<main>";
   
    
?>
  

    <div id="chat-page-wrapper">
    
        <div id="chat-content-wrapper"> 
            
            <div id="contacts-wrapper">

                <div id="contacts-banner">
                    Classes you teach
                </div>
                
                <?php
                    //echo classlist
                    echo $classList;
                ?>
            </div>

            <div id="class-details-wrapper"> 

               <?php
                    //display chosen class details if one is selected
                    echo $chosenClassDetails;
               ?>
            </div>
        </div>

<?php
    echo PageEnd();
?>



