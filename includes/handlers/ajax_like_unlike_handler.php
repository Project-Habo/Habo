<?php
/*
Used for liking/unliking posts
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Post.php');

// Database connection variable
$db_conn = new Database();

// Create post object
$post_obj = new Post($db_conn);
$like_button = $post_obj->like_unlike_post($_REQUEST['id'], $_REQUEST['username'], $_REQUEST['post_id']);

echo $like_button;
?>