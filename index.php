<?php

include 'Controller.php';
require 'dbconfig.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /person
// everything else results in a 404 Not Found
// if ($uri[2] !== 'insert' && $uri[2] !== 'create') {
//     header("HTTP/1.1 404 Not Found");
//     exit();
// }

// the user id is, of course, optional and must be a number:
$description = null;
$galleryName = null;
// if (isset($uri[2])) {
//     $description = $uri[2];
// }

// if (isset($uri[3])) {
//     $galleryName = $uri[3];
// }
if(isset($_POST['description'])){
$description = $_POST['description'];
}
if(isset($_POST['gallery'])){
$galleryName = $_POST['gallery'];
}

// if(isset($_FILES['gallery'])) {
//     $json = true;
// // $fileName = $_FILES['gallery']['name'];
// //     $fileTmpName = $_FILES['gallery']['tmp_name'];
// //     $fileExt = explode('.', $fileName);
// //     $fileActualExt = strtolower(end($fileExt));
// //     $fileNameNew = uniqid('', true);

// //     $fileDestination = 'JSONFiles/'.$fileNameNew.".".$fileActualExt;


// //     move_uploaded_file($fileTmpName, $fileDestination);

// //     $str = file_get_contents($fileDestination);
// //     $json = json_decode($str, true);
// } else {
    
// //     $img_data=$_FILES['image'];
// // $fileName = $_FILES['image']['name'];
// // $fileTmpName = $_FILES['image']['tmp_name'];
// // $fileExt = explode('.', $fileName);
// // $fileActualExt = strtolower(end($fileExt));
// // $fileNameNew = uniqid('', true);

// // $fileDestination = 'JSONFiles/'.$fileNameNew.".".$fileActualExt;


// // move_uploaded_file($fileTmpName, $fileDestination);

// // $str = file_get_contents($fileDestination);
// // $json = json_decode($str, true);
// }


$requestMethod = $_SERVER["REQUEST_METHOD"];


// pass the request method and user ID to the PersonController and process the HTTP request:
$controller = new Controller($conn);
$controller->processPostRequest($description, $galleryName);