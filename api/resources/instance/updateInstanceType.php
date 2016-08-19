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

    $sql = "UPDATE `p_i_maps` SET `type`=:type
              WHERE `profession_id`=:profession_id AND `pro_inst_id`=:pro_inst_id;";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("type", $instance->type);
        $stmt->bindParam("profession_id", $instance->profession_id);
        $stmt->bindParam("pro_inst_id", $instance->pro_inst_id);

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"instances": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}