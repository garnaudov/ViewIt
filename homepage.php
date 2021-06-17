<?php
    session_start();
    $username = $_SESSION['username'];
    if(!isset($_SESSION['username'])){
        include 'navigation-bar-not-logged.php';
    }
    else {
        include 'navigation-bar-logged.php';
    }
?>
<head>
    <link rel="stylesheet" href="homepage.styles.css">
</head>
<header class="homepage-header">
  <div class="title">
    <h1>Уеб галерия</h1>
        <p><?php
        if(!isset($_SESSION['username'])){
            echo 'За да използвате всички функционалности, моля влезте в системата!';
        }
        else{
            echo "Добре дошли, $username!";
        }
        ?>
    </p>
  </div>
</header>

