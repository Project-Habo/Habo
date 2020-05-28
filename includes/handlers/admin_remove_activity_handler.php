<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Admin.php');

$activity_id = $_GET['activity_id'];
// echo $user_id; // DEBUGGING

//Create admin object
$admin_obj = new Admin($db_conn);

//Remove user
$admin_obj->remove_activity($activity_id);

header("Location: ../../admin_test.php");
?>