<?php
// database Connection
$host = 'localhost';
$db   = 'event_database';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

//prepared statement
$stmt = $pdo->prepare("SELECT * FROM event ORDER BY event_date, event_time");
$stmt->execute();
$events = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($events);
