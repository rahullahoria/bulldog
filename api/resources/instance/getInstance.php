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

    $sql = "SELECT p.`pro_inst_id`, pi.name
              FROM `p_i_maps`as p inner join p_i as pi
                WHERE p.`pro_inst_id` = pi.id and p.type='".$type."' and p.profession_id='".$profession."';";

    $sql = "SELECT `user_id` ,u.name, u.md5_id, sum( time ) AS time
            FROM `program_usage` as p inner join users as u
            WHERE p.`user_id` = u.id
            GROUP BY `user_id` ";
    //die($sql);

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        //$stmt->bindParam("type1", $type);
        //$stmt->bindParam("profession_id", $profession);
        //$stmt->debugDumpParams();
        //die(var_dump($stmt));

        $stmt->execute();
        $instances = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"instances": ' . json_encode($instances) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    catch (Exception $e){
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}