<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);

if($loggedIn){
    $accountText = "Account";
    $accountLink = "Account.php";
}else {
    $accountText = "Login/Register";
    $accountLink = "Register.php";
}

echo <<< NAVHTML
    <div id="header">
        <div class = "container">
            <nav class = "nav-box">
                <h3><a class="home" href="Index.php">Street Share</a></h3>
                <ul id= "sidemenu">
                <li><a class ="about" href="#info">Über uns </a></li>
                <li><a class ="cars" href="#cars">Unsere Autos </a></li>
                <li><a class="login/register" href="$accountLink">$accountText</a></li>
                </ul>
            </nav>
        </div>
    </div>
NAVHTML;