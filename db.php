<?php
$host = 'localhost';
$db   = 'skillswap';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // First, connect without DB selected to create it if it doesn't exist
    $pdo_setup = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    $pdo_setup->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    
    // Now connect to the database
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a real app, you might log this instead of showing it
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
