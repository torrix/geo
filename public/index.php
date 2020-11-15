<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db = NewADOConnection("{$_ENV['DB_CONNECTION']}://{$_ENV['DB_USERNAME']}:{$_ENV['DB_PASSWORD']}@{$_ENV['DB_HOST']}/{$_ENV['DB_DATABASE']}");
if (! $db) {
    die('Database Connection Failed');
}

if (isset($_GET['postcode']) && $_GET['postcode'] != '') {
    $postcode = $db->getRow("SELECT * FROM postcodes WHERE postcode LIKE '{$_GET['postcode']}%'");
}

if (isset($postcode) && $postcode) {
    $lat = $postcode['latitude'];
    $lon = $postcode['longitude'];
} else {
    $lat = 54.0643908566752;
    $lon = -2.85714411442939;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OpenStreetMap</title>
    <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
    <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
    <script type="text/javascript">
        // Start position for the map (hardcoded here for simplicity,
        // but maybe you want to get from URL params)

        var lat =<?php echo $lat; ?>;
        var lon =<?php echo $lon; ?>;

        var zoom = 16;

        var map; //complex object of type OpenLayers.Map

        //Initialise the 'map' object
        function init() {
            map = new OpenLayers.Map("map", {
                controls: [
                    new OpenLayers.Control.Navigation(),
                    new OpenLayers.Control.PanZoomBar()
                ],
                maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
                maxResolution: 156543.0399,
                numZoomLevels: 19,
                units: 'm',
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326")
            });


            layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
            map.addLayer(layerMapnik);

            layerMarkers = new OpenLayers.Layer.Markers("Markers");
            map.addLayer(layerMarkers);

            var lonLat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
            map.setCenter(lonLat, zoom);

            var size = new OpenLayers.Size(21, 25);
            var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);
            var icon = new OpenLayers.Icon('http://www.openstreetmap.org/openlayers/img/marker.png', size, offset);
            layerMarkers.addMarker(new OpenLayers.Marker(lonLat, icon));
        }
    </script>
</head>
<body onload="init();">
<form action=".." method="get">
    <p>
        Postcode: <input type="text" maxlength="8" value="<?= isset($_GET['postcode']) ? $_GET['postcode'] : '' ?>"
                         name="postcode"> <input
                type="submit" value="Go">
    </p>
</form>
<div style="width:100%; height:800px" id="map"></div>
Data CC-By-SA by OpenStreetMap
</body>
</html>