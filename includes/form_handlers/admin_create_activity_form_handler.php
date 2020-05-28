<?php 
/*
Used for inserting records to objectives table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Admin.php');

// Variable initialization
$error_array = array();
// $activities = array();

if(isset($_POST['create_activity_button'])) {
    $activity_name = $_POST['activity_name'];
    $activity_category = $_POST['activity_category'];
    $activity_requirements = $_POST['activity_requirements'];
 

    $admin_obj = new Admin($db_conn);

    $admin_obj->create_activity($activity_name, 
                                $activity_category, 
                                $activity_requirements
                            );

    header('Location: admin_test.php');
}
?>