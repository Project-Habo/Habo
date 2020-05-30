<?php
class Comment
{
    private $db_conn;
    private $error_array = array();

    /*
    Constructor
    Arguments list:
        - $db_conn: Database connection variable from config/config.php
    */
    function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    /*
    When this function is called it adds a record in the post_comments table
    Arguments list:
        - $body: Contains the content of the comment
        - $author_username: Logged in user's username
        - $author_id: Logged in user's id
        - $post_id: Commented post's id
    */
    public function insert_comment($body, $author_username, $author_id, $post_id) {
        // Remove html tags
        $body = strip_tags($body);

        // Date and time of comment creation
        $date_time_added = date("Y-m-d H:i:s");

        // INSERT comment
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                INSERT INTO post_comments
                VALUES ('', :body, :author, :date_added, :user_id, :post_id)
            ");
            // Bind parameters
            $stmt->bindParam(":body", $body);
            $stmt->bindParam(":author", $author_username);
            $stmt->bindParam(":date_added", $date_time_added);
            $stmt->bindParam(":user_id", $author_id);
            $stmt->bindParam(":post_id", $post_id);
            // Execute statement
            $execute_stmt = $stmt->execute();

            // Get last inserted id
            $last_id = $this->db_conn->conn->lastInsertId();

            if($execute_stmt == true) { // If INSERT was successfull
                array_push($this->error_array, 'Insert post was succesfull.');

                // UPDATE num_comments
                try {
                    // Prepare statement
                    $stmt = $this->db_conn->conn->prepare("
                        UPDATE posts
                        SET num_comments=num_comments+1
                        WHERE id=:post_id
                    ");
                    // Bind parameters
                    $stmt->bindParam(":post_id", $post_id);
                    // Execute statement
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo 'Connection failed: ' . $e->getMessage();
                }

                // Call function on last inserted id
                // return $this->load_single_comment($last_id);
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    When called, get and return the comments for specified post
    */
    public function load_comments($post_id) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');

        $str = ''; // String to return

        // SELECT all comments
        try {
            // Prepare statement
            $stmt1 = $this->db_conn->conn->prepare("
                SELECT *
                FROM post_comments
                WHERE post_id=:post_id
                ORDER BY date_added
            ");
            // Bind parameters
            $stmt1->bindParam(":post_id", $post_id);
            // Execute statement
            $stmt1->execute();

            // Count number of rows
            // Prepare statement
            $stmt2 = $this->db_conn->conn->prepare("
                SELECT COUNT(*)
                FROM post_comments
                WHERE post_id=:post_id
                ORDER BY date_added
            ");
            // Bind parameters
            $stmt2->bindParam(":post_id", $post_id);
            // Execute statement
            $stmt2->execute();
            $num_rows = $stmt2->fetchColumn(); //            <----------------------- CONDITION ----------------------------
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        if($num_rows > 0) {
            while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id']; // Comment id
                $body = $row['body']; // Comment body
                $author = $row['author']; // Comment author
                $date_time_added = $row['date_added'];
                $author_id = $row['user_id'];
                $post_id = $row['post_id'];

                $author_user_obj = new User($this->db_conn); // Create user object
                $author_profile_obj = new Profile($this->db_conn); // Create profile object

                $author_user_info = $author_user_obj->fetch_user_by_username($author);
                $author_profile_info = $author_profile_obj->fetch_profile_by_id($author_id);

                // Check if author's account is closed
                if($author_user_obj->is_closed($author)) {
                    continue; // Exit loop
                }

                //Timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($date_time_added); //Time of post
                $end_date = new DateTime($date_time_now); //Current time
                $interval = $start_date->diff($end_date); //Difference between dates 
                if($interval->y >= 1) {
                    if($interval == 1)
                        $time_message = $interval->y . " year ago"; //1 year ago
                    else 
                        $time_message = $interval->y . " years ago"; //1+ year ago
                } else if ($interval-> m >= 1) {
                    if($interval->d == 0) {
                        $days = " ago";
                    }
                    else if($interval->d == 1) {
                        $days = $interval->d . " day ago";
                    }
                    else {
                        $days = $interval->d . " days ago";
                    }
                    if($interval->m == 1) {
                        $time_message = $interval->m . " month". $days;
                    }
                    else {
                        $time_message = $interval->m . " months". $days;
                    }
                } else if($interval->d >= 1) {
                    if($interval->d == 1) {
                        $time_message = "Yesterday";
                    }
                    else {
                        $time_message = $interval->d . " days ago";
                    }
                } else if($interval->h >= 1) {
                    if($interval->h == 1) {
                        $time_message = $interval->h . " hour ago";
                    }
                    else {
                        $time_message = $interval->h . " hours ago";
                    }
                } else if($interval->i >= 1) {
                    if($interval->i == 1) {
                        $time_message = $interval->i . " minute ago";
                    }
                    else {
                        $time_message = $interval->i . " minutes ago";
                    }
                } else {
                    if($interval->s < 30) {
                        $time_message = "Just now";
                    }
                    else {
                        $time_message = $interval->s . " seconds ago";
                    }
                }

                $str .= '
                <div class="row mb-2 comment">
                    <div class="col-auto"><img class="rounded-circle" src="' . $author_profile_info['profile_pic'] . '" width="40" height="40" /></div>
                    <div class="col" style="padding-left: 0px;border-radius: 5px;background-color: #cce5f5;margin-right: 15px;"><a class="text-primary" href="#" style="text-decoration: none;padding-left: 10px;font-size: 16px;"><strong>' . $author_user_info['first_name'] . ' ' . $author_user_info['last_name'] . '</strong></a>
                        <p style="margin: 0px;margin-bottom: 6px;padding-left: 10px;">' . $body . '</p>
                    </div>
                </div>
                ';
            }
        }

        return $str;
    }
}
?>