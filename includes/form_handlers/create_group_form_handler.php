<?php 
/*
Used for inserting records to objectives table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');

// Variable initialization
$error_array = array();
// $activities = array();

if(isset($_POST['create_group_button'])) {
    $group_name = $_POST['group_name'];
    $group_type = $_POST['group_type'];

    $user_obj = new User($db_conn);

    $user_obj->create_group($group_name, 
                            $group_type, 
                            $logged_in_user_username);

    header('Location: groups_test.php');
}
?>