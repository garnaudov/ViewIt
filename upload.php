<?php
    include 'Gateway.php';
    require 'dbconfig.php';
    $gateway = new Gateway($conn);


    if (isset($_POST['submit']) && isset($_POST['description']) && isset($_FILES['image'])) {

      $gateway -> insertPhoto($_POST['description'], $_POST['galleryName']);
      header("Location: grid-gallery.php?gallery=".urlencode($_POST['galleryName']));
      }

?>
   