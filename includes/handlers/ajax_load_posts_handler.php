<?php
/*
Used for asynchronously loading posts
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Post.php');

// Database connection variable
$db_conn = new Database();

// Create Post object
$post_obj = new Post($db_conn);
echo $post_obj->load_posts($_REQUEST['logged_in_user_id'], $_REQUEST['logged_in_user_username']);
?>