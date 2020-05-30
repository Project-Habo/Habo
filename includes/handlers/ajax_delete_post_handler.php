<?php
/*
Used for deleting records from posts table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Post.php');

// Database connection variable
$db_conn = new Database();

// Create post object
$post_obj = new Post($db_conn);

$post_obj->delete_post($_REQUEST['post_id']);
?>