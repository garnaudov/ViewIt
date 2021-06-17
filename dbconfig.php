<?php
// $conn=mysqli_connect("localhost","root","root","viewitdb");
// // Check connection
// if (mysqli_connect_errno())
//   {
//   echo "Failed to connect to MySQL: " . mysqli_connect_error();
//   }
// $conn->query("SET NAMES UTF8");
$conn = null;
// $host = getenv('DB_HOST');
//         $port = getenv('DB_PORT');
//         $db   = getenv('DB_DATABASE');
//         $user = getenv('DB_USERNAME');
//         $pass = getenv('DB_PASSWORD');

        $host = "localhost";
        $port = "3306";
        $db   = "viewitdb";
        $user = "root";
        $pass = "root";

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


