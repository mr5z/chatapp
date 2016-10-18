<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

require_once('api/api.php');

$recipientId = post('recipientId');
$recipientType = post('recipientType');

switch ($recipientType) {
	case "user": {
			$sql = "SELECT `firstName`, `lastName`, `active` FROM users WHERE id = $recipientId";
			$result = query($sql);
			if ($result) {
				if ($result->num_rows > 0) {
					$user = $result->fetch_object();
			?>
				<div class="col-xs-12" id="chat-greetings">
					<h4>You are now chatting with <?php echo $user->firstName; ?></h4>
				</div>
			<?php
				}
				else {
					echo "invalid userId!";
				}
			}
			else {
				echo "an internal error occurred: getDbError()";
			}
		}
		break;
	case "room": {
			$sql = "SELECT `name` FROM rooms WHERE id = $recipientId";
			$result = query($sql);
			if ($result) {
				if ($result->num_rows > 0) {
					$room = $result->fetch_object();
			?>
				<div class="col-xs-12" id="chat-greetings">
					<p>You are now in room <strong><?php echo $room->name; ?></strong></p>
				</div>
			<?php
				}
			}
			else {
				
			}
		}
		break;
	default:
		break;
}
?>
<div class="col-xs-12" id="message-container">
</div>