<?php
    #$URL_CONNECTION = "mysql:host=localhost;dbname=u182380138_mineralia;charset=utf8";
    #$URL_USER = "u182380138_mineralia_adm";
    #$URL_PASS = "#pN9#@8n";

    $URL_CONNECTION = "mysql:host=localhost;dbname=mineralia;charset=utf8";
    $URL_USER = "root";
    $URL_PASS = "";

    try {
        $connection = new PDO($URL_CONNECTION, $URL_USER, $URL_PASS);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
