<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */

function getProgramId($programName){
    $getProgramIdSql = "SELECT id
                FROM `programs`
                WHERE program =:program;";
    $insertProgramSql = "Insert into  `programs`( `program`) VALUES (:program)";

    try {
        $db = getDB();
        $stmt = $db->prepare($getProgramIdSql);

        $stmt->bindParam("program",base64_encode($programName));


        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);




        if(count($user) == 1){
            $db = null;
            return $user[0]->id;
        }
        else{

            $db = getDB();
            $stmt = $db->prepare($insertProgramSql);

            $stmt->bindParam("program",base64_encode($programName));


            $stmt->execute();
            $id = $db->lastInsertId();
            $db = null;
            return $id;
        }



    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        //echo '{"error":{"text":' . $e->getMessage() . '}}';
        return false;
    }

}

function getInstanceId($instanceName,$programId){
    $getProgramIdSql = "SELECT id
                FROM `instances`
                WHERE program_id =:programId and instance=:instance;";

    $insertProgramSql = "INSERT INTO `instances`( `program_id`, `instance`)
                            VALUES (:programId,:instance)";

    try {
        $db = getDB();
        $stmt = $db->prepare($getProgramIdSql);

        $stmt->bindParam("programId",$programId);
        $stmt->bindParam("instance",base64_encode($instanceName));


        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);




        if(count($user) == 1){
            $db = null;
            return $user[0]->id;
        }
        else{

            $db = getDB();
            $stmt = $db->prepare($insertProgramSql);

            $stmt->bindParam("programId",$programId);
            $stmt->bindParam("instance",base64_encode($instanceName));


            $stmt->execute();
            $id = $db->lastInsertId();
            $db = null;
            return $id;
        }



    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        //echo '{"error":{"text":' . $e->getMessage() . '}}';
        return false;
    }

}

function saveUsageV1($username){

    $request = \Slim\Slim::getInstance()->request();
    $usage = json_decode($request->getBody());
    if(!isset($usage->pc_username)) $usage->pc_username = "";
    $ip = $_SERVER['REMOTE_ADDR'];


    $insertUsageSql = "INSERT INTO `usages`(`instance_id`, `time`, `user_id`, `pc_username`, `ip`)
                          VALUES (:instanceId,:time,:userId,:pcUsername,:ip)";

   /* $sql2 = "insert into files ( file_name, program_usage_id, time) VALUES ( :file_name, :program_usage_id, :time)
            ON DUPLICATE KEY UPDATE time=time+:newT;";*/

    try {


        $db = getDB();
        foreach ($usage as $k => $u){
            if (isset($u->program)) {
                $stmt = $db->prepare($insertUsageSql);
                $instanceId = getInstanceId($k,getProgramId($u->program));
                $ti = $u->time;

                $stmt->bindParam("instanceId", $instanceId);
                $stmt->bindParam("time", $ti);

                $stmt->bindParam("pcUsername", $usage->pc_username);

                $stmt->bindParam("ip", $ip);
                $stmt->bindParam("user_id", $username);



                $stmt->execute();
                $id = $db->lastInsertId();
                /*foreach ($u->files as $k1 => $u1){

                    $stmt = $db->prepare($sql2);

                    $stmt->bindParam("program_usage_id", $id);
                    $stmt->bindParam("file_name", $k1);
                    $stmt->bindParam("time", $u1);
                    $stmt->bindParam("newT", $u1);

                    $stmt->execute();
                }*/
            }
        }
        $id = $db->lastInsertId();


        $db = null;

        //echo '{"usage": '.json_encode($usage).'}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        //echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }
}

function saveUsage($username){
    saveUsageV1($username);

    $request = \Slim\Slim::getInstance()->request();
    $usage = json_decode($request->getBody());

    //$file = json_decode("{'file_name':'','size':''}");
    //var_dump($_FILES["fileToUpload"]["name"]);die();
    //$file->file_name = $_FILES["fileToUpload"]["name"];
    //$file->size = $_FILES['fileToUpload']['size']/MB;
//var_dump($usage);die();
    $sql = "insert into program_usage ( program, instance, time, date, user_id) VALUES ( :program, :instance, :time, :date, :user_id)
            ON DUPLICATE KEY UPDATE time=time+:newT;";

    $sql2 = "insert into files ( file_name, program_usage_id, time) VALUES ( :file_name, :program_usage_id, :time)
            ON DUPLICATE KEY UPDATE time=time+:newT;";

    try {


        $db = getDB();
        foreach ($usage as $k => $u){
            if (isset($u->program)) {
                $stmt = $db->prepare($sql);
                $ti = $u->time;
                $stmt->bindParam("program", $u->program);
                $stmt->bindParam("instance", $k);
                $stmt->bindParam("time", $ti);
                $stmt->bindParam("newT", $ti);
                $stmt->bindParam("date", date('Y-m-d'));
                $stmt->bindParam("user_id", $username);



                $stmt->execute();
                $id = $db->lastInsertId();
                foreach ($u->files as $k1 => $u1){

                    $stmt = $db->prepare($sql2);

                    $stmt->bindParam("program_usage_id", $id);
                    $stmt->bindParam("file_name", $k1);
                    $stmt->bindParam("time", $u1);
                    $stmt->bindParam("newT", $u1);

                    $stmt->execute();
                }
            }
        }
        $id = $db->lastInsertId();


        $db = null;

        echo '{"usage": '.json_encode($usage).'}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }
}



?>
