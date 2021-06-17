<?php
include 'Gateway.php';
require 'dbconfig.php';

$gateway = new Gateway($conn);

if (isset($_POST['submit'])) {


    $gateway -> insertPhoto("Descriptionnnn", 'Of daea');
    // $file = $_FILES['file'];

    // $fileName = $_FILES['file']['name'];
    // $fileTmpName = $_FILES['file']['tmp_name'];
    // $fileSize = $_FILES['file']['size'];
    // $fileError = $_FILES['file']['error'];
    // $fileType = $_FILES['file']['type'];

    // $fileExt = explode('.', $fileName);
    // $fileActualExt = strtolower(end($fileExt));

    // $allowed = array('jpg', 'jpeg', 'png');

    // if (in_array($fileActualExt, $allowed)) {
    //     if ($fileError === 0) {
    //         if($fileSize < 10000000){
    //             $fileNameNew = uniqid('', true).".".$fileActualExt;
    //             $shaHash = hash('sha256', $fileNameNew);
    //             $firstTwoCharactersHash = substr($shaHash, 0, 2);
    //             $secondTwoCharactersHash = substr($shaHash, 2, 2);
    //             $thirdTwoCharactersHash = substr($shaHash, 4, 2);
    //             $forthTwoCharactersHash = substr($shaHash, 6, 2);


    //             $filePathDestination = 'uploads/'.$firstTwoCharactersHash.'/'.$secondTwoCharactersHash.'/'.$thirdTwoCharactersHash.'/'.$forthTwoCharactersHash;

    //             $fileDestination = 'uploads/'.$firstTwoCharactersHash.'/'.$secondTwoCharactersHash.'/'.$thirdTwoCharactersHash.'/'.$forthTwoCharactersHash.'/'.$fileNameNew;
    //             mkdir($filePathDestination, 0777, true);

    //             move_uploaded_file($fileTmpName, $fileDestination);
    //             header("Location: grid-gallery.php?uploadsuccess");
    //         } else {
    //             echo "Your file is too big!";
    //         }

    //     }
    //     else {}
    }
   