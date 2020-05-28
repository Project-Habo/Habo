<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Activity.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/admin_create_activity_form_handler.php');

// Create user object
$user_obj = new User($db_conn);
$users = $user_obj->fetch_all_users();

// Create user object
$activity_obj = new Activity($db_conn);
$activities = $activity_obj->fetch_all_activities();
// foreach($users as $user) {
//     print_r($user);
//     echo '<br>';
// }
?>

    <h1>Admin Panel(Test)</h1>

    <h3>Users list</h3>
    <table>
        <tr>    
            <th>ID</th>
            <th>Username</th>
            <th>Premium</th>
            <th>Suspended</th>
            <th>Actions</th>
        </tr>
        <?php foreach($users as $user) { 
            $id = $user['id']; ?>
            <tr>
                <td><?php echo $user['id'] ?></td>
                <td><?php echo $user['username'] ?></td>
                <td><?php echo $user['user_premium'] ?></td>
                <td><?php echo $user['user_suspended'] ?></td>
                <td>
                    <?php echo "<a href='includes/handlers/admin_promote_user_handler.php?user_id=$id'>Promote</a>" ?>
                    <?php echo "<a href='includes/handlers/admin_suspend_user_handler.php?user_id=$id'>Suspend</a>" ?>
                    <?php echo "<a href='includes/handlers/admin_remove_user_handler.php?user_id=$id'>Remove</a>" ?>
                    
                </td>
            </tr>
        <?php } ?>                                                     
    </table>
    
    <br>
    <h3>Create a new activity</h3>
    <form action="admin_test.php" method="POST">
        <label for="">Activity name</label>
        <input type="text" name="activity_name">
        <br>
        <label for="">Activity category</label>
        <select name="activity_category">
            <option value="Category 1">Category 1</option>
            <option value="Category 2">Category 2</option>
            <option value="Sports">Sports</option>
        </select>
        <br>
        <label for="">Activity requirements</label>
        <input type="text" name="activity_requirements">
        <br>
        <button type="submit" name="create_activity_button" class="btn btn-primary">Create activity</button>
    </form>

    <h3>Activities list</h3>
    <table>
        <tr>    
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Requierments</th>
            <th>Actions</th>
        </tr>
        <?php foreach($activities as $activity) { 
            $id = $activity['id']; ?>
            <tr>
                <td><?php echo $activity['id']; ?></td>
                <td><?php echo $activity['name']; ?></td>
                <td><?php echo $activity['category']; ?></td>
                <td><?php echo $activity['requirements']; ?></td>
                <td>
                    <?php echo "<a href='includes/handlers/admin_remove_activity_handler.php?activity_id=$id'>Remove</a>" ?>
                </td>
            </tr>
        <?php } ?>                                                     
    </table>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>