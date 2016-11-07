<?php header("Access-Control-Allow-Origin: *"); ?>
<div>
<?php
    $result = getContactListByUserId($userId);
    if ($result) {
        if ($result->num_rows > 0) {
            while($user = $result->fetch_object()) {
?>
            <a href="#" class="row default-rows chat padding-19" data-recipient-type="user" data-recipient-id="<?php echo $user->id; ?>">
                <?php echo "$user->firstName $user->lastName, $user->age"; ?>
                <div class="extra-detail">
                    <?php echo toTitleCase($user->city); ?>
                </div>
            </a>
<?php
            }
        }
        else {
?>
        <div class="alert alert-info alert-dismissable note fade in top-15">
            <div class="row vertical-align">
                <div class="col-xs-10">
                    <p>Search for people or rooms in the search box and add them in your contact list</p>
                </div>
                <div class="col-xs-1" style="font-size:32px">
                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                </div>
            </div>
        </div>
        <p>Your contact list is empty</p>
<?php
        }
    }
    else {
?>
        <div class="alert alert-warning alert-dismissable fade in top-15">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
            <p>An error occurred. Please try again.</p>
        </div>
<?php
    }
?>
</div>