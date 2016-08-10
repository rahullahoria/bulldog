<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:02 PM
 */

//SELECT `user_id`,`date`,sum(time)/60/60 FROM `program_usage` WHERE 1 group by `user_id`,`date

$db_handle = mysqli_connect("localhost","root","redhat@11111p","bulldog");
$result = mysqli_query($db_handle,"SELECT `user_id`,`date`,sum(time) as time FROM `program_usage` WHERE user_id=5 group by `user_id`,`date`;");
$strData = "";
while($row = mysqli_fetch_assoc($result)){

    $strData .= "date: " .$row['date'] . " user_id: " . $row['user_id'] . " time in hrs: " . gmdate("H:i:s", $row['time']) . "<br/>"  ;


}

mysqli_close($db_handle);
?>
<div style="text-align: center;">
    <img class="raleway-logo" src="http://shatkonlabs.com/images/logo.png" height="70px">
    <h1>Shatkon Labs Pvt. Ltd.</h1>
    <img class="raleway-logo" src="https://upload.wikimedia.org/wikipedia/commons/1/13/Clyde_The_Bulldog.jpg" height="100px">
    <h2>Bulldog</h2>
    <?= $strData ?>
    <h4><i> if you have any problem contact at rahul@shatkonlabs.com or Call at 9599075955 </i></h4>
</div>