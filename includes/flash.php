<?php
/*
Se renseigner sur :

session_start();
header("Location: index.php");
exit();

*/


function setFlash($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

function hasFlash() {
    return isset($_SESSION['flash']);
}

function getFlash() {
    if (hasFlash()) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function displayFlash() {
    if (hasFlash()) {
        $flash = getFlash();
        $type = $flash['type'];
        $message = $flash['message'];
        
        $alertClass = match($type) {
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info',
            default => 'alert-info'
        };
        echo "<div class='alert {$alertClass} alert-dismissible fade show' role='alert'>{$message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
}