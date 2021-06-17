<?php
        $conn = null;
        $host = "localhost";
        $port = "3306";
        $db   = "viewitdb";
        $user = "root";
        $pass = "";

        try {
            $conn = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
?>


