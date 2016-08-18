<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/16/16
 * Time: 9:57 PM
 */

function getEmployee($companyId, $managerId, $employee){

    $sql = "SELECT pu.`date`,sum(pu.time) as time
              FROM `program_usage` as pu INNER JOIN users as u
                  WHERE pu.user_id=u.id and u.md5_id=:employee and MONTH(date) = MONTH(CURDATE())
                  group by `user_id`,`date`;";

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