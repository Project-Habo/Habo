<?php 
/*
Used for inserting records to post_comments table
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Comment.php');

// Database connection variable
$db_conn = new Database();

// Create Comment object
$comment_obj = new Comment($db_conn);
$comment_obj->insert_comment($_REQUEST['body'], $_REQUEST['author_username'], $_REQUEST['author_id'], $_REQUEST['post_id']);

// echo $comment;
?>