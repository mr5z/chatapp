<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-12 col-md-12 top-15">
<?php

require_once('api/api.php');

$userId = post('userId');

$sql = "SELECT * FROM users WHERE id=$userId";

$result = query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_object();
?>
    <div class="alert alert-success alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
        Welcome back <strong><?php echo $user->firstName; ?></strong>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#menu-contacts">Contacts</a></li>
        <li><a data-toggle="tab" href="#menu-rooms">Rooms</a></li>
        <li><a data-toggle="tab" href="#menu-profile">My Profile</a></li>
    </ul>
    <div class="tab-content">
        <div id="menu-contacts" class="tab-pane fade in active">
            <?php include('pages/home-contacts.php'); ?>
        </div>
        <div id="menu-rooms" class="tab-pane fade">
            <?php include('pages/home-rooms.php'); ?>
        </div>
        <div id="menu-profile" class="tab-pane fade">
            <?php include('pages/home-profile.php'); ?>
        </div>
    </div>
<?php
}
else {
?>
    <div class="col-xs-12">
        <p>An error occurred. Please <a href="#" class="reload">reload</a> the page</p>
    </div>
<?php
}
?>
</div>