<?php

try {
    $pdo = new PDO("mysql:host=mysql_8;dbname=ACA;charset=utf8", "root", "rootpassword");
    return $pdo;
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
