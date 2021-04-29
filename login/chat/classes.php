<?php
    // 1 check user is logged in
    //check user is a teacher 
    // 2 get list of classes for user 
    //options to edit them or create a new one 
    //display each as a link to the next page that shows all students for that class
    session_start();
    //include_once("./includes/pagefunctions.inc.php"); //would change obv
    include_once("../classes/recordset.class.php");
    include_once("../includes/pagefunctions.inc.php"); //shit
    include_once("../includes/functions.inc.php"); //shit
   

    checkLoggedIn();
    //if logged in user is a student redirect them to the index page which contains the chat
    if(!checkIfTeacher())
    {
        //if student send straight to the chat section 
        header("location: ./index.php");
    }

    $classDeets = "
                <div id='class-options-wrapper'>
                    <a class='class-option' href=''><span >Create a new class</span></a>
                </div>  
                ";

    $chosenClass = "";

    if(isset($_GET['class']))
    {
        $chosenClass =  htmlspecialchars($_GET["class"]);
    }

    // if( (!isset($_SESSION['loggedIn']) ) || ( $_SESSION['type'] != "Teacher") )
    // {
    //     //redirect to chat page? 
    //     header("location: ./index.php"); //change index.php to different name

    // }

    $recset = new RecordSet('./logindb.sqlite');
    //so here user is logged in and is set to Teacher
   
    $classesQuery = "SELECT tp_class.class_id, tp_class.class_desc, tp_subject.subject_name, tp_class.class_year_group FROM tp_class 
    join tp_class_teacher on tp_class_teacher.class_id = tp_class.class_id 
    join tp_subject on tp_subject.subject_id = tp_class.subject_id
    WHERE tp_class_teacher.teacher_id = :teacherID";
    $params = array();
    $params['teacherID'] = $_SESSION['user_id']; 
    $classes = $recset->getRecordSet($classesQuery, $params);
    
    $classList = ""; 
    //check if any results are found
    if(count($classes) > 0)
    {
        //we gonna display all classes here each ahs an option to edit it but if none we will display a create one! button to link to same place 
        foreach($classes as $class)
        {
            
            if( (isset($chosenClass)) && ($chosenClass == $class['class_id']) )
            {
                //get count of students in a given class
                $countQuery = "SELECT count(*) as classCount from tp_class_members where class_id = :classID";
                $countParams['classID'] = $class['class_id'];
                $classCount =  $recset->getRecordSet($countQuery, $countParams);
                $classCount = $classCount[0]['classCount'];
                $classDeets = showClassDetails($class,$classCount);
                $classList .= "<div class='class-item selected'><a class='class-link' href='./classes.php?class={$class['class_id']}'>{$class['class_desc']}</a></div>";
                continue;
            }
            
            
            $classList .= "<div class='class-item'><a class='class-link' href='./classes.php?class={$class['class_id']}'>{$class['class_desc']}</a></div>";
                            //do with function 
            
           
        }
    }
    else
    {
        $classList .= "none exist create one"; //button linking
        
    }
    //create page here 
    echo pageStart("chat home", "./chat_styles/chat_style.css");
    echo createNav();
    echo createBanner(); 
    echo "<main>";
?>
  

    <div id="chat-page-wrapper">
    <!--nav and shit go here-->
        <div id="chat-content-wrapper"> <!--only for the main page content not borders and shit-->
            <!--the side pannel and the other thing will go here-->
            <div id="contacts-wrapper">
                <!-- generate this with php and set a custom attribute to the id value then get 
                in the js below with document..getAttribute();-->
                <?php
                    echo $classList;
                ?>
            </div> <!-- end of contacts wrapper -->
            <div id="class-details-wrapper"> 
                <!--<div id="messages-container">-->
                <!--</div>end of message-container-->

               <?php 
                    echo $classDeets;
               ?>
            </div><!--end of chat-wrapper-->
        </div> <!--end of page-wrapper  full page shit -->

<?php
    echo PageEnd();
    function showClassDetails($class, $count)
    {
        $classDetails = "
            <div id='expanded-class-details'>
                <div id='class-details'>
                    <span>{$class['subject_name']}</span>
                    <span>class size:{$count}</span>
                </div>
                <div id='class-title'>
                    <h2>{$class['class_desc']}</h2>
                </div>
                    <div id='class-options-wrapper'>
                        <a class='class-option' href='./index.php?class={$class['class_id']}'><span >start class chat</span></a>
                        <a class='class-option' href=''><span >manage class details</span></a>
                        <a class='class-option' href=''><span >manage class register</span></a>
                        <a class='class-option' href=''><span >Create a new class</span></a>
                    </div>
            </div>
        ";
        return $classDetails;
    }
?>



