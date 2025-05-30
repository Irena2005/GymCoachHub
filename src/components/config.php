<?php
// початок сесії
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// повідомлення про помилку
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// підключення до БД
$mysqli = new mysqli("mysql", "root", "rootpassword", "db_sport");
$mysqli->set_charset("utf8mb4");//кодування

if ($mysqli->connect_errno) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["status" => "error", "message" => "Помилка підключення до БД: " . $mysqli->connect_error], JSON_UNESCAPED_UNICODE);
    exit;
}
?>