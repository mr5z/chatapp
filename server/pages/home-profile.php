<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

require_once('api/api.php');

$userId = post('userId');

$sql = "SELECT firstName, lastName, city FROM users WHERE id = $userId";

$result = query($sql);

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
				City:
			</div>
			<div class="col-xs-8">
				 <?php echo toTitleCase($user->city); ?>
			</div>
		</div>
	</div>
	<div class="default-rows padding-19">
		<h3>Contact List</h3>
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