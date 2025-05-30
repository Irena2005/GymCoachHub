<?php
// Підключаємо перевірку авторизації
include '../components/auth_check.php';

// Отримуємо user_id із сесії
$user_id = $_SESSION['user_id'];

// Перевіряємо, чи це POST-запит
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо дані з форми
    $weight = isset($_POST['base_weight']) ? floatval($_POST['base_weight']) : null;
    $height = isset($_POST['base_height']) ? intval($_POST['base_height']) : null;

    // Перевіряємо, чи введено коректні дані
    if ($weight !== null && $height !== null && $weight > 0 && $height > 0) {
        // Підключаємося до бази даних (припускаємо, що $mysqli вже ініціалізований у auth_check.php)
        global $mysqli;

        // Перевіряємо, чи вже є базові виміри для користувача
        $stmt = $mysqli->prepare("SELECT id FROM user_base_measurements WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Оновлюємо існуючий запис
            $stmt = $mysqli->prepare("UPDATE user_base_measurements SET weight = ?, height = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
            $stmt->bind_param("dii", $weight, $height, $user_id);
        } else {
            // Додаємо новий запис
            $stmt = $mysqli->prepare("INSERT INTO user_base_measurements (user_id, weight, height) VALUES (?, ?, ?)");
            $stmt->bind_param("idi", $user_id, $weight, $height);
        }

        // Виконуємо запит
        if ($stmt->execute()) {
            // Перенаправляємо назад на профіль із повідомленням про успіх
            header("Location: my-profile.php?success=Базові виміри успішно оновлено!");
        } else {
            // Перенаправляємо з повідомленням про помилку
            header("Location: my-profile.php?error=Помилка при оновленні базових вимірів: " . $mysqli->error);
        }
        $stmt->close();
    } else {
        // Перенаправляємо з повідомленням про некоректні дані
        header("Location: my-profile.php?error=Будь ласка, введіть коректні значення ваги та зросту.");
    }
    exit();
}
?>