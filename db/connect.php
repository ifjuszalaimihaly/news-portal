<?php
defined('BASEPATH') or exit('No direct script access allowed');

$host = 'localhost';
$db   = 'c2_test002';
$user = 'c2_test002';
$pass = 'MyCwxv_p3GAAa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}