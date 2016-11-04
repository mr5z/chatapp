<?php
header("Access-Control-Allow-Origin: *");

require_once('../api/api.php');

$mapSize = post('mapSize');

if (empty($mapSize)) {
    exitWithResponse("Unspecified map size");
}

$mapSize = explode('x', $mapSize);
$mapWidth = $mapSize[0] . 'px';
$mapHeight = $mapSize[1] . 'px';

?>
<div id="map" style="width: <?php echo $mapWidth;?>; height: <?php echo $mapHeight;?>;">
</div>
<script src="https://maps.googleapis.com/maps/api/js?callback=initMap"></script>