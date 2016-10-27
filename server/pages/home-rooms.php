<?php

header("Access-Control-Allow-Origin: *");

require_once('api/api.php');

$userId = post('userId');

$result = getRoomsByUserId($userId);

include_once('home-rooms-modal.php');

if ($result && $result->num_rows > 0) {
    while($room = $result->fetch_object()) {
?>
    <a href="#" class="row default-rows padding-19 chat" data-recipient-id="<?php echo $room->id; ?>" data-recipient-type="room">
        <div class="col-xs-8">
            <?php echo $room->name; ?>
        </div>
        <div class="col-xs-4">
            <?php echo '(' . $room->activeUsers . '/' . $room->totalUsers . ')'; ?>
        </div>
    </a>
<?php
    }
}
else {
    echo getDbError();
}
?>