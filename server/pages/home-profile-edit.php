<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

require_once('../api/api.php');

$userId = post('userId');

$result = getUserById($userId);

if ($result) {
    $user = $result->fetch_object();
?>
    <div class="default-rows padding-19">
        <h3>
            Edit Basic Information
        </h3>
        <div class="row">
            <div class="col-xs-4">
                First name:
            </div>
            <div class="col-xs-8">
                <input type="text" class="form-control" value="<?php echo "$user->firstName"; ?>" name="edit-first-name" placeholder="First name" />
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                Last name:
            </div>
            <div class="col-xs-8">
                <input type="text" class="form-control" value="<?php echo "$user->lastName"; ?>" name="edit-last-name" placeholder="Last name" />
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                City:
            </div>
            <div class="col-xs-8">
                <input type="text" class="form-control" value="<?php echo toTitleCase($user->city); ?>" name="edit-city" placeholder="City name" />
            </div>
        </div>
    </div>
    <div class="default-rows padding-19">
        <h3>Edit Contact List</h3>
        <ul>
<?php
            $result = getContactListByUserId($userId);
            while($row = $result->fetch_object()) {
?>
                <li class="padding-7">
                    <?php echo $row->firstName; ?>
                    <a href="#" class="glyphicon glyphicon-remove pull-right remove-contact" data-contact-id="<?php echo $row->id; ?>"></a>
                </li>
<?php
            }
?>
        </ul>
    </div>
    <div class="default-rows padding-19 text-right">
        <a href="#" class="btn btn-link" id="cancel-edit-profile">
            Cancel
        </a>
        <a href="#" class="btn btn-primary" id="save-edit-profile">
            Save
        </a>
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