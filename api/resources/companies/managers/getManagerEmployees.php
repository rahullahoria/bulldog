<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */


function getManagerEmployees($companyId, $managerId){

    $sql = "SELECT `user_id` ,u.name, u.md5_id, sum( time ) AS time
            FROM `program_usage` as p inner join users as u
            WHERE p.`user_id` = u.id
            GROUP BY `user_id` ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        /*$stmt->bindParam("id", $fileId);
        $stmt->bindParam("username", $username)*/;

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"employees": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}