
<?php
include 'Gateway.php';
require 'dbconfig.php';

session_start();
if(!isset($_SESSION['username'])){
    include 'navigation-bar-not-logged.php';
}
else {
    include 'navigation-bar-logged.php';
}

$gateway = new Gateway($conn);
$names = $gateway->findGalleriesByUsername('gkarnaudov');

?>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <link rel="stylesheet" href="drag-drop-module.styles.css">
</head>

<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" id="myFile" name="file">
    <button type="submit" name="submit">Upload</button>
</form>

<?php

// output data of each row
foreach ($names as $value) 
{

// }
// while($name = $names->fetch_assoc()) 
// {
    
    ?>
    <p><?php echo $value["gallery_name"]?>
    </p>
	
    <?php
                }
            
            ?>






<body>
  <div class="drop-zone">
    <span class="drop-zone__prompt">Drop the JSON file here or click to upload</span>
    <input type="file" id="jsonFile" name="myFile" class="drop-zone__input">
  </div>

<div class="alert" id="drop-description__name__wrapper">
    <span id="drop-description__name"></span>
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
</div>

  <span id="drop-description__students">

</span>

  <!-- <script src="./src/main.js"></script> -->
</body>

<!-- <form>
    <input type="file" name="jsonFile" id="jsonFile">
    <div id="result"></div>
</form> -->

<script>

    let galleryNameFromFile = "";
    let studentsListfromFile = "";

   
    const onChange = (event) => {
        var reader = new FileReader();
        reader.onload = onReaderLoad;
        reader.readAsText(event.target.files[0]);
    }

    const onReaderLoad = (event) => {
        const obj = JSON.parse(event.target.result);

        const { galleryName, studentsList, studentsNamesList} = obj;

        galleryNameFromFile = galleryName;
        studentsListfromFile = studentsList;

        console.log("galleryName: ", galleryName);
        console.log("student Names: ", studentsList);
        console.log("students: ", studentsList);

        const dropDescriptionNameWrapper = document.getElementById('drop-description__name__wrapper');
        const dropDescriptionName = document.getElementById('drop-description__name');

        dropDescriptionName.innerHTML = `JSON file successfully uploaded with the following gallery name: ${galleryName}`;

        dropDescriptionNameWrapper.style.display = "block";
        // dropDescriptionStudents.style.display = "block";
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

