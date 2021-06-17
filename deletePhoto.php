<?php
    include 'Gateway.php';
    require 'dbconfig.php';
    $gateway = new Gateway($conn);

    if (isset($_POST['submit']) && isset($_POST['photoId'])) {

      $gateway -> deletePhotoById($_POST['photoId']);
      header("Location: grid-gallery.php?gallery=".urlencode($_POST['galleryName']));
      }

?>