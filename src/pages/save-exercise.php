<?php
require '../components/config.php';
header("Content-Type: application/json; charset=utf-8");

try {
    // Перевірка наявності user_id в сесії
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Користувач не залогінений");
    }

    $user_id = $_SESSION['user_id'];

    // Отримуємо JSON-дані з запиту
    $json = file_get_contents("php://input");
    if ($json === false) {
        throw new Exception("Не вдалося прочитати дані запиту");
    }

    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Невірний JSON формат");
    }

    // Перевірка обов’язкових полів
    $trainingType = isset($data['trainingType']) ? $data['trainingType'] : '';
    $exercises = isset($data['exercises']) ? $data['exercises'] : [];

    if (empty($trainingType)) {
        throw new Exception("Тип тренування обов’язковий");
    }
    if (empty($exercises)) {
        throw new Exception("Виберіть хоча б одну вправу");
    }

    // Допустимі типи тренувань
    $allowedTypes = ['hardwork', 'fitness', 'pilates'];
    if (!in_array($trainingType, $allowedTypes)) {
        throw new Exception("Непідтримуваний тип тренування: " . htmlspecialchars($trainingType, ENT_QUOTES, 'UTF-8'));
    }

    // Визначаємо поля для відповідної таблиці
    $fields = [];
    if ($trainingType === 'hardwork') {
        $fields = [
            'back_extension', 't_bar_row', 'dumbbell_row', 'seated_row', 'barbell_row',
            'barbell_upright_row', 'stationary_bike', 'barbell_lunges', 'leg_extension',
            'crossover_leg_abduction', 'barbell_squats', 'leg_adduction', 'leg_curl',
            'arm_adduction', 'dip_bars', 'crossover_arm', 'barbell_bench_press',
            'dumbbell_bench_press'
        ];
    } elseif ($trainingType === 'fitness') {
        $fields = [
            'lunges_forward', 'plank', 'squats', 'glute_bridge', 'abs_exercises',
            'push_ups', 'burpees', 'mountain_climbers', 'high_knee_run'
        ];
    } elseif ($trainingType === 'pilates') {
        $fields = [
            'spine_stretch', 'leg_lift_stretch', 'scissor_twist', 'swan_exercise',
            'ball_exercise', 'pelvic_circles', 'swimming_exercise', 'kneeling_leg_extension',
            'shoulder_bridge', 'push_ups', 'basket_exercise', 'boomerang_exercise'
        ];
    }

    // Формуємо SQL-запит для оновлення вибраних вправ
    $updates = [];
    foreach ($exercises as $exercise) {
        $field = str_replace(" ", "_", strtolower($exercise));
        if (in_array($field, $fields)) {
            $updates[] = "`$field` = `$field` + 1";
        }
    }

    if (empty($updates)) {
        throw new Exception("Жодна з вибраних вправ не відповідає полям таблиці");
    }

    // Оновлення даних у таблиці
    $sql = "UPDATE `$trainingType` SET " . implode(", ", $updates) . " WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Помилка підготовки запиту: " . $mysqli->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Помилка виконання запиту: " . $stmt->error);
    }
    $stmt->close();

    // Успішна відповідь
    echo json_encode([
        "status" => "success",
        "message" => "Вправи успішно збережено"
    ], JSON_UNESCAPED_UNICODE); die;

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>