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

    $file = json_decode("{'file_name':'','size':''}");
    //var_dump($_FILES["fileToUpload"]["name"]);die();
    //$file->file_name = $_FILES["fileToUpload"]["name"];
    //$file->size = $_FILES['fileToUpload']['size']/MB;
//var_dump($usage);die();
    $sql = "insert into program_usage ( program, instance, time, date) VALUES ( :program, :instance, :time, :date)
            ON DUPLICATE KEY UPDATE time=time+:newT;";

    try {


        $db = getDB();
        foreach ($usage as $k => $u){
            $stmt = $db->prepare($sql);

            $stmt->bindParam("program", $u->program);
            $stmt->bindParam("instance", $k);
            $stmt->bindParam("time", $u->time);
            $stmt->bindParam("newT", $u->time);
            $stmt->bindParam("date", date('Y-m-d'));


            $stmt->execute();
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
