<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-12">
<?php

require_once('../api/api.php');

$userId = post('userId');
$search = post('search');

$sql = "SELECT id, firstName, lastName
        FROM users
        WHERE (firstName LIKE '$search%'
        OR lastName LIKE '$search%')
        AND id != $userId";

$result = query($sql);

if ($result) {
    if ($result->num_rows > 0) {
?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
<?php
        while($user = $result->fetch_object()) {
?>
            <tr>
                <td>
                    <a href="#" class="view-profile" data-user-id="<?php echo $user->id; ?>"><?php echo "$user->lastName, $user->firstName"; ?></a>
                </td>
                <td>
                    <a href="#" class="col-xs-8 chat" data-recipient-type="user" data-recipient-id="<?php echo $user->id; ?>" value="Chat">Chat</a>
                    <div class="col-xs-4 dropdown">
                        <a href="#" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-option-vertical"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" data-contact-id="<?php echo $user->id; ?>" data-contact-type="user">
                            <li>
                                <a href="#" class="add-contacts">Add to contacts</a>
                            </li>
                            <li>
                                <a href="#" class="view-profile" data-user-id="<?php echo $user->id; ?>">View profile</a>
                            </li>
                            <li>
                                <a href="#" class="block-user">Block user</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
<?php
        }
?>
            </tbody>
        </table>
<?php
		
    }
    else {
?>
        <div class="alert alert-warning alert-dismissable fade in top-7">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
            <h4>No results found</h4>
        </div>
<?php
    }
}
else {
?>
        <div class="alert alert-danger alert-dismissable fade in top-7">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
            <h4>An error occurred. Please try again</h4>
        </div>
<?php
}
?>
</div>