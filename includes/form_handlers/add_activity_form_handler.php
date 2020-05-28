<?php 
/*
Used for inserting records to activities table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');

// Variable initialization
$error_array = array();
// $activities = array();

if(isset($_POST['add_activity_button'])) {
    $activity_id = $_POST['activity'];

    // Create new user object
    $user_obj = new User($db_conn);

    $user_obj->add_activity($activity_id, $user['id']);

    
    // foreach($activities as $activity) {
    //     echo $activity['id'];
    // }

    header('Location: index.php');
}
?>