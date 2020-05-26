<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');

// Variable initialization
$error_array = array();

if(isset($_POST['login_form_button'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email address
    $password = md5($_POST['password']); // Get password in md5 form
    
    $_SESSION['email'] = $email; // Store email in session variable

    // Create new user object
    $user_obj = new User($db_conn);

    $user_obj->login_user($email, $password);

    // Get login errors if any
    $error_array = $user_obj->get_error_array();

    if(in_array('Email or password was incorrect.', $error_array) == false) { // No errors occurred during login
        // Fetch user's record from users table
        $user = $user_obj->fetch_user_by_email($_SESSION['email']);

        $_SESSION['username'] = $user['username']; // Store username in session variable

        // If user's account is not suspended procceed with log in
        if($user['user_suspended'] == 'no') {
            // If user's account is closed reopen it upon login
            if($user['user_closed'] == 'yes') {
                try {
                    // Prepare statement
                    $stmt = $db_conn->conn->prepare("
                        UPDATE users
                        SET user_closed='no'
                        WHERE id=:id
                    ");
                    // Bind parameters
                    $stmt->bindParam(':id', $user['id']);
                    // Execute statement
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo 'Connection failed: ' . $e->getMessage();
                }
            }

            header('Location: index.php');
            exit();
        } else { // User's account is suspended
            array_push($error_array, 'Your account has been suspended.');
    		$_SESSION['email'] = "";
        }

        
    }
}
?>