<?php 

    try {

        // JSON output
        header('Content-Type: application/json');

        // Database connect
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=rgpksmnv_m1;charset=UTF8;','rgpksmnv','fR4LT2', array(PDO::ATTR_PERSISTENT=>true));
        $pdo->query("SET NAMES utf8;");

        // Insert my results
        $insert = $pdo->prepare("INSERT INTO `scores` (`id`, `name`, `score`) VALUES (NULL, :name, :score);");
        $insert->execute(array(
            "name" => $_GET["name"],
            "score" => $_GET["score"]
        ));

        $insertId = $pdo->lastInsertId();
        
        // Parametrs
        $success = false;
        $response = Array();
        $myScore = 0; 
        $place = 1;

        // Get top players
        $get = $pdo->prepare("SELECT * FROM scores ORDER BY score DESC");
        $get->execute();

        // Sorting
        while($row = $get->fetch()) {
            if($place <= 10) $response[] = Array("name" => $row["name"], "score" => $row["score"], "place" => $place);
            if($insertId == $row["id"]) {
                if($place <= 10) $success = true;
                $myScore = $place;
            }
            $place++;
        };

        // My place
        if(!$success) $response[9] = Array("name" => $_GET["name"], "score" => $_GET["score"], "place" => $myScore);

        // output json 
        echo json_encode($response);

    } catch (\Throwable $th) {
        throw $th;
    }