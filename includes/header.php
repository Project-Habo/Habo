<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Profile.php');

if(isset($_SESSION['email']) && isset($_SESSION['username'])) { // User is logged in
    // Fetch user's info from users table
    $user_obj = new User($db_conn); // Create new user object
    $user = $user_obj->fetch_user_by_email($_SESSION['email']);// Stores user record info
    $logged_in_user_id = $user['id'];
    $logged_in_user_username = $user['username'];
    
    // Fetch user's info from profiles table
    $profile_obj = new Profile($db_conn); // Create new profile object
    $profile = $profile_obj->fetch_profile_by_id($user['id']);
} else { // User is not logged in
    header('Location: register.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <!-- Top navigation -->
    <nav class="navbar navbar-dark navbar-expand-lg sticky-top" id="top-navigation">
        <div class="container">
            <a class="navbar-brand" href="#">Habo</a>
            <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <!-- Navbar -->
            <div class="collapse navbar-collapse" id="navcol-1">
                <!-- Top search bar -->
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation">
                        <form class="form-inline" id="top-search-bar">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" type="text" placeholder="Search...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
                <!-- Top right navigation -->
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link top-navigation-link" href="<?php echo $user['username']; ?>" style="border-right: none;">&nbsp;<img class="rounded-circle" width="25px" height="25px" src="<?php echo $profile['profile_pic']; ?>">&nbsp;<?php echo $user['first_name']; ?></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active top-navigation-link" href="index.php" style="border-left: 1px solid #95a5a6;">Home</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link top-navigation-link" href="groups_test.php">Groups</a>
                    </li>
                    <!-- Notifications dropdown -->
                    <li class="nav-item" role="presentation" style="margin-left: 8px;">
                        <div class="nav-item dropdown my-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">
                                <i class="fa fa-bell"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a class="dropdown-item" role="presentation" href="#">First Item</a>
                                <a class="dropdown-item" role="presentation" href="#">Second Item</a>
                                <a class="dropdown-item" role="presentation" href="#">Third Item</a>
                            </div>
                        </div>
                    </li>
                    <!-- Messages dropdown -->
                    <li class="nav-item" role="presentation" style="margin-left: 8px;">
                        <div class="nav-item dropdown my-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">
                                <i class="fa fa-comments"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a class="dropdown-item" role="presentation" href="#">First Item</a>
                                <a class="dropdown-item" role="presentation" href="#">Second Item</a>
                                <a class="dropdown-item" role="presentation" href="#">Third Item</a>
                            </div>
                        </div>
                    </li>
                    <!-- Friend requests dropdown -->
                    <li class="nav-item" role="presentation" style="margin-left: 8px;">
                        <div class="nav-item dropdown my-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">
                                <i class="fa fa-users"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a class="dropdown-item" role="presentation" href="#">First Item</a>
                                <a class="dropdown-item" role="presentation" href="#">Second Item</a>
                                <a class="dropdown-item" role="presentation" href="#">Third Item</a>
                            </div>
                        </div>
                    </li>
                    <!-- Caret dropdown -->
                    <li class="nav-item" role="presentation" style="margin-left: 8px;">
                        <div class="nav-item dropdown my-dropdown" style="border-left: 1px solid #95a5a6;">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#" style="margin-left: 8px;">
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a class="dropdown-item" role="presentation" href="#">
                                <i class="fa fa-gear text-dark"></i> Settings
                            </a>
                            <div class="dropdown-divider" role="presentation"></div>
                            <a class="dropdown-item" role="presentation" href="includes/handlers/logout_handler.php">
                                <i class="fa fa-sign-out text-dark"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div> <!-- End navbar -->
        </div>
    </nav>