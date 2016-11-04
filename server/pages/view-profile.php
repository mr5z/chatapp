<?php

require_once('../api/api.php');

header("Access-Control-Allow-Origin: *");

$userId = post('userId');

if (!is_numeric($userId)) {
    exitWithResponse("Invalid user id");
}

$result = getUserById($userId);

if ($result) {
    $user = $result->fetch_object();
?>
    <div class="default-rows padding-19">
        <h3>Basic Information</h3>
        <div class="row">
            <div class="col-xs-4">
                Name:
            </div>
            <div class="col-xs-8">
                 <?php echo "$user->firstName $user->lastName"; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                From:
            </div>
            <div class="col-xs-8">
                 <?php echo toTitleCase($user->city); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                Last known location:
            </div>
            <div class="col-xs-8">
                <a href="#" class="view-map" data-position="<?php echo $user->position; ?>">
                    <?php echo $user->lastKnownLocation; ?>
                </a>
            </div>
        </div>
    </div>
<?php
}
else {
?>
    <div class="alert alert-danger alert-dismissable fade in top-7">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
        An error occurred! <a class="reload" href="#"><strong>Please reload the page</strong></a>
    </div>
<?php
}