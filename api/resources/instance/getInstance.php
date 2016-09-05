<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/18/16
 * Time: 5:23 PM
 */

function getInstance($md4Id,$type){

    if ($type == "manager") $type = "white";
    if ($type == "employee") $type = "black";

    $sql = "SELECT distinct i.`id`,i.`instance`
                FROM `instances` as i
                  inner join usages as u

                WHERE u.user_id = (select id from users where md5_id = :md4Id) ";

    //die($profession. " " . $type);

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("md4Id", $md4Id);
        //$stmt->bindParam("profession_id", $profession);
        //$stmt->debugDumpParams();
        //die(var_dump($stmt));

        $stmt->execute();
        $instances = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ( $instances as $instance) {
            $instance->instance =  base64_decode($instance->instance);

        }

        echo '{"instances": ' . json_encode($instances) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}