<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */
define('MB', 1048576);

function saveUsage($username){

    $request = \Slim\Slim::getInstance()->request();
    $usage = json_decode($request->getBody());

    //$file = json_decode("{'file_name':'','size':''}");
    //var_dump($_FILES["fileToUpload"]["name"]);die();
    //$file->file_name = $_FILES["fileToUpload"]["name"];
    //$file->size = $_FILES['fileToUpload']['size']/MB;
//var_dump($usage);die();
    $sql = "insert into program_usage ( program, instance, time, date, user_id) VALUES ( :program, :instance, :time, :date, :user_id)
            ON DUPLICATE KEY UPDATE time=time+:newT;";

    try {


        $db = getDB();
        foreach ($usage as $k => $u){
            if (isset($u->program)) {
                $stmt = $db->prepare($sql);
                $ti = intval($u->time/60);
                $stmt->bindParam("program", $u->program);
                $stmt->bindParam("instance", $k);
                $stmt->bindParam("time", $ti);
                $stmt->bindParam("newT", $ti);
                $stmt->bindParam("date", date('Y-m-d'));
                $stmt->bindParam("user_id", $username);



                $stmt->execute();
            }
        }
        $id = $db->lastInsertId();


        $db = null;

        echo '{"file": { "id":"' . $id . '"}}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }
}



?>
