<?php

// $file = request()->fileinput;    // <input type="file" name="fileinput" />
// $content = file_get_contents($file);
// $json = json_decode($content, true);

$file = $_FILES['file'];

$fileName = $_FILES['file']['name'];
print_r($fileName);


?>
