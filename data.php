<!-- to do get res array from prev page to this -->
<?php
ini_set('display_errors', 1);
require 'vendor/autoload.php';
session_start();
?>

<!doctype html>
<html>
<head>
<title>IOT</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- cdn for chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <style>
        #myChart{
                height: 300px;
                width: 40px;
            }
    </style>
</head>
<body >
<?php
    //creating seperate mysqli object
    $host = '127.0.0.1';
    $usr = 'alpha';
    $db_name = 'lora';
    $pass = 'Anshu@123';
    $output;
    $conn;

    // mysql connection
    $conn = new mysqli($host, $usr, $pass, $db_name);
    if(mysqli_connect_errno()){
      trigger_error('Database connection failed :'. mysqli_connect_error(),E_USER_ERROR);
    }
    if($rs = $conn->query("select * from lora_t")){
      while(null != ($row = $rs->fetch_assoc())){
        $output[] = $row;
      }
    }
?>
    <center><h3>Data Chart</h3></center>
    <!-- <select>
            <option value="a">a</option>
    </select> -->
    <!-- chart js usage -->
    <div class="container mx-auto">
        <div class="row ">
            <canvas  id="myChart" class="col "></canvas>
            <canvas  id="myChart2" class="col "></canvas>
        </div>
    </div>


<?php
    global $conn;
    $out;
    if($rs2 = $conn->query("select * from lora_t ")){
      while(null != ($row = $rs2->fetch_assoc())){
        $out[] = $row;
      }
    }
    print_r($rs2->fetch_assoc());
    // echo '<script>',
    //     //script for a sample timer;
    //     'const timer = document.getElementById("timer");',
    //     'function display(){',
    //         'setTimeout("timer_c()", 1000);',
    //     '}',
    //     'function timer_c(){',
    //         'timer.innerHTML = parseInt(timer.innerHTML) + 1;',
    //         'display();',
    //     '}',
    //     'display();',
    //     '</script>';
?>

<script>
    function update_data(){
        console.log("update called");
        setTimeout(
           'do_update()' 
        , 10000);
    }
    function do_update(){
        console.log("do update called");
        window.location.href =  window.location.href;
        update_data();
    }
    update_data();

//    previous output
     var data = <?php global $output ;echo json_encode($output); ?>;
    // parsing time datatype
    for(let d of data){
        var parts = d['time'].split(':');
        var sum = parseInt(parts[2]) * 3600 + parseInt(parts[1])*60 + parseInt(parts[0]);
        console.log(sum);
        d['time'] = sum;
    }
    var time = [];
    var ph = [];
    for(d of data){
        time.push(d['time']);
        ph.push(d['ph']*100);
    }
    console.log(time);

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['t1', 't2', 't3', 't4', 't5'],
            datasets: [{
                label: 'IOT data',
                data :time,
                fill:"origin",
                backgroundColor: [
                    'rgba(55, 99, 132, 0.2)',
                    'rgba(154, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            },
            {
                label: 'IOT data',
                data :ph,
                fill:"origin",
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    var e= document.getElementById("myChart");
    e.style.height = "350px";
    e.style.width = "550px";


    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var myChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['t1', 't2', 't3', 't4', 't5'],
            datasets: [{
                label: 'IOT data',
                data :time,
                fill:"origin",
                backgroundColor: [
                    'rgba(55, 99, 132, 0.2)',
                    'rgba(154, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            },
            {
                label: 'IOT data',
                data :ph,
                fill:"origin",
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    var e2= document.getElementById("myChart2");
    e2.style.height = "350px";
    e2.style.width = "550px";
</script>

</body>
</html>
