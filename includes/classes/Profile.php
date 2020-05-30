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

    /*
    Fetches a row from profiles table based on user's id value
    Arguments list:
        - $user_id
    Returns:
        - $profile: Array containing profiles table row
    */
    public function fetch_profile_by_id($user_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM profiles
                WHERE user_id=:user_id
            ");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() > 0) { // If SELECT was successfull
                $profile = $stmt->fetch(PDO::FETCH_ASSOC);
                return $profile;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Assigns a random default profile pic to a user
    Returns:
        - $profile_pic: Contains path to picture file
    */
    private function assign_default_profile_pic() {
        $rand = rand(1, 6); // Random number between 1 and 4
		if($rand == 1) {
			$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
		} elseif ($rand == 2) {
			$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
		} elseif ($rand == 3) {
			$profile_pic = "assets/images/profile_pics/defaults/head_red.png";
		} elseif ($rand == 4) {
			$profile_pic = "assets/images/profile_pics/defaults/head_sun_flower.png";
		} elseif ($rand == 5) {
			$profile_pic = "assets/images/profile_pics/defaults/head_carrot.png";
		} elseif ($rand == 5) {
			$profile_pic = "assets/images/profile_pics/defaults/head_wisteria.png";
        }
        
        return $profile_pic;
    }

    /*
    Calculates user's age
    Arguments list:
        - $date_of_birth
    Returns:
        - $age: In years
    */
    private function calculate_age($date_of_birth) {
        $from = new DateTime($date_of_birth);
		$to = new DateTime('today');
        $age = $from->diff($to)->y;
        
        return $age;
    }

    /*
    Checks if logged in user is friend with another user
    Arguments list:
        - $logged_in_user
        - $user
    Returns:
        - true/false
    */
    public function is_friend($logged_in_user_id, $logged_in_user_username, $username) {
        $username_comma = ',' . $username . ','; // Search target

        $profile = $this->fetch_profile_by_id($logged_in_user_id);

        if (strstr($profile['friends_list'], $username_comma) || $logged_in_user_username == $username) {
			return true;
		} else {
			return false;
		}
    }
}
?>