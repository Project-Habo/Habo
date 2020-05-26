<?php
/*
*/

class User
{
    private $db_conn; // Database connection variable
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $error_array = array();

    /*
    Constructor
    Arguments list:
    - $db_conn: database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }
}