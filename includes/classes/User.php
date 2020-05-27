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
    Fetches all rows from users table
    Returns:
        - $users: Array containing users table rows
    */
    public function fetch_all_users() {
        $users = array();

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM users
            ");
            // Execute statement
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Loop through each row
                array_push($users, $row);
            }
            return $users;
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


    /*
    Sanitizes and validates first name before registration
    Arguments list:
        - $first_name
    Returns:
        - $first_name
    */
    private function validate_first_name($first_name) {
        $first_name = strip_tags($first_name); // Strip HTML/PHP tags
        $first_name = str_replace(' ', '', $first_name); // Remove white spaces
        $first_name = ucfirst(strtolower($first_name)); // Make only first letter capital

        // First name should be between 2 and 25 characters long
        if(strlen($first_name) > 25 || strlen($first_name) < 2) {
            array_push($this->error_array, 'Your first name must be between 2 and 25 characters.');
        } else {
            return $first_name;
        }
    }

    /*
    Sanitizes and validates last name before registration
    Arguments list:
        - $last_name
    Returns:
        - $last_name
    */
    private function validate_last_name($last_name) {
        $last_name = strip_tags($last_name); // Strip HTML/PHP tags
        $last_name = str_replace(' ', '', $last_name); // Remove white spaces
        $last_name = ucfirst(strtolower($last_name)); // Make only first letter capital

        // First name should be between 2 and 25 characters long
        if(strlen($last_name) > 25 || strlen($last_name) < 2) {
            array_push($this->error_array, 'Your last name must be between 2 and 25 characters.');
        } else {
            return $last_name;
        }
    }

    /*
    Sanitizes and validates email address before registration
    Arguments list:
        - $email
    Returns:
        - $email
    */
    private function validate_email($email) {
        $email = strip_tags($email); // Strip HTML/PHP tags
        $email = str_replace(' ', '', $email); // Remove white spaces
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Remove illegal characters

        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // If email address is valid
            try {
                // Check if email address already registered in database
                
                // Prepare statement
                $stmt = $this->db_conn->conn->prepare("
                    SELECT email
                    FROM users
                    WHERE email=:email
                ");
                // Bind parameters
                $stmt->bindParam(':email', $email);
                // Execute statement
                $stmt->execute();

                if($stmt->rowCount() > 0) { // Email already in database
                    array_push($this->error_array, 'Email already in use.');
                } else { // Email is not in database
                    return $email;
                }
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        } else {
            // If email is not valid
            array_push($this->error_array, 'This is not a valid email address.');
        }
    }   

    /*
    Validates password before registration
    Arguments list: 
        - $password, $confirm_password
    Returns:
        - $md5(password)
    */
    private function validate_password($password, $confirm_password) {
        if($password != $confirm_password) {
            array_push($this->error_array, 'Your passwords do not match.');
        } elseif(preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($this->error_array, 'Your password must only contain english characters and numbers.');
        } elseif(strlen($password) > 30 || strlen($password) < 5) {
            array_push($this->error_array, 'Your password must be between 5 and 30 characters.');
        } else {
            return md5($password);
        }
    }

    /*
    Based on the user's first and last name assigns a unique username
    Arguments list:
        - $first_name
        - $last_name
    Returns:
        - $username
    */
    private function assign_username($first_name, $last_name) {
        $username = strtolower($first_name . '_' . $last_name);

        try {
            // Check if username already exists in database

            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT username
                FROM users
                WHERE username=:username
            ");
            // Bind parameters
            $stmt->bindParam(':username', $username);
            // Execute statement
            $stmt->execute();

            $i = 0;
            while($stmt->rowCount() != 0) {
                // If username exists in database keep adding $i at the end.
                
                $i++;
                if (preg_match('~[0-9]+~', $username)) { // if there are numbers in username string
				    $username = preg_replace("/[0-9]/", $i, $username); // replace the number in the end with new $i
				}else{ // if no numbers in username string
					$username = $username . "_" . $i; // append $i(=1) at end of string
                }
                
                // Check if new username exists in database

                // Prepare statement
                // $stmt = $this->db_conn->conn->prepare("
                //     SELECT username
                //     FROM users
                //     WHERE username=:username
                // ");
                // Bind parameters
                $stmt->bindParam(':username', $username);
                // Execute statement
                $stmt->execute();
            }

            // When available username is found
            return $username;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    
    public function create_objective($objective_name, $objective_type, $objective_goal, $objective_category, $user_id) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Objective.php');

        // Create objective object
        $objective_obj = new Objective($this->db_conn);

        $objective_name = $objective_obj->validate_objective_name($objective_name);
        $objective_goal = $objective_obj->validate_objective_goal($objective_goal);

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                INSERT INTO objectives
                VALUES ('', :objective_name, :objective_type, :objective_goal, :objective_category, '0', 'no', :user_id)
            ");
            // Bind parameters
            $stmt->bindParam(':objective_name', $objective_name);
            $stmt->bindParam(':objective_type', $objective_type);
            $stmt->bindParam(':objective_goal', $objective_goal);
            $stmt->bindParam(':objective_category', $objective_category);
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $execute_stmt = $stmt->execute();

            // if($execute_stmt == true) { // If INSERT was successfull
            //     array_push($this->error_array, 'Registration complete.');
            // }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function add_activity($activity_id, $user_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                INSERT INTO activities_log
                VALUES ('', :activity_id, :user_id)
            ");
            // Bind parameters
            $stmt->bindParam(':activity_id', $activity_id);
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $execute_stmt = $stmt->execute();

            // if($execute_stmt == true) { // If INSERT was successfull
            //     array_push($this->error_array, 'Registration complete.');
            // }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}