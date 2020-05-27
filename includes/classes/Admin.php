<?php 
class Admin 
{
    private $db_conn; // Database connection variable
    private $email;
    private $password;

    /*
    Constructor
    Arguments list:
    - $db_conn: database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    /*

    */
    function remove_user($user_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                DELETE FROM users
                WHERE id=:user_id
            ");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*

    */
    function promote_user($user_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
            UPDATE users
            SET user_premium='yes'
            WHERE id=:user_id
            ");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*

    */
    function suspend_user($user_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
            UPDATE users
            SET user_suspended='yes'
            WHERE id=:user_id
            ");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function create_activity($activity_name, $activity_category, $activity_requirements) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Activity.php');

        // Create objective object
        $activity_obj = new Activity($this->db_conn);

        $activity_name = $activity_obj->validate_activity_name($activity_name);
        $activity_requirements = $activity_obj->validate_activity_requirements($activity_requirements);

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                INSERT INTO activities
                VALUES ('', :activity_name, :activity_category, :activity_requirements)
            ");
            // Bind parameters
            $stmt->bindParam(':activity_name', $activity_name);
            $stmt->bindParam(':activity_category', $activity_category);
            $stmt->bindParam(':activity_requirements', $activity_requirements);
            // Execute statement
            $execute_stmt = $stmt->execute();

            // if($execute_stmt == true) { // If INSERT was successfull
            //     array_push($this->error_array, 'Registration complete.');
            // }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    function remove_activity($activity_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                DELETE FROM activities
                WHERE id=:activity_id
            ");
            // Bind parameters
            $stmt->bindParam(':activity_id', $activity_id);
            // Execute statement
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
?>