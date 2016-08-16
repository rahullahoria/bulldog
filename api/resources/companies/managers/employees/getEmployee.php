<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/16/16
 * Time: 9:57 PM
 */

function getEmployee($companyId, $managerId, $employee){

    $sql = "SELECT `user_id`,`date`,sum(time) as time FROM `program_usage` WHERE user_id=:employee group by `user_id`,`date`;";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("employee", $employee);/*
        $stmt->bindParam("username", $username)*/;

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"employee": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}