<?php

include 'Gateway.php';
require 'dbconfig.php';

class Controller {

    private $db;
    private $gateway;


    public function __construct($db)
    {
        $this->db = $db;
        //$this->gallery = $gallery;
        //$this->file = $file;
        $this->gateway = new Gateway($db);
    }

    public function processPostRequest($description, $galleryName) {
        if ($galleryName && $description) {
            $this->gateway->insertPhoto($description, $galleryName);   
        } else {
            $this->gateway->createGallery();
        }
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}