<?php
require __DIR__ . '/../vendor/autoload.php';

$DB_CONNECTION = $_SERVER['DB_CONNECTION'];
$DB_USERNAME   = $_SERVER['DB_USERNAME'];
$DB_PASSWORD   = $_SERVER['DB_PASSWORD'];
$DB_HOST       = $_SERVER['DB_HOST'];
$DB_DATABASE   = $_SERVER['DB_DATABASE'];

$dsn = "{$DB_CONNECTION}://{$DB_USERNAME}:{$DB_PASSWORD}@{$DB_HOST}/{$DB_DATABASE}";

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db = NewADOConnection($dsn) or die('Database Connection Failed');

$_GET['postcode'] = str_replace(' ', '', trim($_GET['postcode']));

if (isset($_GET['postcode']) && $_GET['postcode'] != '' && ctype_alnum($_GET['postcode'])) {
    $postcode = $db->getRow("SELECT * FROM postcodes WHERE REPLACE(postcode,' ','') LIKE '{$_GET['postcode']}%'");
}

if (isset($postcode) && $postcode) {
    $OSRef     = new PHPCoord\OSRef((int) $postcode['easting'], (int) $postcode['northing']);
    $LatLng    = $OSRef->toLatLng();
    $GPSLatLng = $LatLng->toWGS84();
    $lat       = $GPSLatLng->getLat();
    $lon       = $GPSLatLng->getLng();
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
<body onload="init()">
<form method="get">
    <p>
        Postcode: <input type="text" maxlength="8" value="<?= isset($_GET['postcode']) ? $_GET['postcode'] : '' ?>"
                         name="postcode"> <input
                type="submit" value="Go">
    </p>
</form>
<div style="width:100%; height:800px" id="map"></div>
<address>
    Data CC-By-SA by OpenStreetMap<br>
    Contains OS data © Crown copyright and database right 2020<br>
    Contains Royal Mail data © Royal Mail copyright and Database right 2020<br>
    Contains National Statistics data © Crown copyright and database right 2020
</address>
</body>
</html>