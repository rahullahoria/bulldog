<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/26/16
 * Time: 5:09 PM
 */
/*
 * INSERT INTO `bulldog`.`usages` (
`id` ,
`instance_id` ,
`time` ,
`user_id` ,
`pc_username` ,
`ip` ,
`creation`
)
VALUES (
NULL , '1', '60', '3', 'unknown', '122.176.246.115', '2016-08-25 14:50:27'
);

1/6/16-24/8/16

9am-9pm

userid 1-7
instance 1-100
time (1-4)*60
step size 2 min
*/
$config['host'] = "localhost";
$config['user'] = "root";
$config['password'] = "redhat@11111p";
$config['database'] = "bulldog";


$db_handle = mysqli_connect($config['host'], $config['user'], $config['password'], $config['database']);

$startDate = '2016-06-01 08:50:27';
$endDate = '2016-08-24 20:50:27';

/*$created_date = date("Y-m-d H:i:s");
$newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +2 minutes"));*/

//if(strtotime($date1) < strtotime($date2));

while (strtotime($endDate) > strtotime($startDate."")){
    $endDayDate = strtotime($startDate." +12 hour");

    echo "Start Date: ". $startDate." endDate:". $endDate." end Day:".$endDayDate."\n";
    while (strtotime($endDayDate) > strtotime($startDate."")){
        $userId = rand(1,7);
        $instance = rand(1,101);
        $time = rand(1,4)*60;
        $step = rand(1,3);

        $sql = "INSERT INTO `bulldog`.`usages` (`instance_id` , `time` , `user_id` , `pc_username` , `ip` , `creation`)
                  VALUES (
                       '".$instance."', '".$time."', '".$userId."', 'unknown', '122.176.246.115', '".$startDate."'
                    );";

        echo $sql."\n\n";
        mysqli_query($db_handle, $sql);


        $id = mysqli_insert_id($db_handle);
        if($id == 0) die("someThing wrong happed \n".$sql);


        $startDate = date("Y-m-d H:i:s",strtotime($startDate." +".$step." minutes"));
    }
    $startDate = date("Y-m-d H:i:s",strtotime($startDate." +12 hour"));

}

