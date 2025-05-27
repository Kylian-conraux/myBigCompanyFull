<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

    define("SERVER", "localhost");
    define("DB", "mybigcompany");
    define("USER", "kylian");
    define("PASSWORD", "Ardagon2901_");
    $db = new PDO('mysql:host='.SERVER.';dbname='.DB, USER, PASSWORD);
    
?>