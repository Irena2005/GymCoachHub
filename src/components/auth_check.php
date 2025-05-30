<?php
// auth_check.php
require '../components/config.php';


// Перевірка авторизації користувача
if (!isset($_SESSION['user_number']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Отримуємо ім’я користувача з сесії
$username = htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8');


?>