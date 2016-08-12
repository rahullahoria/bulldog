<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:02 PM
 */

//SELECT `user_id`,`date`,sum(time)/60/60 FROM `program_usage` WHERE 1 group by `user_id`,`date


$db_handle = mysqli_connect("localhost", "root", "redhat@11111p", "bulldog");
if($_GET[user_id]){
    //here auth should be checked first
    $userId = $_GET[user_id];
}

if (isset($userId)) {

    $result = mysqli_query($db_handle, "SELECT `user_id`,`date`,sum(time) as time FROM `program_usage` WHERE user_id="
        . $userId
        . " group by `user_id`,`date`;");
    $strData = "";
    $labels = "";
    $workingHrs = "";
    $expectedHrs = "";
    $fun = "";
    $workingDays = 0;
    $first = null;
    while ($row = mysqli_fetch_assoc($result)) {

        if ($workingDays == 0) $first = $row['date'];

        $labels .= "\"" . $row['date'] . "\",";
        $workingHrs .= "\"" . gmdate("H.i", $row['time']) . "\",";
        $expectedHrs .= "\"8\",";
        $fun .= "\"" . rand(1, 4) . "\",";
        $workingDays++;
        $last = $row['date'];

    }


    $totalDays = floor(abs(strtotime($last . "") - strtotime($first . "")) / (60 * 60 * 24));

    $labels = rtrim($labels, ",");
    $workingHrs = rtrim($workingHrs, ",");
    $expectedHrs = rtrim($expectedHrs, ",");
    $fun = rtrim($fun, ",");
}

$sql = "SELECT `user_id` ,u.name, sum( time ) AS time
            FROM `program_usage` as p inner join users as u
            WHERE p.`user_id` = u.id
            GROUP BY `user_id` ";

$result = mysqli_query($db_handle, $sql);

$emp = "";
while ($row = mysqli_fetch_assoc($result)) {

    $tFun = rand(intval($row['time']/4),intval($row['time']/2));
    $emp .= "<tr>
                <td><a href='?user_id=".$row['user_id']."'> ".$row['name']."</a></td>
                <td>". intval($row['time']/60/60)."</td>
                <td>".intval($tFun/60/60) ."</td>
                <td>".intval(date("d")*6.85)."</td>
                <td>".intval((($row['time']/60/60)/(date("d")*6.85))*100)."%</td>
             </tr>";

}

mysqli_close($db_handle);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BullDog</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">


</head>

<body>
<div style="text-align: center;">
    <img class="raleway-logo" src="http://shatkonlabs.com/images/logo.png" height="70px">
    <img class="raleway-logo" src="https://upload.wikimedia.org/wikipedia/commons/1/13/Clyde_The_Bulldog.jpg"
         height="100px">
    <h3>Shatkon Labs Pvt. Ltd.</h3>

    <h2>Bulldog</h2>
    <table id="example">
        <thead>
        <tr>
            <th>Username</th>
            <th>Total Work in Hrs</th>
            <th>Total Fun in Hrs</th>
            <th>Expected Working Hrs</th>
            <th>Efficiency</th>
        </tr>
        </thead>
        <tbody>
        <?= $emp ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-10">
            <div class="container">
                <h2>Graph</h2>
                <div>
                    <canvas id="canvas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            Report For: <?= $workingDays ?>/<?= $totalDays ?> days
        </div>
    </div>


    <h4><i> if you have any problem contact at rahul@shatkonlabs.com or Call at 9599075955 </i></h4>
</div>

<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
<script>
    $(function(){
        $("#example").dataTable();
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.0/Chart.bundle.min.js"></script>

<?php if (isset($userId)) { ?>
<script>

    var data = {
        labels: [<?= $labels ?>],
        datasets: [
            {
                label: "Expected working hours",
                backgroundColor: "rgba(0,220,0,0.2)",


                data: [<?= $expectedHrs ?>]
            },
            {
                label: "Fun Time",
                backgroundColor: "rgba(151,0,0,0.8)",
                data: [<?= $fun ?>]
            },
            {
                label: "Working Hours",
                backgroundColor: "rgba(151,187,0,0.4)",
                data: [<?= $workingHrs ?>]
            }
        ]
    };



    var options = {

        scales: {
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Date'
                }
            }],
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Hours'
                }
            }]
        },

        ///Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,

        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",

        //Number - Width of the grid lines
        scaleGridLineWidth: 1,

        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,

        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,

        //Boolean - Whether the line is curved between points
        bezierCurve: true,

        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.4,

        //Boolean - Whether to show a dot for each point
        pointDot: true,

        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,

        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,

        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,

        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,

        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,

        //Boolean - Whether to fill the dataset with a colour
        datasetFill: true,

        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

    };


    var ctx = document.getElementById("canvas").getContext("2d");
    new Chart(ctx, {type: 'line', data, options});


    //var myLineChart = new Chart(ctx).Line(data, options);


</script>

<?php } ?>

</body>
</html>
