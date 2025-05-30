<?php
// Підключаємо перевірку авторизації
include '../components/auth_check.php';

// Отримуємо user_id із сесії
$user_id = $_SESSION['user_id'];

// Перевіряємо, чи це POST-запит
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо дані з форми
    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : null;
    $height = isset($_POST['height']) ? intval($_POST['height']) : null;

    // Перевіряємо, чи введено коректні дані
    if ($weight !== null && $height !== null && $weight > 0 && $height > 0) {
        // Підключаємося до бази даних (припускаємо, що $mysqli ініціалізований у auth_check.php)
        global $mysqli;

        // Додаємо новий запис у таблицю user_measurements
        $stmt = $mysqli->prepare("INSERT INTO user_measurements (user_id, weight, height) VALUES (?, ?, ?)");
        $stmt->bind_param("idi", $user_id, $weight, $height);

        // Виконуємо запит
        if ($stmt->execute()) {
            // Перенаправляємо назад із повідомленням про успіх
            header("Location: my-profile.php?success=Поточні виміри успішно збережено!");
        } else {
            // Перенаправляємо з повідомленням про помилку
            header("Location: my-profile.php?error=Помилка при збереженні вимірів: " . $mysqli->error);
        }
        $stmt->close();
    } else {
        // Перенаправляємо з повідомленням про некоректні дані
        header("Location: my-profile.php?error=Будь ласка, введіть коректні значення ваги та зросту.");
    }
    exit();
}
?>