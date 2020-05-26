<?php
/*
    To be included in every page.
    Starts session and initiates database connection.
*/
ob_start(); // Turn on output buffer
session_start(); // Start session

// Create database connection
// $_SERVER['DOCUMENT_ROOT'] is equal to 'C:/xampp/htdocs'
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Database.php');
$db_conn = new Database();
?>