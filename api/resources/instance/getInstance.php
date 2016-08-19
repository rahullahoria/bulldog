<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/18/16
 * Time: 5:23 PM
 */

function getInstance($profession,$type){

    if ($type == "manager") $type = "white";
    if ($type == "employee") $type = "black";

    $sql = "SELECT p.`pro_inst_id`,pi.name
              FROM `p_i_maps`as p inner join p_i as pi
                WHERE p.`pro_inst_id` = pi.id and p.type=:type and p.profession_id=:profession_id;";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("type", $type);
        $stmt->bindParam("profession_id", $profession);

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"instances": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}