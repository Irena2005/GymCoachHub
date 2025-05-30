<?php
require '../components/config.php';
header("Content-Type: application/json; charset=utf-8");

try {
    $json = file_get_contents("php://input");
    if ($json === false) {
        throw new Exception("Не вдалося прочитати дані запиту");
    }

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Невірний JSON формат");
    }

    $phone_number = isset($data['phone_number']) ? $data['phone_number'] : '';
    if (empty($phone_number)) {
        throw new Exception("Номер телефону обов’язковий");
    }

    $stmt = $mysqli->prepare("SELECT id, user_name FROM users WHERE phone_number = ?");
    if (!$stmt) {
        throw new Exception("Помилка підготовки запиту: " . $mysqli->error);
    }

    $stmt->bind_param("s", $phone_number);
    if (!$stmt->execute()) {
        throw new Exception("Помилка виконання запиту: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "status" => "success",
            "message" => "Користувач знайдений",
            "id" => $row['id'],
            "name" => $row['user_name']
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Користувача не знайдено"
        ], JSON_UNESCAPED_UNICODE);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>