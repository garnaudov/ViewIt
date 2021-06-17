<?php
include 'navigation-bar-not-logged.php';
include 'Gateway.php';
require 'dbconfig.php';
$gateway = new Gateway($conn);
session_start();
?>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <link rel="stylesheet" href="login.styles.css">
</head>

<?php

if(isset($_POST['username']) && isset($_POST['password']))
{
    $result = $gateway -> getUserByNameAndPassword($_POST['username'], $_POST['password']);
    if($result){
        $_SESSION['username'] = $_POST['username'];
        if(!isset($_SESSION['username'])){
            echo "You are not logged in!";
        }
        else {
            header("Location: homepage.php");
        }
    }
    else{
        echo <<<GFG
        <div class="alert-login">
            <span id="drop-description__name">Невалидно потребителско име или парола! Опитайте отново!</span>
            <span class="closebtn-login" onclick="this.parentElement.style.display='none';">&times;</span> 
        </div>        
        GFG;
    }
}

?>

<div class="form-wrapper">
    <form method="post" class="form" action="login.php">
      <div class="title">Вход</div>
      <div class="subtitle">Оттук можете да влезете в системата</div>
      <div class="input-container ic1">
        <input id="username" name="username" class="input" type="text" placeholder=" " />
        <div class="cut"></div>
        <label for="username" class="placeholder">Потребителско име</label>
      </div>
      <div class="input-container ic2">
        <input id="password" name="password" class="input" type="password" placeholder=" " />
        <div class="cut cut-short"></div>
        <label for="password" class="placeholder">Парола</label>
      </div>
      <button type="text" class="submit" type="submit" name="submit">Влез</button>
    </form>
</div>