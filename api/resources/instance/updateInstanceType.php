<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/18/16
 * Time: 5:24 PM
 */

function updateInstance(){

    $request = \Slim\Slim::getInstance()->request();
    $instance = json_decode($request->getBody());

    $sql = "INSERT INTO `bulldog`.`p_i_maps`
                (`profession_id`, `instance_id`, `type`) VALUES ( :profession_id, :instance_id, :type);";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("profession_id", $instance->profession_id);
        $stmt->bindParam("instance_id", $instance->instance_id);
        $stmt->bindParam("type", $instance->type);

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"instances": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}