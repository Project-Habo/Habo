<?php 
/*
Used for inserting records to objectives table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');

// Variable initialization
$error_array = array();
// $activities = array();

if(isset($_POST['edit_profile_info_button'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $year_of_birth = $_POST['year_of_birth'];
    $month_of_birth = $_POST['month_of_birth'];
    $day_of_birth = $_POST['day_of_birth'];
    $gender = $_POST['gender'];


    $profile_obj = new Profile($db_conn);

    $profile_obj->edit_profile($first_name, 
                                $last_name, 
                                $year_of_birth,
                                $month_of_birth,
                                $day_of_birth,
                                $gender,
                                $logged_in_user_id);

                                
    header('Location: profile.php?profile_username=' . $logged_in_user_username);
}
?>