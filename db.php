<?php
// DB Credentials
define('DB_SERVER', 'localhost');
define('USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'phploginapp');

// Attempt to connect to MySQL
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, USERNAME, DB_PASSWORD);
} catch (PDOException $e) {
    die("ERROR: could not connect. " . $e->getMessage());
}