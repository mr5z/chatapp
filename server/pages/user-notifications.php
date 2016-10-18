<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-12">
<?php

require_once('../api/api.php');

$userId = post('userId');

$sql = "SELECT u.firstName AS sender, u.id AS senderId FROM users u
		INNER JOIN messages m
		ON m.senderId = u.id
		WHERE m.status = 'pending'
		AND m.recipientId = $userId
		GROUP BY u.id";

$result = query($sql);

if ($result) {
	if ($result->num_rows > 0) {
?>
<?php
		while($row = $result->fetch_object()) {
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