<?php 
class Objective
{
    private $db_conn; // Database connection variable
    private $objective_name;
    private $objective_type;
    private $objective_goal;
    private $objective_category;
    private $error_array = array();

    /*
    Constructor
    Arguments list:
    - $db_conn: database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    public function validate_objective_name($objective_name) {
        $objective_name = strip_tags($objective_name); // Strip HTML/PHP tags
        $objective_name = ucfirst(strtolower($objective_name)); // Make only first letter capital

        // First name should be between 2 and 50 characters long
        if(strlen($objective_name) > 50 || strlen($objective_name) < 3) {
            array_push($this->error_array, 'Your objective name must be between 3 and 50 characters.');
        } else {
            return $objective_name;
        }
    }

    public function validate_objective_goal($objective_goal) {
        $objective_goal = strip_tags($objective_goal); // Strip HTML/PHP tags

        // First name should be between 2 and 50 characters long
        if(strlen($objective_goal) > 10 || strlen($objective_goal) < 1) {
            array_push($this->error_array, 'Your objective goal must be between 1 and 10 characters.');
        } else {
            return $objective_goal;
        }
    }

    public function fetch_objectives($user_id) {
        $objectives = array();

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM objectives
                WHERE user_id=:user_id
            ");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Loop through each row
                array_push($objectives, $row);
            }

            return $objectives;
            // if($stmt->rowCount() == 1) { // If SELECT was successfull
            //     $objectives = $stmt->fetch(PDO::FETCH_ASSOC);
            //     return $objectives;
            // } else {
            //     array_push($this->error_array, 'Error!');
            // }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}
?>