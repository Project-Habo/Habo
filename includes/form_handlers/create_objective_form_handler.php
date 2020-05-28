<?php 
/*
Used for inserting records to objectives table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');

// Variable initialization
$error_array = array();
// $activities = array();

if(isset($_POST['create_objective_button'])) {
    $objective_name = $_POST['objective_name'];
    $objective_type = $_POST['objective_type'];
    $objective_goal = $_POST['objective_goal'];
    $objective_category = $_POST['objective_category'];

    $user_obj = new User($db_conn);

    $user_obj->create_objective($objective_name, 
                                $objective_type, 
                                $objective_goal,
                                $objective_category,
                                $user['id']);

    header('Location: index.php');
}
?>