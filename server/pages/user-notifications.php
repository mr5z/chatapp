<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-12">
<?php

require_once('../api/api.php');

$userId = post('userId');

$notifications = getNotificationsByRecipient($userId, 'user');

if ($notifications) {
    if ($notifications->num_rows > 0) {
?>
<?php
        while($row = $notifications->fetch_object()) {
?>
            <a class="row default-rows padding-19 chat" href="#" data-recipient-id="<?php echo $row->senderId; ?>" data-recipient-type="user">
                <strong><?php echo $row->sender; ?></strong> has sent you a message
            </a>
<?php
        }
    }
    else {
?>
        <div class="alert alert-info alert-dismissable fade in top-15">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
            <p>All caught up!</p>
        </div>
<?php
	}
}
else {
?>
        <div class="alert alert-warning alert-dismissable fade in top-15">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
            <p>An error occurred. Please try again</p>
        </div>
<?php
}
?>
</div>