<?php
try {
    $pdo = new PDO(
        "mysql:host=db:3306;dbname=ACA;charset=utf8",
        "zou",
        "aca2026"
    );
    return $pdo;
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
