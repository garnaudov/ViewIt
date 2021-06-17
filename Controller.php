<?php

include 'Gateway.php';
require 'dbconfig.php';

class Controller {

    private $db;
    private $gateway;


    public function __construct($db)
    {
        $this->db = $db;
        $this->gateway = new Gateway($db);
    }

    public function processPostRequest($description, $galleryName) {
        if ($galleryName && $description) {
            $this->gateway->insertPhoto($description, $galleryName);   
        } else {
            $this->gateway->createGallery();
        }
    }
}