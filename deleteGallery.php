<?php
    include 'Gateway.php';
    require 'dbconfig.php';
    $gateway = new Gateway($conn);

    if (isset($_POST['submit']) && isset($_POST['galleryName'])) {

      $gateway -> deleteGalleryByName($_POST['galleryName']);
      header("Location: vied-galleries.php");
      }

?>