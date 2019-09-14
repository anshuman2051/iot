<!doctype html>
<?php
    ini_set('display_errors', 1);
    require 'vendor/autoload.php';
    session_start();
?>
<html>
<head>
<title>IOT</title>
<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css" type="text/css">
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>

<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- cdn for chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>

<!-- css styling -->
<style>
    .map{
        height: 400px;
        width: 80%;
    }
    #myChart{
        margin-top:40px;
        height: 40%;
        width: 100%;
    }
    .ol-popup {
        position: absolute;
        background-color: white;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        bottom: 12px;
        left: -50px;
        min-width: 280px;
      }
      .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
      }
      .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
      }
      .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
      }
      .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;
      }
      .ol-popup-closer:after {
        content: "✖";
      }
</style>
</head>
<body>
    <center><h2>device data</h2></center>
    <?php
    $host = '127.0.0.1';
    $usr = 'alpha';
    $db_name = 'lora';
    $pass = 'Anshu@123';
    $output;

    // mysql connection
    $conn = new mysqli($host, $usr, $pass, $db_name);
    if(mysqli_connect_errno()){
      trigger_error('Database connection failed :'. mysqli_connect_error(),E_USER_ERROR);
    }

    if($rs = $conn->query("select * from lora_t")){
      while(null != ($row = $rs->fetch_assoc())){
        print_r($row);
        $output[] = $row;
      }
      // setting session value
      $_SESSION['output'] = $output;
  }
    $conn->close();
    ?>


    <!-- showing map fromhere -->
    <h2> MY MAP </h2>
    <div id = 'map' class='map' > </div>

    <!-- popup elements -->
    <div id="popup" class="ol-popup">
      <a href = "#" id="popup-closer" class = "ol-popup-closer"></a>
      <div id="popup-content">
        <a href = "./data.php"> data</a>
      </div>
    </div>
    

      <!-- // imports -->
    <script>
      const Map = ol.Map;
      const Overlay = ol.Overlay;
      const View = ol.View;
      const MultiPoint = ol.geom.MultiPoint;
      const Point = ol.geom.Point;
      const TileLayer = ol.layer.Tile;
      const OSM = ol.source.OSM;
      const CircleStyle = ol.style.Circle;
      const Style = ol.style.Style;
      const {Fill, Icon, Stroke}= ol.style;
      const Feature = ol.Feature;
      const VectorLayer = ol.layer.Vector;
      const VectorSource = ol.source.Vector;
      const fromLonLat = ol.proj.fromLonLat;  

    </script>

    <script>
    //gettng php response in form of array
    const res = <?php global $output; echo json_encode($output); ?>;
    console.log(res);
    //creating popup
      const container = document.getElementById('popup');
      const content = document.getElementById('popup-content');
      const closer = document.getElementById('popup-closer');

      // creating overlay to show over the map
      const overlay = new Overlay({
        element : container,
        autoPan: true,
        autoPanAnimation : {
          duration: 250
        }
      });

      // close action for popup
      closer.onclick = ()=>{
        overlay.setPosition(undefined);
        closer.blur();
        return false;
      };
    
    const points = [];
    // putting all lon and lat data into points array;
    //wrong value of lat and lon given change it into the database
    for( r of res){
      points.push(
        new Feature({
          type: 'point',
          geometry: new Point(fromLonLat([r['lat'],r['longi']]))
        })
      );
    }
    console.log(points);

    // todo : addd style to the point
    var imageStyle = new Style({
        image: new CircleStyle({
          radius: 5,
          fill: new Fill({color: 'yellow'}),
          stroke: new Stroke({color: 'red', width: 1})
        })
      });
    var map = new Map({
        layers: [
          new TileLayer({
            source: new OSM()
          }),
          new VectorLayer({
            source: new VectorSource({
              features: points,
              style : imageStyle
            }),
          })
        ],
        overlays : [overlay],
        target: 'map',
        view: new ol.View({
          center: ol.proj.fromLonLat([0, 0]),
          zoom: 2
        })
      });

      map.render();


  

      // display popup on click
      map.on('click', function(evt) {
        var feature = map.forEachFeatureAtPixel(evt.pixel,
          function(feature) {
            return feature;
          });
        if (feature) {
          var coordinates = feature.getGeometry().getCoordinates();
          console.log(coordinates);
          overlay.setPosition(coordinates);
      
        }
      });

   </script>


   
</body>
</html>
