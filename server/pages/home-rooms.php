<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

// $sql = "SELECT city, SUM(active) AS activeUsers, COUNT(*) AS totalUsers FROM users GROUP BY city ORDER BY city ASC";
$sql = "SELECT * FROM rooms";

$result = query($sql);

if ($result && $result->num_rows > 0) {
    while($room = $result->fetch_object()) {
?>
    <a href="#" class="row default-rows padding-19 chat" data-recipient-id="<?php echo $room->id; ?>" data-recipient-type="room">
        <div class="col-xs-8">
            <?php echo $room->name; ?>
        </div>
        <div class="col-xs-4">
            <?php /*echo '(' . $row->activeUsers . '/' . $row->totalUsers . ')';*/ ?>
        </div>
    </a>
<?php
    }
}
?>