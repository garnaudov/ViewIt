<?php

include 'Gateway.php';
require 'dbconfig.php';

$gateway = new Gateway($conn);

if (isset($_POST['submit'])) {
    $gateway -> createGallery();
} 
 
?>
