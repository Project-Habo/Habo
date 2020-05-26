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
    
    /* Getter Methods */
    public function get_first_name() { return $this->first_name; }
    public function get_last_name() { return $this->last_name; }
    public function get_email() { return $this->email; }
    public function get_error_array() { return $this->error_array; }

    /*
    Handles user registration
    Arguments list:
    - $first_name
    - $last_name
    - $email
    - $password
    - $confirm_password
    */
    public function register_user($first_name, $last_name, $email, $password, $confirm_password) {
        $this->first_name = $this->validate_first_name($first_name);
        $this->last_name = $this->validate_last_name($last_name);
        $this->email = $this->validate_email($email);
        $password_encrypted = $this->validate_password($password, $confirm_password);
        $username = $this->assign_username($first_name, $last_name);
        $signup_date = date('Y-m-d'); // Example: 1996/07/26

        // If errors array is empty INSERT info to users table
        if(empty($this->error_array)) {
            try {
                // Prepare statement
                $stmt = $this->db_conn->conn->prepare("
                    INSERT INTO users
                    VALUES ('', :first_name, :last_name, :email, :password_encrypted, :username, :signup_date, 'no', 'no', 'no', 'no')
                ");
                // Bind parameters
                $stmt->bindParam(':first_name', $this->first_name);
                $stmt->bindParam(':last_name', $this->last_name);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':password_encrypted', $password_encrypted);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':signup_date', $signup_date);
                // Execute statement
                $execute_stmt = $stmt->execute();

                if($execute_stmt == true) { // If INSERT was successfull
                    array_push($this->error_array, 'Registration complete.');
                }
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }

    /*
    Handles user login
    Arguments list:
        - $email
        - $password
    */
    public function login_user($email, $password) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM users
                WHERE email=:email AND password=:password
            ");
            // Bind parameters
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() == 1) { // If SELECT was successfull
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                return $user;
            } else {
                array_push($this->error_array, 'Email or password was incorrect.');
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
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