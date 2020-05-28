<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/register_form_handler.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-md sticky-top" id="top-navigation">
        <div class="container-fluid"><a class="navbar-brand" href="#">Habo</a>
            <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Register</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <!--Hidden column from md down-->
            <div class="col-5 text-center my-auto d-none d-md-block">
                <!--Placeholder-->
                <img class="img-fluid" src="https://dummyimage.com/400x500/eeeeee/000000&amp;text=Placeholder+Image"></div>
            <div class="col">
                <h3>Not a member? Join <strong>Habo</strong> below.</h3>
                <form id="register-form" action="register.php" method="POST">
                    <div class="form-row">
                        <div class="col-10 mx-auto col-lg-6">
                            <div class="form-group">
                                <label class="d-none d-lg-block">First Name</label>
                                <input class="form-control" name="first_name" type="text" placeholder="Enter first name..." value="<?php
                                    if(isset($_SESSION['first_name'])) {
                                        echo $_SESSION['first_name'];
                                    }
                                ?>" required="">
                                <?php
                                    if(in_array('Your first name must be between 2 and 25 characters.', $error_array)){
                                        echo "<span class='text-danger' style='font-size: small;'>Your first name must be between 2 and 25 characters.</span>";
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-10 mx-auto col-lg-6">
                            <div class="form-group">
                                <label class="d-none d-lg-block">Last Name</label>
                                <input class="form-control" name="last_name" type="text" placeholder="Enter last name..." value="<?php
                                    if(isset($_SESSION['last_name'])) {
                                        echo $_SESSION['last_name'];
                                    }
                                ?>" required="">
                                <?php
                                    if(in_array('Your last name must be between 2 and 25 characters.', $error_array)){
                                        echo "<span class='text-danger' style='font-size: small;'>Your last name must be between 2 and 25 characters.</span>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 mx-auto col-lg-12">
                            <div class="form-group">
                                <label class="d-none d-lg-block">Email address</label>
                                <input class="form-control" name="email" type="email" placeholder="Enter email address..." value="<?php
                                    if(isset($_SESSION['email'])) {
                                        echo $_SESSION['email'];
                                    }
                                ?>" required="">
                                <?php
                                    if(in_array("This is not a valid email address.", $error_array)){
                                        echo "<span class='text-danger' style='font-size: small;'>This is not a valid email address.</span>";
                                    }
                                    if(in_array("Email already in use.", $error_array)){
                                        echo "<span class='text-danger' style='font-size: small;'>Email already in use.</span>";
                                    } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 mx-auto col-lg-12">
                            <div class="form-group">
                                <label class="d-none d-lg-block">Password</label>
                                <input class="form-control" name="password" type="password" placeholder="Enter password..." required="">
                                <?php
                                    if(in_array('Your password must only contain english characters and numbers.', $error_array)) {
                                        echo "<span class='text-danger' style='font-size: small;'>Your password should only contain english characters or numbers.</span><br>";
                                    }
                                    if(in_array('Your password must be between 5 and 30 characters.', $error_array)) {
                                        echo "<span class='text-danger' style='font-size: small;'>Your password must be between 5 and 30 characters.</span><br>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 mx-auto col-lg-12">
                            <div class="form-group">
                                <label class="d-none d-lg-block">Confirm password</label>
                                <input class="form-control" name="confirm_password" type="password" placeholder="Confirm password..." required="">
                                <?php
                                    if(in_array('Your passwords do not match.', $error_array)) {
                                        echo "<span class='text-danger' style='font-size: small;'>Your passwords do not match.</span><br>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 mx-auto col-lg-12">
                            <div class="form-group">
                                <label>Date of birth</label>
                                <div class="form-row">
                                    <div class="col-4">
                                        <select class="form-control" name="year_of_birth" required="">
                                            <optgroup label="Year">

                                                <?php
                                                    for($i = date('Y'); $i >= date('Y', strtotime('-100 years')); $i--) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>

                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="form-control" name="month_of_birth" required="">
                                            <optgroup label="Month">
                                                
                                                <?php
                                                    for($i = 1; $i <= 12; $i++) {
                                                        $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>

                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="form-control" name="day_of_birth" required="">
                                            <optgroup label="Day">
                                                
                                                <?php
                                                    for($i = 1; $i <= 31; $i++) {
                                                        $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>

                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 mx-auto col-lg-12">
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="gender" type="radio" value="Female" required="">
                                            <label class="form-check-label">Female</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="gender" type="radio" value="Male" required="">
                                            <label class="form-check-label">Male</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col text-center">
                            <button class="btn btn-success" name="register_form_button" id="register-button" type="submit">Register</button>
                        </div>
                    </div>
                    <div class="form-row">
                    <?php
                        if(in_array('Registration complete.', $error_array)){
                            echo "<div class='col alert alert-success text-center' role='alert'>Registration successful. Go ahead and <a href='login.php' class='alert-link'>login</a>.</div>";
                        }
                    ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>