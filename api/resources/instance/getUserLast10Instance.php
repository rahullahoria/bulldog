<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/6/16
 * Time: 2:19 PM
 */

function getUserLast10Instance($md4Id,$type){

    if ($type == "manager") $type = "white";
    if ($type == "employee") $type = "black";

    //global $app;
    //$month = $app->request()->get('month');

    $sql = "SELECT distinct i.`id`,i.`instance`, p.program
                FROM usages as u
                  inner join `instances` as i
                  INNER JOIN programs as p

                WHERE  p.id = i.program_id
                    AND u.instance_id = i.id
                    and u.user_id = (select id from users where md5_id = :md4Id)
                ORDER By u.creation DESC
                LIMIT 0,10";

    //die($profession. " " . $type);

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("md4Id", $md4Id);
        //$stmt->bindParam("month", $month);


        $stmt->execute();
        $instances = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"instances": ' . json_encode($instances) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}