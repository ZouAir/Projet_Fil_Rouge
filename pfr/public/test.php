<?php
echo "Test OK<br>";
echo "Fichier : " . __FILE__ . "<br>";
echo "Dossier : " . __DIR__ . "<br>";
echo "DOCUMENT_ROOT : " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "MySQL test : ";

try {
    $pdo = require_once('./includes/bdd.php');
    echo "BDD OK";
} catch (Exception $e) {
    echo "BDD ERROR: " . $e->getMessage();
}
