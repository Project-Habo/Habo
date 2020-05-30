<?php 
class Profile 
{
    private $db_conn;

    /*
    Constructor
    Arguments list:
        - $db_conn: database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    /*
    Upon user registration, creates a profile for that user.
    Arguments list:
        - $email
        - $year_of_birth
        - $month_of_birth
        - $day_of_birth
        - $gender
    */
    public function create_profile($email, $year_of_birth, $month_of_birth, $day_of_birth, $gender) {
        // To create new profile and link it with a user we need the id value from users table
        // To retrieve the id of the user, we need the user's email
        // Notes: 
        // - Could use lastInsertedID() PDO function ???
        // - Add is_protected column to profiles table for underage accounts ???
        
        $profile_pic = $this->assign_default_profile_pic();
        $date_of_birth = "$year_of_birth-$month_of_birth-$day_of_birth";
        $age = $this->calculate_age($date_of_birth);

        // Get user's id value
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT id 
                FROM users
                WHERE email=:email
            ");
            // Bind parameters
            $stmt->bindParam(':email', $email);
            // Execute statement
            $stmt->execute();
            // Fetch id
            $user_id = $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        // INSERT info to profiles table
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                INSERT INTO profiles
                VALUES ('', :profile_pic, :date_of_birth, :age, :gender, '', '', '', '', 0, 0, 0, ',', :user_id)
            ");
            // Bind parameters
            $stmt->bindParam(':profile_pic', $profile_pic);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function edit_profile($first_name, $last_name, $year_of_birth, $month_of_birth, $day_of_birth, $gender, $logged_in_user_id) {
        $date_of_birth = "$year_of_birth-$month_of_birth-$day_of_birth";
        $age = $this->calculate_age($date_of_birth);

        // UPDATE users table
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                UPDATE users
                SET first_name=:first_name, last_name=:last_name
                WHERE id=:logged_in_user_id
            ");
            // Bind parameters
            $stmt->bindParam(":logged_in_user_id", $logged_in_user_id);
            $stmt->bindParam(":first_name", $first_name);
            $stmt->bindParam(":last_name", $last_name);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        // UPDATE profiles table
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                UPDATE profiles
                SET age=:age, gender=:gender
                WHERE user_id=:logged_in_user_id
            ");
            // Bind parameters
            $stmt->bindParam(":logged_in_user_id", $logged_in_user_id);
            $stmt->bindParam(":age", $age);
            $stmt->bindParam(":gender", $gender);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}