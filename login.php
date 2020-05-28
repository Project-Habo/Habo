<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/login_form_handler.php');
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
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#">Register</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto">
                <h3>Welcome back! Log in.</h3>
                <form id="login-form" action="login.php" method="POST">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Email address</label>
                                <input class="form-control" name="email" type="email" value="<?php
                                    if(isset($_SESSION['email'])) {
                                        echo $_SESSION['email'];
                                    }
                                ?>" placeholder="Enter email address...">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" name="password" type="password" placeholder="Enter password...">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col text-center">
                            <button class="btn btn-primary" name="login_form_button" type="submit">Log in</button>
                        </div>
                    </div>
                    <div class="form-row">
                    <?php
                        if(in_array('Email or password was incorrect.', $error_array)){
                            echo "<div class='col alert alert-danger text-center' role='alert'>Email or password was incorrect.</div>";
                        }
                        if(in_array('Your account has been suspended.', $error_array)){
                            echo "<div class='col alert alert-danger text-center' role='alert'>Your account has been suspended.</div>";
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