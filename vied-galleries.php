<?php
    include 'Gateway.php';
    require 'dbconfig.php';

    session_start();
    $username = $_SESSION['username'];
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
    }
    else {
        include 'navigation-bar-logged.php';
    }

    $gateway = new Gateway($conn);
    $galleries = $gateway->findGalleriesByUsername($username);

    $array = array("w-3 h-2", "w-3 h-3", "h-2", "w-2", "w-4 h-1", "");
    $index = 0;


?>
<head>
    <link rel="stylesheet" href="grid-gallery.css">
</head>
<?php
    echo $username;
?>

<div class="wrapper-full">
    <div class='grid-gallery'>
        <div class="container">


        <?php
            foreach ($galleries as $value)
        {

            ?>
            <div class="gallery-container <?php echo $array[$index++ % 10]?>">
                <div onclick="location.href='grid-gallery.php?gallery=<?php echo $value['gallery_name']?>';" style="cursor: pointer;" class="gallery-item">
                    <div class="image">
                    <img src="./assets/rirri-z4KhbVhPP7s-unsplash.jpg" alt="<?php echo $value["gallery_name"]?>">
                    </div>
                    <div class="text"><?php echo $value["gallery_name"]?></div>
                </div>
            </div>      
        <?php
        }      
        ?>



            <!-- <div class="gallery-container w-3 h-3">
            <div class="gallery-item">
                <div class="image">
                <img src="https://source.unsplash.com/1600x900/?people" alt="people">
                </div>
                <div class="text">People</div>
            </div>
            </div>

            <div class="gallery-container h-2">
            <div class="gallery-item">
                <div class="image">
                <img src="https://source.unsplash.com/1600x900/?sport" alt="sport">
                </div>
                <div class="text">Sport</div>
            </div>
            </div>

            <div class="gallery-container w-2">
            <div class="gallery-item">
                <div class="image">
                <img src="https://source.unsplash.com/1600x900/?fitness" alt="fitness">
                </div>
                <div class="text">Fitness</div>
            </div>
            </div>

            <div class="gallery-container w-4 h-1">
            <div class="gallery-item">
                <div class="image">
                <img src="https://source.unsplash.com/1600x900/?food" alt="food">
                </div>
                <div class="text">Food</div>
            </div>
            </div>

            <div class="gallery-container">
            <div class="gallery-item">
                <div class="image">
                <img src="https://source.unsplash.com/1600x900/?travel" alt="travel">
                </div>
                <div class="text">Travel</div>
            </div>
        </div> -->
    </div>
</div>


