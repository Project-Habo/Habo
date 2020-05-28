<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/add_activity_form_handler.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/form_handlers/create_objective_form_handler.php');


########## DEBUGGING ##########
echo "DEBUGGING:<br>";
print_r($user);
echo "<br>";
print_r($profile);
echo "<br><br>";
###############################
?>

    <div class="container">

        <div class="row" style="padding: 0px;">
            <div class="col-12 text-center col-lg-2" id="profile-pic-col">
                <a href="<?php echo $user['username']; ?>">
                    <img class="rounded img-fluid" src="<?php echo $profile['profile_pic']; ?>" width="120" height="120" alt="Profile picture" style="padding-top: 21px;">
                </a>
            </div>
            <div class="col-12 pt-2 col-lg-4" id="user-info-col">
                <a href="<?php echo $user['username']; ?>">
                    <h3><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h3>
                </a>
                <ul class="list-unstyled">
                    <li><strong>Level</strong>:</li>
                    <li><strong>Points</strong>:</li>
                    <li><strong>Coins</strong>:</li>
                </ul>
            </div>
            <div class="col-12 col-lg-5 offset-lg-1 mt-2 mt-lg-0" id="groups-events-col">
                <h5>Groups &amp; Events</h5>
                <div class="list-group" id="groups-events-list">
                    <a class="list-group-item list-group-item-action"><span>List Group Item 1</span></a>
                    <a class="list-group-item list-group-item-action"><span>List Group Item 2</span></a>
                    <a class="list-group-item list-group-item-action"><span>List Group Item 3</span></a>
                    <a class="list-group-item list-group-item-action"><span>List Group Item 1</span></a>
                    <a class="list-group-item list-group-item-action"><span>List Group Item 2</span></a>
                    <a class="list-group-item list-group-item-action"><span>List Group Item 3</span></a>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <!-- Side menu column -->
            <!-- <div class="col-12 col-lg-2 mt-lg-0" id="side-menu-col">
                <div class="row">
                    <div class="col" style="padding-top: 5px;">
                        <ul class="list-unstyled">
                            <li>&nbsp;<a href="#" style="text-decoration: none; color: #333;"><i class="fa fa-user" style="margin-left: 2px;"></i><span style="font-size: small;">&nbsp; Profile</span></a></li>
                            <li>&nbsp;<a href="#" style="text-decoration: none; color: #333;"><i class="fa fa-envelope"></i><span style="font-size: small;">&nbsp;Messages</span></a></li>
                            <li>&nbsp;<a href="#" style="text-decoration: none; color: #333;"><i class="fa fa-shopping-basket" style="margin-left: -2px;"></i><span style="font-size: small;">&nbsp;Marketplace</span></a></li>
                            <li>&nbsp;<a href="#" style="text-decoration: none; color: #333;"><i class="fa fa-calendar"></i><span style="font-size: small;">&nbsp;Events</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p style="font-size: small;color: #888;float: left;margin-bottom: 4px;">
                            <strong>Groups</strong>
                        </p>
                        <a href="#">
                            <p style="font-size: 11px;; float: right;margin-bottom: 3px;">
                                Find more
                            </p>
                        </a>
                    </div>
                    <div class="col-12">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#" style="text-decoration: none; color: #333; font-size:small">
                                    <i class="fa fa-group"></i>&nbsp;Group name
                                </a>
                            </li>
                            <li>
                                <a href="#" style="text-decoration: none; color: #333; font-size:small">
                                    <i class="fa fa-group"></i>&nbsp;Group name
                                </a>
                            </li>
                            <li>
                                <a href="#" style="text-decoration: none; color: #333; font-size:small">
                                    <i class="fa fa-group"></i>&nbsp;Group name
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> End side menu column -->
            <!-- Objectives and Activities column -->
            <div class="col-12 mt-2 col-lg-12 mt-lg-0 mx-lg-auto" id="objectives-activities-col">
                <div>
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" data-toggle="pill" href="#objectives-tab">Objectives</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-toggle="pill" href="#tab-2">Activity Log</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" id="objectives-tab">
                            <div class="row">
                                <div class="col">
                                    <div class="row" style="margin-top: 5px;">
                                        <div class="col-6 text-center col-md-6">
                                            <!-- Button trigger modal -->
                                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#createObjectiveModal">Create an objective</button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="createObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="createObjectiveModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="createObjectiveModalLabel">Create an objective</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="index.php" method="POST">
                                                            <div class="modal-body">
                                                                <label for="">Objective name</label>
                                                                <input type="text" name="objective_name">
                                                                <br>
                                                                <label for="">Objective type</label>
                                                                <select name="objective_type">
                                                                    <option value="Daily">Daily</option>
                                                                    <option value="Monthly">Monthly</option>
                                                                    <option value="Yearly">Yearly</option>
                                                                </select>
                                                                <label for="">Goal</label>
                                                                <input type="text" name="objective_goal">
                                                                <br>
                                                                <label for="">Objective category</label>
                                                                <select name="objective_category">
                                                                    <option value="Category 1">Category 1</option>
                                                                    <option value="Category 2">Category 2</option>
                                                                    <option value="Sports">Sports</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="create_objective_button" class="btn btn-primary">Create objective</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 text-center mt-1 mt-lg-0">
                                            <button class="btn btn-info" type="button">View completed objectives</button>
                                        </div>
                                    </div>
                                    <?php 
                                        require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Objective.php');

                                        $activity_obj = new Objective($db_conn);

                                        $objectives = $activity_obj->fetch_objectives($user['id']);

                                        // foreach($objectives as $objective) {
                                        //     print_r($objective);
                                        //     echo '<br>';
                                        // }
                                    ?>
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <h4 class="text-center">Daily</h4>
                                            <div class="row">
                                                <div class="col" style="padding-left: 20px;padding-right: 20px;padding-bottom: 5px;">
                                                    <?php
                                                    foreach($objectives as $objective) {
                                                        if($objective['type'] == 'Daily') {
                                                            print_r($objective);
                                                            echo '<br>';
                                                        } 
                                                    } 
                                                    ?>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <div class="row mb-2">
                                                                <div class="col-10 align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col-2">
                                                                    
                                                                    <div class="dropdown my-dropdown">
                                                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-h float-right text-dark"></i>
                                                                        </a>

                                                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                                            <a class="dropdown-item" href="#">Action</a>
                                                                            <a class="dropdown-item" href="#">Another action</a>
                                                                            <a class="dropdown-item" href="#">Something else here</a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                              
                                                                <!-- <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div> -->
                                                            </div>
                                                            <div class="progress">
                                                                <div class="progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <h4 class="text-center">Monthly</h4>
                                            <div class="row">
                                                <div class="col" style="padding-left: 20px;padding-right: 20px;padding-bottom: 5px;">
                                                    <?php
                                                    foreach($objectives as $objective) {
                                                        if($objective['type'] == 'Monthly') {
                                                            print_r($objective);
                                                            echo '<br>';
                                                        } 
                                                    } 
                                                    ?>
                                                    <ul class="list-group">
                                                    <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <h4 class="text-center">Yearly</h4>
                                            <div class="row">
                                                <div class="col" style="padding-left: 20px;padding-right: 20px;padding-bottom: 5px;">
                                                    <?php
                                                    foreach($objectives as $objective) {
                                                        if($objective['type'] == 'Yearly') {
                                                            print_r($objective);
                                                            echo '<br>';
                                                        } 
                                                    } 
                                                    ?>
                                                    <ul class="list-group">
                                                    <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <div class="col align-self-center"><span>List Group Item 1&nbsp;<br></span></div>
                                                                <div class="col align-self-center">
                                                                    <div class="btn-group btn-group-sm float-right" role="group">
                                                                        <button class="btn btn-success" type="button">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" type="button">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="tab-2">
                            <!-- Button trigger modal -->
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#addActivityModal">Add activity</button>
                            <?php 
                                require_once($_SERVER['DOCUMENT_ROOT'] . '/habo/includes/classes/Activity.php');

                                $activity_obj = new Activity($db_conn);

                                $activities = $activity_obj->fetch_user_activities($user['id']);

                                foreach($activities as $activity) {
                                    print_r($activity);
                                    echo '<br>';
                                }
                            ?>
                            <!-- Modal -->
                            <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="addActivityModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createObjectiveModalLabel">Add an activity</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="index.php" method="POST">
                                            <div class="modal-body">
                                                <?php
                                                    try {
                                                        // Prepare statement
                                                        $stmt = $db_conn->conn->prepare("
                                                            SELECT *
                                                            FROM activities
                                                        ");
                                                        // Execute statement
                                                        $stmt->execute();
                                                        
                                                        $activities = $stmt->fetchAll();
                                                    } catch (PDOException $e) {
                                                        echo 'Connection failed: ' . $e->getMessage();
                                                    }
                                                ?>
                                                <label for="">Select activity</label>
                                                <select name="activity" id="activities-dropdown">
                                                    <option>-- Activity --</option>
                                                    <?php foreach($activities as $activity) { ?>
                                                        <option value="<?php echo $activity['id']; ?>"><?php echo $activity['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="add_activity_button" class="btn btn-primary">Add activity</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Objectives and Activities column -->
        </div>

        <div class="row" style="margin-top: 10px;">
            <!-- Friend recomendations column -->
            <div class="col-12 col-lg-4 pt-2" id="friend-recommendations">
                <h5>People you might know</h5>
                <div class="row">
                    <div class="col col-lg-8 mb-2">
                        <a class="text-dark" href="#" style="text-decoration: none;">&nbsp;<img class="rounded-circle" width="30px" height="30px" src="https://dummyimage.com/30x30/52a6ff/0011ff">&nbsp;Firstname Lastname</a>
                    </div>
                    <div class="col col-lg-4">
                        <div class="btn-group btn-group-sm float-right" role="group">
                            <button class="btn btn-info" type="button" style="height: 25px;padding: 0px 8px;">Add</button>
                            <button class="btn btn-link" type="button" style="height: 25px;padding: 0px 8px;">Dimiss</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-lg-8 mb-2">
                        <a class="text-dark" href="#" style="text-decoration: none;">&nbsp;<img class="rounded-circle" width="30px" height="30px" src="https://dummyimage.com/30x30/52a6ff/0011ff">&nbsp;Firstname Lastname</a>
                    </div>
                    <div class="col col-lg-4">
                        <div class="btn-group btn-group-sm float-right" role="group">
                            <button class="btn btn-info" type="button" style="height: 25px;padding: 0px 8px;">Add</button>
                            <button class="btn btn-link" type="button" style="height: 25px;padding: 0px 8px;">Dimiss</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-lg-8 mb-2">
                        <a class="text-dark" href="#" style="text-decoration: none;">&nbsp;<img class="rounded-circle" width="30px" height="30px" src="https://dummyimage.com/30x30/52a6ff/0011ff">&nbsp;Firstname Lastname</a>
                    </div>
                    <div class="col col-lg-4">
                        <div class="btn-group btn-group-sm float-right" role="group">
                            <button class="btn btn-info" type="button" style="height: 25px;padding: 0px 8px;">Add</button>
                            <button class="btn btn-link" type="button" style="height: 25px;padding: 0px 8px;">Dimiss</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- New post column -->
            <div class="col-12 mt-2 col-lg-7 offset-lg-1 mt-lg-0 mx-lg-auto" id="home-new-post-col">
                <form style="margin-block-end: 0em;">
                    <div class="form-row">
                        <div class="col">
                            <label class="col-form-label">Create a post</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <textarea class="form-control" id="new-post-content"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col text-right">
                            <button class="btn btn-primary" id="publish-post-button" name="publish_post_button" type="button">Button</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" style="margin-top: 15px;">
            <!-- Advertisments column -->
            <div class="col-lg-4 pt-3 pb-2 d-none d-lg-block" id="advertisments-col">
                <div class="row text-center mb-2">
                    <div class="col"><img src="https://dummyimage.com/301x250/52a6ff/0011ff"></div>
                </div>
                <div class="row text-center mb-2">
                    <div class="col"><img src="https://dummyimage.com/301x250/52a6ff/0011ff"></div>
                </div>
            </div>
            <!-- Posts column -->
            <div class="col col-lg-7 offset-lg-1 mx-lg-auto" id="posts-col">
                <div class="row post mb-3">
                    <div class="col">
                        <div class="row post-head pt-1 pb-1">
                            <div class="col-auto"><img class="rounded-circle" src="https://dummyimage.com/50x50/52a6ff/0011ff" /></div>
                            <div class="col">
                                <div class="row">
                                    <div class="col" style="padding-left: 0px;"><a class="text-primary" href="#" style="text-decoration:none;"><strong>Firstname Lastname</strong></a></div>
                                </div>
                                <div class="row">
                                    <div class="col" style="padding-left: 0px;margin-top: -5px;"><span class="text-secondary" style="font-size:small;">1hr ago</span></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="float-right"><a class="mr-2" href="#" style="text-decoration: none;">Edit</a><a class="text-danger" href="#" style="text-decoration: none;">Delete</a></div>
                            </div>
                        </div>
                        <div class="row post-body">
                            <div class="col pt-2">
                                <p style="margin-bottom: 8px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam massa mauris, imperdiet id volutpat nec, ullamcorper rhoncus tortor. Cras interdum elit tellus, sed ullamcorper quam tincidunt eu. Interdum et malesuada fames ac ante ipsum
                                    primis in faucibus. Nunc quis nulla vehicula, tincidunt dolor sit amet, tincidunt lacus.<br /></p>
                            </div>
                        </div>
                        <div class="row post-actions">
                            <div class="col" style="padding-left: 20px;">
                                <a class="btn btn-link text-secondary" role="button"><i class="fa fa-thumbs-o-up"></i> Like (_)</a>
                                <a class="btn btn-link text-secondary" role="button" data-toggle="collapse" href="#commentCollapse" role="button" aria-expanded="false" aria-controls="commentCollapse"><i class="far fa-comment-alt"></i> Comment (_)</a>
                                <div class="collapse" id="commentCollapse">
                                    <div class="card card-body" style="border:none; padding: 0.5rem 0rem;">
                                        <!-- Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. -->
                                        <div class="row mb-2 comment">
                                            <div class="col-auto"><img class="rounded-circle" src="https://dummyimage.com/40x40/52a6ff/0011ff" width="40" height="40" /></div>
                                            <div class="col" style="padding-left: 0px;border-radius: 5px;background-color: #cce5f5;margin-right: 15px;"><a class="text-primary" href="#" style="text-decoration: none;padding-left: 10px;font-size: 16px;"><strong>Firstname Lastname</strong></a>
                                                <p style="margin: 0px;margin-bottom: 6px;padding-left: 10px;">Donec aliquet efficitur magna vitae varius. Nunc egestas erat odio, non fermentum ligula placerat non. <br /></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-auto"><img class="rounded-circle" src="https://dummyimage.com/40x40/52a6ff/0011ff" width="40" height="40" /></div>
                                            <div class="col" style="padding-left: 0px;border-radius: 5px;background-color: #cce5f5;margin-right: 15px;"><a class="text-primary" href="#" style="text-decoration: none;padding-left: 10px;font-size: 16px;"><strong>Firstname Lastname</strong></a>
                                                <p style="margin: 0px;margin-bottom: 6px;padding-left: 10px;">Donec aliquet efficitur magna vitae varius. Nunc egestas erat odio, non fermentum ligula placerat non. <br /></p>
                                            </div>
                                        </div>
                                        <div class="row" style="background-color: #fff; padding-top: 5px;padding-bottom: 5px;">
                                            <div class="col">
                                                <form style="background-color: #ecf0f1; padding:5px; border-radius: 5px; margin-block-end: 0em;">
                                                    <div class="form-row">
                                                        <div class="col"><textarea class="form-control form-control-sm" style="height: 31px;"></textarea></div>
                                                        <div class="col-auto text-center my-auto"><button class="btn btn-primary btn-sm" type="submit">Comment</button></div>
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
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- My scripts -->
    <script>
        $(document).ready(function() {
            var loggedInUserId = <?php echo $user['id']; ?>;
            var loggedInUserUsername = '<?php echo $user['username']; ?>';

            // Ajax call to load posts
            var ajaxReq = $.ajax({
                url: "includes/handlers/ajax_load_posts_handler.php",
                type: "POST",
                data: "logged_in_user_id=" + loggedInUserId + "&logged_in_user_username=" + loggedInUserUsername,
                success: function(data) {
                    $('#posts-col').html(data);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#publish-post-button').click(function() {
                var author = '<?php echo $user['username']; ?>'; // Username of logged in user
                var to = 'none'; // Post is published from index page so to is 'none'
                var body = $.trim($('#new-post-content').val());
                if(body != '') {
                    // console.log(author);
                    var ajaxReq = $.ajax({
                        url: 'includes/form_handlers/index_new_post_form_handler.php',
                        type: 'POST',
                        data: 'author=' + author + '&to=' + to + '&body=' + body,
                        success: function (data) {
                            // alert(response);
                            $('#new-post-content').val('');
                            // prepend new post
                            $('#posts-col').prepend(data)
                        }
                    });
                } else {
                    alert('Can\'t publish an empty post!');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on("click",".delete-post", function(e) {
                // prevent the default action, in this case the following of a link
                e.preventDefault();

                var deleteAnchorTag = $(this);
                var href = deleteAnchorTag.attr('href');

                var postId = deleteAnchorTag.attr('data-post-id')

                alert(href + ' ' + postId);

                var ajaxReq = $.ajax({
                    url: href,
                    type: 'POST',
                    data: 'post_id=' + postId,
                    success: function(response) {
                        deleteAnchorTag.closest('.post').remove();
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on("click", ".like-post-button", function(e) {
                // prevent the default action, in this case the following of a link
                e.preventDefault();

                var loggedInUserId = <?php echo $user['id']; ?>;
                var loggedInUserUsername = '<?php echo $user['username']; ?>';

                var likeAnchorTag = $(this);
                var href = likeAnchorTag.attr('href');

                var postId = likeAnchorTag.attr('data-post-id');
                
                alert(href + ' ' + postId);

                var ajaxReq = $.ajax({
                    url: href,
                    type: 'POST',
                    data: {
                        id: loggedInUserId,
                        username: loggedInUserUsername,
                        post_id: postId
                    },
                    success: function(data) {
                        likeAnchorTag.replaceWith(data);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.publish-comment-button', function() {
                var loggedInUserId = <?php echo $user['id']; ?>;
                var loggedInUserUsername = '<?php echo $user['username']; ?>';
                var postId = $(this).attr('data-post-id');
                var body = $.trim($('#post-' + postId + '-textarea').val());

                // DEBUGGING
                // console.log(loggedInUserId);
                // console.log(loggedInUserUsername);
                // console.log(postId);
                // console.log(body);

                var ajaxReq = $.ajax({
                    url: 'includes/form_handlers/ajax_post_comment_form_handler.php',
                    type: 'POST',
                    data: {
                        author_id: loggedInUserId,
                        author_username: loggedInUserUsername,
                        body: body,
                        post_id: postId
                    },
                    success: function(response) {
                        console.log(response);
                        $('#post-' + postId + '-textarea').val('');
                    }
                });
            });
        });
    </script>
</body>

</html>