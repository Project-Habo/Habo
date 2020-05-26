<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');

// Variable initialization
$error_array = array();

if(isset($_POST['register_form_button'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $year_of_birth = $_POST['year_of_birth'];
    $month_of_birth = $_POST['month_of_birth'];
    $day_of_birth = $_POST['day_of_birth'];
    $gender = $_POST['gender'];

    // Create new user object
    $user_obj = new User($db_conn);

    $user_obj->register_user($first_name, $last_name, $email, $password, $confirm_password);

    // Create session variables
    $_SESSION['first_name'] = $user_obj->get_first_name();
    $_SESSION['last_name'] = $user_obj->get_last_name();
    $_SESSION['email'] = $user_obj->get_email();

    // Get error_array from User class
    $error_array = $user_obj->get_error_array();

    if(in_array('Registration complete.', $error_array)) {
        // Create profile upon successfull registration
        // Create new profile object
        $profile_obj = new Profile($db_conn);

        $profile_obj->create_profile($_SESSION['email'], $year_of_birth, $month_of_birth, $day_of_birth, $gender);

        // Clear session variables
        $_SESSION['first_name'] = '';
        $_SESSION['last_name'] = '';
        $_SESSION['email'] = '';
    }
}
?>