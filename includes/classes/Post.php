<?php
class Post
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

    /* Getter Methods */
    public function get_error_array() { return $this->error_array; }

    /*
    When this function is called it adds a record in the posts table
    Arguments list:
        - $author: Contains the username of the user who published the post
        - $to : Contains the username of the user this post is reffered to.
                If post is published from index page or from logged in user's
                profile, the $to is 'none'.
        - $body: The content of the post
    */
    public function insert_post($author, $to, $body) {
        if($author == $to) { // Statement is true when user publishes a post in his own profile
            $to = 'none';
        }

        // We need to get the author's id value using $author_username,
        // for the user_id column in posts table
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
        // Create User object
        $author_obj = new User($this->db_conn);
        // Fetch author info
        $author_info = $author_obj->fetch_user_by_username($author); // To get author's id use $author_info['id']

        // Remove html tags if any, from $body
        $body = strip_tags($body);
        
        $date_time_added = date("Y-m-d H:i:s"); // Date and time of post creation

        // INSERT post 
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                INSERT INTO posts
                VALUES ('', :body, :author, :to, :date_added, 0, 0, :user_id)
            ");
            // Bind parameters
            $stmt->bindParam(":body", $body);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":to", $to);
            $stmt->bindParam(":date_added", $date_time_added);
            $stmt->bindParam(":user_id", $author_info['id']);
            // Execute statement
            $execute_stmt = $stmt->execute();

            // Get last inserted id
            $last_id = $this->db_conn->conn->lastInsertId();
            // echo 'DEBUGGING: last inserted post id is ' . $last_id;

            if($execute_stmt == true) { // If INSERT was successfull
                array_push($this->error_array, 'Insert post was succesfull.');

                // UPDATE num_posts
                try {
                    // Prepare statement
                    $stmt = $this->db_conn->conn->prepare("
                        UPDATE profiles
                        SET num_posts=num_posts+1
                        WHERE user_id=:user_id
                    ");
                    // Bind parameters
                    $stmt->bindParam(":user_id", $author_info['id']);
                    // Execute statement
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo 'Connection failed: ' . $e->getMessage();
                }

                // Call function on last inserted id
                return $this->load_single_post($last_id);
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

    }

    /*
    Deletes a record from posts table
    Arguments list:
        - $id: Id value of post to be removed
    */
    public function delete_post($id) {
        $post_info = $this->fetch_post_by_id($id);
        
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                DELETE FROM posts
                WHERE id=:id
            ");
            // Bind parameters
            $stmt->bindParam(":id", $id);
            // Execute statement
            $execute_stmt = $stmt->execute();

            if($execute_stmt == true) { // If DELETE was successfull
                array_push($this->error_array, 'Delete post was succesfull.');

                require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');
                $profile_obj = new Profile($this->db_conn);
                $profile_info = $profile_obj->fetch_profile_by_id($post_info['user_id']);

                // UPDATE num_posts
                try {
                    // Prepare statement
                    $stmt = $this->db_conn->conn->prepare("
                        UPDATE profiles
                        SET num_posts=num_posts-1
                        WHERE user_id=:user_id
                    ");
                    // Bind parameters
                    $stmt->bindParam(":user_id", $profile_info['user_id']);
                    // Execute statement
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo 'Connection failed: ' . $e->getMessage();
                }
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Fetches a row from posts table based on post id value
    Arguments list:
        - $id: Id value of the post
    Returns:
        - post: Array containing post record
    */
    public function fetch_post_by_id($id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT * 
                FROM posts
                WHERE id=:id
            ");
            // Bind parameters
            $stmt->bindParam(":id", $id);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() > 0) { // If SELECT was successfull
                $post = $stmt->fetch(PDO::FETCH_ASSOC);
                return $post;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Adds/Removes record from post_likes table for logged in user
    Arguments list:
        - $id: Logged in user's id
        - $username: Logged in user's username
        - $post_id: Liked post's id
    */
    public function like_unlike_post($id, $username, $post_id) {
        try {
            // First find if logged in user likes specified post
            
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM post_likes
                WHERE user_id=:id AND post_id=:post_id
            ");
            // Bind parameters
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":post_id", $post_id);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() == 1) { // User already likes the post
                // Unlike post - DELETE
                
                // Prepare statement
                $stmt = $this->db_conn->conn->prepare("
                    DELETE FROM post_likes
                    WHERE user_id=:id AND post_id=:post_id
                ");
                // Bind parameters
                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":post_id", $post_id);
                // Execute statement
                $stmt->execute();

                // UPDATE num_likes of posts table

                // Prepare statement
                $stmt = $this->db_conn->conn->prepare("
                    UPDATE posts
                    SET num_likes=num_likes-1
                    WHERE id=:post_id
                ");
                // Bind parameters
                $stmt->bindParam(":post_id", $post_id);
                // Execute statement
                $stmt->execute();

                $post_info = $this->fetch_post_by_id($post_id);

                return '<a class="btn btn-link text-secondary like-post-button" href="includes/handlers/ajax_like_unlike_handler.php" data-post-id="' . $post_id . '" role="button" style="text-decoration:none;"><i class="fa fa-thumbs-o-up"></i> Like (' . $post_info['num_likes'] . ')</a>';
            } else { // User doesn't already like the post
                // Like post

                // Prepare statement
                $stmt = $this->db_conn->conn->prepare("
                    INSERT INTO post_likes
                    VALUES ('', :username, :id, :post_id)
                ");
                // Bind parameters
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":post_id", $post_id);
                // Execute statement
                $stmt->execute();

                // UPDATE num_likes of posts table

                // Prepare statement
                $stmt = $this->db_conn->conn->prepare("
                    UPDATE posts
                    SET num_likes=num_likes+1
                    WHERE id=:post_id
                ");
                // Bind parameters
                $stmt->bindParam(":post_id", $post_id);
                // Execute statement
                $stmt->execute();

                $post_info = $this->fetch_post_by_id($post_id);

                return '<a class="btn btn-link text-primary like-post-button" href="includes/handlers/ajax_like_unlike_handler.php" data-post-id="' . $post_id . '" role="button" style="text-decoration:none;"><i class="fa fa-thumbs-up"></i> Like (' . $post_info['num_likes'] . ')</a>';
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Checks if logged in user likes a specific post
    Arguments list:
        - $user_id: Id value of logged in user
        - $post_id: Id value of post to be checked
    Returns:
        - true -> logged in user already likes the post
        - false -> logged in user doesn't like the post
    */
    private function is_liked_by($user_id, $post_id) {
        try {
            // Prepare statement
            $stmt = $this->db_conn->conn->prepare("
                SELECT *
                FROM post_likes
                WHERE user_id=:user_id AND post_id=:post_id
            ");
            // Bind parameters
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":post_id", $post_id);
            // Execute statement
            $stmt->execute();

            if($stmt->rowCount() == 1) { // User already likes the post
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
    Loads a single post when logged in user publishes a new one
    Arguments list:
        - $id: Id value of the post
    */
    public function load_single_post($id) {
        $str = ''; // String to return

        // Fetch post info
        $post_info = $this->fetch_post_by_id($id);

        $id = $post_info['id'];
        $body = $post_info['body'];
        $author = $post_info['author'];
        $to = $post_info['to'];
        $date_time = $post_info['date_added'];
        $num_likes = $post_info['num_likes'];
        $num_comments = $post_info['num_comments'];
        $user_id = $post_info['user_id'];

        if($to == 'none') {
            $to_string = '';
        } else {
            $to_obj = new User($this->db_conn);
            $to_info = $to_obj->fetch_user_by_username($to);

            $to_name = $to_info['first_name'] . ' ' . $to_info['last_name'];
            $to_string = 'to <a class="text-primary" href="' . $to . '" style="text-decoration:none;"><strong>' . $to_name . '</strong></a>';
            // Ex. <a class="text-primary" href="#" style="text-decoration:none;"><strong>Firstname Lastname</strong></a>
        }

        //Timeframe
        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($date_time); //Time of post
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

        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');

        $author_user_obj = new User($this->db_conn);
        $author_profile_obj = new Profile($this->db_conn);

        $author_user_info = $author_user_obj->fetch_user_by_username($author);
        $author_profile_info = $author_profile_obj->fetch_profile_by_id($user_id);

        $str .= '
        <div class="row post mb-3">
            <div class="col">
                <div class="row post-head pt-1 pb-1">
                    <input type="text" readonly class="form-control-plaintext" value="' . $id . '">
                    <div class="col-auto"><img class="rounded-circle" src="' . $author_profile_info['profile_pic'] . '" width="50px" height="50px"/></div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col" style="padding-left: 0px;"><a class="text-primary" href="' . $author . '" style="text-decoration:none;"><strong>' . $author_user_info['first_name'] . ' ' . $author_user_info['last_name'] . '</strong></a> '. $to_string . '</div>
                        </div>
                        <div class="row">
                            <div class="col" style="padding-left: 0px;margin-top: -5px;"><span class="text-secondary" style="font-size:small;">' . $time_message . '</span></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="float-right"><a class="mr-2" href="#" style="text-decoration: none;">Edit</a><a class="text-danger delete-post" data-post-id="' . $id . '" href="includes/handlers/ajax_delete_post_handler.php" style="text-decoration: none;">Delete</a></div>
                    </div>
                </div>
                <div class="row post-body">
                    <div class="col pt-2">
                        <p style="margin-bottom: 8px;">
                            ' . $body . '
                        </p>
                    </div>
                </div>
                <div class="row post-actions">
                    <div class="col" style="padding-left: 20px;">
                        <a class="btn btn-link text-secondary like-post-button" href="includes/handlers/ajax_like_unlike_handler.php" data-post-id="' . $id . '" role="button" style="text-decoration:none;"><i class="fa fa-thumbs-o-up"></i> Like (' . $num_likes . ')</a>
                        <a class="btn btn-link text-secondary" role="button" data-toggle="collapse" href="#commentCollapse' . $id . '" role="button" aria-expanded="false" aria-controls="commentCollapse' . $id . '" style="text-decoration:none;"><i class="far fa-comment-alt"></i> Comment (' . $num_comments . ')</a>
                        <div class="collapse" id="commentCollapse' . $id . '">
                            <div class="card card-body" style="border:none; padding: 0.5rem 0rem;">
                                <div class="comment-section">
                                </div>
                                <div class="row" style="background-color: #fff; padding-top: 5px;padding-bottom: 5px;">
                                    <div class="col">
                                        <form style="background-color: #ecf0f1; padding:5px; border-radius: 5px; margin-block-end: 0em;">
                                            <div class="form-row">
                                                <div class="col"><textarea class="form-control form-control-sm" id="post-' . $id . '-textarea" style="height: 31px;"></textarea></div>
                                                <div class="col-auto text-center my-auto"><button class="btn btn-primary btn-sm publish-comment-button" data-post-id="' . $id . '" type="button">Comment</button></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        ';

        return $str;
    }

    /*
    TODO: Add description
    */
    public function load_posts($logged_in_user_id, $logged_in_user_username) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Comment.php');

        $str = ""; // Final string to return

        // SELECT all posts
        try {
            // Prepare statement
            $stmt1 = $this->db_conn->conn->prepare("
                SELECT *
                FROM posts
                ORDER BY date_added DESC
            ");
            // Execute statement
            $stmt1->execute();

            // Count number of rows
            // Prepare statement
            $stmt2 = $this->db_conn->conn->prepare("
                SELECT COUNT(*)
                FROM posts
                ORDER BY date_added DESC
            ");
            // Execute statement
            $stmt2->execute();
            $num_rows = $stmt2->fetchColumn(); //            <----------------------- CONDITION ----------------------------
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }   

        if($num_rows > 0) {
            while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) { // Loop through each row
                $id = $row['id'];
                $body = $row['body'];
                $author = $row['author'];
                $to = $row['to'];
                $date_time = $row['date_added'];
                $num_likes = $row['num_likes'];
                $num_comments = $row['num_comments'];
                $user_id = $row['user_id'];

                if($to == 'none') {
                    $to_string = '';
                } else {
                    $to_obj = new User($this->db_conn);
                    $to_info = $to_obj->fetch_user_by_username($to);

                    $to_name = $to_info['first_name'] . ' ' . $to_info['last_name'];
                    $to_string = 'to <a class="text-primary" href="' . $to . '" style="text-decoration:none;"><strong>' . $to_name . '</strong></a>';
                    // Ex. <a class="text-primary" href="#" style="text-decoration:none;"><strong>Firstname Lastname</strong></a>
                }
                
                $author_user_obj = new User($this->db_conn);
                // Check if author's account is closed
                if($author_user_obj->is_closed($author)) {
                    continue; // Exit loop
                }

                // Show only posts from friends
                $author_profile_obj = new Profile($this->db_conn);
                if($author_profile_obj->is_friend($logged_in_user_id, $logged_in_user_username, $author)) {
                    //Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
                    
                    $author_user_info = $author_user_obj->fetch_user_by_username($author);
                    $author_profile_info = $author_profile_obj->fetch_profile_by_id($user_id);

                    // Check if logged in user likes post and assign appropriate like button icon
                    if($this->is_liked_by($logged_in_user_id, $id)) {
                        $like_button = '<a class="btn btn-link text-primary like-post-button" href="includes/handlers/ajax_like_unlike_handler.php" data-post-id="' . $id . '" role="button" style="text-decoration:none;"><i class="fa fa-thumbs-up"></i> Like (' . $num_likes . ')</a>';
                    } else {
                        $like_button = '<a class="btn btn-link text-secondary like-post-button" href="includes/handlers/ajax_like_unlike_handler.php" data-post-id="' . $id . '" role="button" style="text-decoration:none;"><i class="fa fa-thumbs-o-up"></i> Like (' . $num_likes . ')</a>';
                    }

                    // Load post's comments
                    // Create comment object
                    $comment_obj = new Comment($this->db_conn);
                    $comments = $comment_obj->load_comments($id);

                    // COMPLETED - TODO: If logged in user is the author of a post, include 'Edit' and 'Delete' options, else don't
                    if($logged_in_user_username == $author) {
                        $str .= '
                        <div class="row post mb-3">
                            <div class="col">
                                <div class="row post-head pt-1 pb-1">
                                    <input type="text" readonly class="form-control-plaintext" value="' . $id . '">
                                    <div class="col-auto"><img class="rounded-circle" src="' . $author_profile_info['profile_pic'] . '" width="50px" height="50px"/></div>
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col" style="padding-left: 0px;"><a class="text-primary" href="' . $author . '" style="text-decoration:none;"><strong>' . $author_user_info['first_name'] . ' ' . $author_user_info['last_name'] . '</strong></a> '. $to_string . '</div>
                                        </div>
                                        <div class="row">
                                            <div class="col" style="padding-left: 0px;margin-top: -5px;"><span class="text-secondary" style="font-size:small;">' . $time_message . '</span></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="float-right"><a class="mr-2" href="#" style="text-decoration: none;">Edit</a><a class="text-danger delete-post" data-post-id="' . $id . '" href="includes/handlers/ajax_delete_post_handler.php" style="text-decoration: none;">Delete</a></div>
                                    </div>
                                </div>
                                <div class="row post-body">
                                    <div class="col pt-2">
                                        <p style="margin-bottom: 8px;">
                                            ' . $body . '
                                        </p>
                                    </div>
                                </div>
                                <div class="row post-actions">
                                    <div class="col" style="padding-left: 20px;">
                                        ' . $like_button . '
                                        <a class="btn btn-link text-secondary" role="button" data-toggle="collapse" href="#commentCollapse' . $id . '" role="button" aria-expanded="false" aria-controls="commentCollapse' . $id . '" style="text-decoration:none;><i class="far fa-comment-alt"></i> Comment (' . $num_comments . ')</a>
                                        <div class="collapse" id="commentCollapse' . $id . '">
                                            <div class="card card-body" style="border:none; padding: 0.5rem 0rem;">
                                                <div class="comment-section">
                                                    ' . $comments . '
                                                </div>
                                                <div class="row" style="background-color: #fff; padding-top: 5px;padding-bottom: 5px;">
                                                    <div class="col">
                                                        <form style="background-color: #ecf0f1; padding:5px; border-radius: 5px; margin-block-end: 0em;">
                                                            <div class="form-row">
                                                            <div class="col"><textarea class="form-control form-control-sm" id="post-' . $id . '-textarea" style="height: 31px;"></textarea></div>
                                                                <div class="col-auto text-center my-auto"><button class="btn btn-primary btn-sm publish-comment-button" data-post-id="' . $id . '" type="button">Comment</button></div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        ';  
                    } else {
                        $str .= '
                        <div class="row post mb-3">
                            <div class="col">
                                <div class="row post-head pt-1 pb-1">
                                    <input type="text" readonly class="form-control-plaintext" value="' . $id . '">
                                    <div class="col-auto"><img class="rounded-circle" src="' . $author_profile_info['profile_pic'] . '" width="50px" height="50px"/></div>
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col" style="padding-left: 0px;"><a class="text-primary" href="' . $author . '" style="text-decoration:none;"><strong>' . $author_user_info['first_name'] . ' ' . $author_user_info['last_name'] . '</strong></a> '. $to_string . '</div>
                                        </div>
                                        <div class="row">
                                            <div class="col" style="padding-left: 0px;margin-top: -5px;"><span class="text-secondary" style="font-size:small;">' . $time_message . '</span></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <!-- <div class="float-right"><a class="mr-2" href="#" style="text-decoration: none;">Edit</a><a class="text-danger delete-post" data-post-id="' . $id . '" href="includes/handlers/ajax_delete_post_handler.php" style="text-decoration: none;">Delete</a></div> -->
                                    </div>
                                </div>
                                <div class="row post-body">
                                    <div class="col pt-2">
                                        <p style="margin-bottom: 8px;">
                                            ' . $body . '
                                        </p>
                                    </div>
                                </div>
                                <div class="row post-actions">
                                    <div class="col" style="padding-left: 20px;">
                                        <!-- <a class="btn btn-link text-secondary like-post-button" href="includes/handlers/ajax_like_unlike_handler.php" data-post-id="' . $id . '" role="button" style="text-decoration:none;">' . $like_button . ' Like (' . $num_likes . ')</a> -->
                                        ' . $like_button . '
                                        <a class="btn btn-link text-secondary" role="button" data-toggle="collapse" href="#commentCollapse' . $id . '" role="button" aria-expanded="false" aria-controls="commentCollapse' . $id . '" style="text-decoration:none;"><i class="far fa-comment-alt"></i> Comment (' . $num_comments . ')</a>
                                        <div class="collapse" id="commentCollapse' . $id . '">
                                            <div class="card card-body" style="border:none; padding: 0.5rem 0rem;">
                                                <div class="comment-section">
                                                    ' . $comments . '
                                                </div>
                                                <div class="row" style="background-color: #fff; padding-top: 5px;padding-bottom: 5px;">
                                                    <div class="col">
                                                        <form style="background-color: #ecf0f1; padding:5px; border-radius: 5px; margin-block-end: 0em;">
                                                            <div class="form-row">
                                                            <div class="col"><textarea class="form-control form-control-sm" id="post-' . $id . '-textarea" style="height: 31px;"></textarea></div>
                                                                <div class="col-auto text-center my-auto"><button class="btn btn-primary btn-sm publish-comment-button" data-post-id="' . $id . '" type="button">Comment</button></div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        ';
                    }
                }
            }
        }

        return $str;
    }
}
?>