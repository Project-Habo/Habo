<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Admin.php');

$user_id = $_GET['user_id'];
// echo $user_id; // DEBUGGING

//Create admin object
$admin_obj = new Admin($db_conn);

//Remove user
$admin_obj->suspend_user($user_id);

header("Location: ../../admin_test.php");
?>