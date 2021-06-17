<?php
    include 'Gateway.php';
    require 'dbconfig.php';

    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
    }
    else {
        include 'navigation-bar-logged.php';
        $username = $_SESSION['username'];
    }

    $gateway = new Gateway($conn);
    $galleries = $gateway->findGalleriesByUsername($username);

    $array = array("w-3 h-2", "w-3 h-3", "h-2", "w-2", "w-4 h-1", "");
    $index = 0;


?>
<head>
    <link rel="stylesheet" href="grid-gallery.css">
</head>

<div class="wrapper-full">
    <div class='grid-gallery'>
        <div class="container">


        <?php
            foreach ($galleries as $value)
        {
            $galleryOwner = $gateway->getOwnerOfGallery($value['gallery_name']);           

            ?>
            <div class="gallery-container <?php echo $array[$index++ % 10]?>">
                <div onclick="location.href='grid-gallery.php?gallery=<?php echo $value['gallery_name']?>';" style="cursor: pointer;" class="gallery-item">
                    <div class="image">
                    <img src="./assets/jamie-street-qWYvQMIJyfE-unsplash.jpg" alt="<?php echo $value["gallery_name"]?>">
                    </div>
                    <div class="text"><?php echo $value["gallery_name"]?></div>
                </div>
                <?php 
                if($galleryOwner[0]["owner"] == $username) {
                    ?>
                    <form method="POST" action="deleteGallery.php" enctype="multipart/form-data">
                    <input id="galleryName" name="galleryName" value="<?php echo $value['gallery_name']?>" type="hidden" />
                    <button type="submit" name="submit" class="deletebtn">&#128465;</button>
                  </form>
                  <?php

                }
                 ?>
            </div>      
        <?php
        }      
        ?>
    </div>  
</div>


