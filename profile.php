<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/edit_profile_info_form_handler.php');





// echo 'Profile of ' . $_GET['profile_username'];
// Check if user is closed
$closed = $user_obj->is_closed($_GET['profile_username']);
if ($closed) { // Show account closed message
?>

    <h3>Account of user <b><?php echo $_GET['profile_username']; ?></b> is unavailable.</h3>

<?php
} else { // show profile
    $user = $user_obj->fetch_user_by_username($_GET['profile_username']);
    $profile = $profile_obj->fetch_profile_by_id($user['id']);
    ########## DEBUGGING ##########
    echo "DEBUGGING:<br>";
    print_r($user);
    echo "<br>";
    print_r($profile);
    echo "<br><br>";
    ###############################
?>
    <div class="container">
        <div class="row">
            <div class="col">
                <img src="<?php echo $profile['profile_pic']; ?>" alt="">
                <h3><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h3>
                <ul>
                    <li>Date of birth: <?php echo $profile['date_of_birth']; ?></li>
                    <li>Age: <?php echo $profile['age']; ?></li>
                    <li>Gender: <?php echo $profile['gender']; ?></li>
                    <li>Number of posts: <?php echo $profile['num_posts']; ?></li>
                    <li>Number of likes: <?php echo $profile['num_likes']; ?></li>
                    <li>From: <?php echo $profile['from_country'] . ', ' . $profile['from_city']; ?></li>
                    <li>Lives in: <?php echo $profile['in_country'] . ', ' . $profile['in_city']; ?></li>
                </ul>
                <?php 
                if($_GET['profile_username'] == $logged_in_user_username){
                ?>
                <!-- Button trigger modal -->
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#editProfileInfoModal">Edit info</button>
                <!-- Modal -->
                <div class="modal fade" id="editProfileInfoModal" tabindex="-1" role="dialog" aria-labelledby="editProfileInfoModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProfileInfoModalLabel">Edit info</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="profile.php" method="POST">
                                <div class="modal-body">
                                    <label for="">First name</label>
                                    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>">
                                    <br>
                                    <label for="">Last name</label>
                                    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>">
                                    <br>
                                    <label for="">Gender</label>
                                    <select name="gender" required="">
                                        <optgroup label="Gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </optgroup>
                                    </select>
                                    <br>
                                    <label for="">Date of birth</label>
                                    <select name="year_of_birth" required="">
                                        <optgroup label="Year">

                                            <?php
                                                for($i = date('Y'); $i >= date('Y', strtotime('-100 years')); $i--) {
                                                    echo "<option value='$i'>$i</option>";
                                                }
                                            ?>

                                        </optgroup>
                                    </select>
                                    <select name="month_of_birth" required="">
                                        <optgroup label="Month">
                                            
                                            <?php
                                                for($i = 1; $i <= 12; $i++) {
                                                    $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                                                    echo "<option value='$i'>$i</option>";
                                                }
                                            ?>

                                        </optgroup>
                                    </select>
                                    <select name="day_of_birth" required="">
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="edit_profile_info_button" class="btn btn-primary">Edit info</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    
<?php
}
?>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>