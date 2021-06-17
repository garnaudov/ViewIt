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

    $uri = $_SERVER["REQUEST_URI"];
    $str = explode('/', $uri);
    $str2 = explode('=',$str[sizeof($str)-1]);

    $currentGalleryName = urldecode($str2[1]);

    $photosByGallery = $gateway->findPhotosByGallery($currentGalleryName);

    $galleryOwner = $gateway->getOwnerOfGallery($currentGalleryName);



?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="grid-gallery.css" media="all">
  <title><php echo $currentGalleryName ?></title>
</head>

<body>



<div class="form-wrapper">
    <form method="POST" class="form" action="upload.php" enctype="multipart/form-data">
      <div class="title">Качване на снимка</div>
      <div class="subtitle">Добавете снимка в галерията</div>
      <div class="input-container ic1">
      <input id="description" name="description" class="input" type="text" placeholder=" " />
        <div class="cut"></div>
        <label for="username" class="placeholder">Описание</label>
      </div>
      <div class="input-container ic2">
        <input id="galleryName" name="galleryName" value="<?php echo $currentGalleryName ?>" type="hidden" />
        <div class="drop-zone">
          <span class="drop-zone__prompt">Провлачете снимката или натиснете на полето.</span>
          <input type="file" id="jsonFile" name="image" class="drop-zone__input">
        </div>
      </div>
      <button class="submit" type="submit" name="submit">Качи!</button>
    </form>
</div>

<div class='grid-gallery'>



<?php
if(count($photosByGallery) == 0) {
      echo <<<GFG
        <div class="alert-login">
            <span id="drop-description__name">Няма добавени снимки към тази галерия</span>
            <span class="closebtn-login" onclick="this.parentElement.style.display='none';">&times;</span> 
        </div>        
        GFG;

    }

  ?>

  <div class="container">

    <?php
    
    for ($i=0; $i<count($photosByGallery); $i++)
     {
       $image = $gateway->findPhotoById($photosByGallery[$i]["photo_id"]);

       $supported_file = array(
               'jpg',
               'jpeg',
               'png'
        );
        $ext = strtolower(pathinfo($image[0]['path'], PATHINFO_EXTENSION));
        if (in_array($ext, $supported_file)) {
            if($galleryOwner[0]["owner"] === $username) {
              echo '
              <div class="gallery-container w-3 h-2">
              <div class="gallery-item">
                <div class="image">
                  <img src="'.$image[0]['path'] .'" alt="nature">
                </div>
                <div class="text">'.$image[0]['description'] .'
                </div>
                
              </div>
              <form method="POST" action="deletePhoto.php" enctype="multipart/form-data">
              <input name="photoId" value="'.$image[0]['id'] .'" type="hidden" />
              <input id="galleryName" name="galleryName" value="'.$currentGalleryName .'" type="hidden" />
              <button type="submit" name="submit" class="deletebtn">&#128465;</button>
            </form> 
            </div>';
            }
            else {
              echo '
              <div class="gallery-container w-3 h-2">
              <div class="gallery-item">
                <div class="image">
                  <img src="'.$image[0]['path'] .'" alt="nature">
                </div>
                <div class="text">'.$image[0]['description'] .'
                </div>
              </div>
            </div>';
            }
           } else {
               continue;
           }
         }
      ?>
</div>


</body>

<script>

    let galleryNameFromFile = "";
    let studentsListfromFile = "";

   
    const onChange = (event) => {
        var reader = new FileReader();
        reader.onload = onReaderLoad;
        reader.readAsText(event.target.files[0]);
    }
 
    document.getElementById('jsonFile').addEventListener('change', onChange);

    document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
  const dropZoneElement = inputElement.closest(".drop-zone");

  dropZoneElement.addEventListener("click", (e) => {
    inputElement.click();
  });

  inputElement.addEventListener("change", (e) => {
    if (inputElement.files.length) {
      updateThumbnail(dropZoneElement, inputElement.files[0]);
    }
  });

  dropZoneElement.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZoneElement.classList.add("drop-zone--over");
  });

  ["dragleave", "dragend"].forEach((type) => {
    dropZoneElement.addEventListener(type, (e) => {
      dropZoneElement.classList.remove("drop-zone--over");
    });
  });

  dropZoneElement.addEventListener("drop", (e) => {
    e.preventDefault();

    if (e.dataTransfer.files.length) {
      inputElement.files = e.dataTransfer.files;
      updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
    }

    dropZoneElement.classList.remove("drop-zone--over");
  });
});

/**
 * Updates the thumbnail on a drop zone element.
 *
 * @param {HTMLElement} dropZoneElement
 * @param {File} file
 */
function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

  // First time - remove the prompt
  if (dropZoneElement.querySelector(".drop-zone__prompt")) {
    dropZoneElement.querySelector(".drop-zone__prompt").remove();
  }

  // First time - there is no thumbnail element
  if (!thumbnailElement) {
    thumbnailElement = document.createElement("div");
    thumbnailElement.classList.add("drop-zone__thumb");
    dropZoneElement.appendChild(thumbnailElement);
  }

  thumbnailElement.dataset.label = file.name;

  // Show thumbnail for image files
  if (file.type.startsWith("image/")) {
    const reader = new FileReader();

    reader.readAsDataURL(file);
    reader.onload = () => {
      thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
    };
  } else {
    thumbnailElement.style.backgroundImage = null;
  }
}
</script>

</html>