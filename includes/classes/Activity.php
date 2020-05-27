<?php
class Activity
{
    private $db_conn; // Database connection variable
	private $activity_name;
	private $category;
	private $goal;

	/*
    Constructor
    Arguments list:
    - $db_conn: database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    /*
    TODO: Add comment
    */
    public function fetch_activity_by_id($activity_id) {
    	try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM activities
                WHERE activity_id=:activity_id
            ");
            // Bind parameters
            $stmt->bindParam(':activity_id', $activity_id);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() == 1) { // If SELECT was successfull
                $activity = $stmt->fetch(PDO::FETCH_ASSOC);
                return $activity;
            } else {
                array_push($this->error_array, 'Error!');
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function fetch_user_activities($user_id) {
        $activities = array(); // Array initialization

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM activities_log
                WHERE user_id=:user_id
            ");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Loop through each row
                array_push($activities, $row);
            }
            return $activities;
            // if($stmt->rowCount() > 0) { // If SELECT was successfull
            //     $activities = $stmt->fetch(PDO::FETCH_ASSOC);
            //     
            // }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function fetch_all_activities() {
        $activities = array(); // Array initialization

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM activities
            ");
            // Execute statement
            $stmt->execute();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Loop through each row
                array_push($activities, $row);
            }
            return $activities;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function validate_activity_name($activity_name) {
        $activity_name = strip_tags($activity_name); // Strip HTML/PHP tags
        $activity_name = ucfirst(strtolower($activity_name)); // Make only first letter capital

        // First name should be between 2 and 50 characters long
        if(strlen($activity_name) > 50 || strlen($activity_name) < 3) {
            array_push($this->error_array, 'Activity name must be between 3 and 50 characters.');
        } else {
            return $activity_name;
        }
    }

    public function validate_activity_requirements($activity_requirements) {
        $activity_requirements = strip_tags($activity_requirements); // Strip HTML/PHP tags

        // First name should be between 2 and 50 characters long
        if(strlen($activity_requirements) > 10 || strlen($activity_requirements) < 1) {
            array_push($this->error_array, 'Activity requirements must be between 1 and 10 characters.');
        } else {
            return $activity_requirements;
        }
    }
}
?>