<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */


function getManagerEmployees($companyId, $managerId){

    global $app;

    $month = $app->request()->get('month');

    $sql = "SELECT `user_id` ,u.name, u.md5_id, sum( time ) AS time
            FROM `usages` as p inner join users as u
            WHERE p.`user_id` = u.id and MONTH(p.creation) = :month
            GROUP BY `user_id`,date(p.creation); ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        /*$stmt->bindParam("id", $fileId);
        $stmt->bindParam("username", $username)*/;
        $stmt->bindParam("month", $month);

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"employees": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}