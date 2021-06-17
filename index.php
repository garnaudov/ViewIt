<?php

include 'Controller.php';
require 'dbconfig.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$description = null;
$galleryName = null;

if (isset($_POST['description'])) {
    $description = $_POST['description'];
}
if (isset($_POST['gallery'])) {
    $galleryName = $_POST['gallery'];
}

if (!isset($_POST['description']) || !isset($_POST['gallery']) || !isset($_FILES['image'])) {
    if (!isset($_FILES['gllery'])) {
        header("HTTP/1.1 400 Bad Request");
        exit();
    }
}

$controller = new Controller($conn);
$controller->processPostRequest($description, $galleryName);