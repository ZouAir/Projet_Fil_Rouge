<?php

try {
    $pdo = new PDO("mysql:host=172.21.0.5;dbname=sql_dw2025_zouhair2_stagiairesmns_fr;charset=utf8", "sql_dw2025_zouhair2_stagiairesmns_fr", "7a7eb856234b7");
    return $pdo;
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
