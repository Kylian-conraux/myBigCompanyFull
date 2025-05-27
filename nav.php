<?php

$conn = "CONNEXION";
$connected = false;
if (isset($_SESSION['user'])) {
    $conn = "DECONNEXION";
    $connected = true;
}

function displayNavbar($conn)
{
    echo '<div class="navbar-nav">
            <a class="nav-link" href="./index.php">GESTION EMPLOYES</a>
            <a class="nav-link " href="./services.php">GESTION SERVICES</a>
            <a class="nav-link " href="./users.php">GESTION USERS</a>
            <a class="nav-link " href="./login.php">' . $conn . '</a>
        </div>';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">MyBigCompany</a>
        <?php
            if($connected){
                displayNavbar($conn);
            }
        ?>
    </div>
</nav>