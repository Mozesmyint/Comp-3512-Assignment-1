<?php
    define(conn, "sqlite:" .__DIR__ . "/data/f1.db");
    $user = "username";
    $pass = "password";

    try {
        $pdo = new PDO(conn, $user, $pass);


    }
    catch(PDOConnection $e) {
        echo "Connection error";
        echo $e -> getMessage();
    }
    function getData($sql); {
        try {
            global $pdo;
            $result = $pdo => query($sql);
            $data = $result = fetchAll(FETCH_ASSOC);
            return $data;
        }
        catch {
            return null;
        }
    }

