<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Group.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/create_group_form_handler.php');

// Create group object
$group_obj = new Group($db_conn);
$groups = $group_obj->fetch_all_groups();
?>

    <h1>Groups(Test)</h1>

    <h3>Groups list</h3>
    <table>
        <tr>    
            <th>ID</th>
            <th>Group name</th>
            <th>Group type</th>
            <th>Group admin</th>
        </tr>
        <?php foreach($groups as $group) { 
            $id = $group['id']; ?>
            <tr>
                <td><?php echo $id ?></td>
                <td><?php echo $group['name'] ?></td>
                <td><?php echo $group['type'] ?></td>
                <td><?php echo $group['admin'] ?></td>
            </tr>
        <?php } ?>                                                     
    </table>
    
    <br>
    <h3>Create a new group</h3>
    <form action="groups_test.php" method="POST">
        <label for="">Group name</label>
        <input type="text" name="group_name">
        <br>
        <label for="">Group type</label>
        <select name="group_type">
            <option value="Public">Public</option>
            <option value="Private">Private</option>
        </select>
        <br>
        <button type="submit" name="create_group_button" class="btn btn-primary">Create group</button>
    </form>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>