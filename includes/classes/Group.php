<?php
class Group
{
    /*
    Constructor
    Arguments list:
    - $db_conn: database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    public function validate_group_name($group_name) {
        $group_name = strip_tags($group_name); // Strip HTML/PHP tags
        $group_name = ucfirst(strtolower($group_name)); // Make only first letter capital

        // First name should be between 2 and 50 characters long
        if(strlen($group_name) > 50 || strlen($group_name) < 3) {
            array_push($this->error_array, 'Your group name must be between 3 and 50 characters.');
        } else {
            return $group_name;
        }
    }

    public function fetch_all_groups() {
        $groups = array();

        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM groups
            ");
            // Execute statement
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Loop through each row
                array_push($groups, $row);
            }

            return $groups;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
?>