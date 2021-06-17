
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


?>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <link rel="stylesheet" href="drag-drop-module.styles.css">
</head>

<body>

<div class="form-wrapper">
    <form method="POST" class="form" action="uploadJSON.php" enctype="multipart/form-data">
      <div class="title">Създаване на галерия</div>
      <div class="subtitle">Добавете галерия като качите JSON</div>
      <div class="input-container ic1">
      <div class="input-container ic2">
        <div class="drop-zone">
          <span class="drop-zone__prompt">Провлачете JSON или натиснете на полето.</span>
          <input type="file" id="jsonFile" name="gallery" class="drop-zone__input">
        </div>
      </div>
      <button class="submit" type="submit" name="submit">Качи!</button>
    </form>
</div>

<div class="alert" id="drop-description__name__wrapper">
    <span id="drop-description__name"></span>
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
</div>

  <span id="drop-description__students">

</span>

</body>

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

        const { galleryName } = obj;


        const dropDescriptionNameWrapper = document.getElementById('drop-description__name__wrapper');
        const dropDescriptionName = document.getElementById('drop-description__name');

        dropDescriptionName.innerHTML = `JSON файлът е успешно качен. Името на галерията е: ${galleryName}`;

        dropDescriptionNameWrapper.style.display = "block";
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

