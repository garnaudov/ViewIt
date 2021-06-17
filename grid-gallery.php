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
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="grid-gallery.css" media="all">
  <title>CSS Grid</title>
</head>

<body>

<!-- <form action="upload.php" method="POST" enctype="multipart/form-data">
  <input type="file" id="myFile" name="file">
  <button type="submit" name="submit">Upload</button>
</form> -->

<div class='grid-gallery'>

<?php
  echo $_GET['gallery'];
?>


<h1>Php is my passion</h1>
  <div class="container">

    <?php
    $files = glob("uploads/*.*");
    for ($i=0; $i<count($files); $i++)
     {
       $image = $files[$i];
       $supported_file = array(
               'jpg',
               'jpeg',
               'png'
        );

        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (in_array($ext, $supported_file)) {
            echo '
            <div class="gallery-container w-3 h-2">
            <div class="gallery-item">
              <div class="image">
                <img src="'.$image .'" alt="nature">
              </div>
              <div class="text">Sample text</div>
            </div>
          </div>';
           } else {
               continue;
           }
         }
      ?>
</div>

  
<!--     

    <div class="gallery-container w-3 h-2">
      <div class="gallery-item">
        <div class="image">
          <img src="https://source.unsplash.com/1600x900/?nature" alt="nature">
        </div>
        <div class="text">Nature</div>
      </div>
    </div>

    <div class="gallery-container w-3 h-3">
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
    </div>

    <div class="gallery-container h-2">
      <div class="gallery-item">
        <div class="image">
          <img src="https://source.unsplash.com/1600x900/?art" alt="art">
        </div>
        <div class="text">Art</div>
      </div>
    </div>

    <div class="gallery-container">
      <div class="gallery-item">
        <div class="image">
          <img src="https://source.unsplash.com/1600x900/?cars" alt="cars">
        </div>
        <div class="text">Cars</div>
      </div>
    </div>

  </div> -->
</body>

</html>