<?php 
/*
Used for inserting records to posts table from the index page
create post form
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Post.php');

// Database connection variable
$db_conn = new Database();

$error_array = array();

// Create new Post object
$new_post_obj = new Post($db_conn);

$new_post = $new_post_obj->insert_post($_REQUEST['author'], $_REQUEST['to'], $_REQUEST['body']);

// Get error messages from insert_post method
$error_array = $new_post_obj->get_error_array();

if(in_array('Insert post was succesfull.', $error_array)) {
    // echo 'SUCCESS';
    // COMPLETED - TODO: prepend new post with last inserted id ???
    echo $new_post;
}
?>