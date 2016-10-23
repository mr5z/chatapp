<?php

header("Access-Control-Allow-Origin: *");

require_once('api/api.php');

$userId = post('userId');

$result = getRoomsByUserId($userId);

?>
<div class="row padding-14 text-right">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create-room">
        Create room
        <span class="glyphicon glyphicon-plus"></span>
    </button>
</div>
<div id="modal-create-room" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Create room</h3>
            </div>
            <div class="modal-body form-group">
                <div class="row default-rows">
                    <h4>Room description</h4>
                    <label class="top-7">
                        Room name
                        <input type="text" class="form-control" placeholder="Enter room name" name="room-name" />
                    </label>
                    <label class="top-7 btn">
                        <input type="radio" name="accessibility" />
                        Public
                    </label>
                    <label class="top-7 btn">
                        <input type="radio" name="accessibility" />
                        Private
                    </label>
                </div>
                <div class="row default-rows">
                    <h4>Room members</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php

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