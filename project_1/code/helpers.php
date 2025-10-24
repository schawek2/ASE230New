<?php

//module1/lecture/RestAPI/Building REST API server with PHP/30
    function getRequestData() {
        $input = file_get_contents('php://input');
        return json_decode($input, true)?? [];
    }

     //from Connect_PHP_with_MySQL/CRUD operations Using PDO/3-4
    function getPDO() {
        $servername = "localhost";
        $name = "root";
        $password = "";
        $dbname = "music";

        $dsn = "mysql:host={$servername};dbname={$dbname};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
        
            return new PDO($dsn, $name, $password, $options);
    }

?>