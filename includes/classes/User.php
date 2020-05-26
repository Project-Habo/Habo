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
	
	/*
    Fetches a row from users table based on email value
    Arguments list:
        - $email
    Returns:
        - $user: Array containing users table row
    */
    public function fetch_user_by_email($email) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM users
                WHERE email=:email
            ");
            // Bind parameters
            $stmt->bindParam(':email', $email);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() > 0) { // If SELECT was successfull
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                return $user;
            } else {
                array_push($this->error_array, 'Unknown email address.');
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Fetches a row from users table based on username value
    Arguments list:
        - $username
    Returns:
        - $user: Array containing users table row
    */
    public function fetch_user_by_username($username) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM users
                WHERE username=:username
            ");
            // Bind parameters
            $stmt->bindParam(':username', $username);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() > 0) { // If SELECT was successfull
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                return $user;
            } else {
                array_push($this->error_array, 'Unknown username.');
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Check if user's account is closed
    Arguments list:
        - $id
    Returns:
        - true/false
    */
    public function is_closed($username) {
        $user = $this->fetch_user_by_username($username);
        if($user['user_closed'] == 'yes') {
            return true;
        } else {
            return false;
        }
    }
}