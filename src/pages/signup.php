<?php
require '../components/config.php';


// Перевірка, чи користувач уже авторизований
if (isset($_SESSION['user_number'])) {
    header("Location: my-profile.php");
    exit();
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $user_name = trim(isset($_POST['user_name']) ? $_POST['user_name'] : '');
    $surname = trim(isset($_POST['surname']) ? $_POST['surname'] : '');
    $phone_number = trim(isset($_POST['phone_number']) ? $_POST['phone_number'] : '');
    $age = intval(isset($_POST['age']) ? $_POST['age'] : 0);

    // Валідація даних
    if (empty($user_name)) {
        $errors[] = "Ім’я обов’язкове";
    } elseif (!preg_match('/^[a-zA-Zа-яА-ЯїЇіІєЄґҐ\s]{2,50}$/u', $user_name)) {
        $errors[] = "Ім’я має містити тільки літери (2-50 символів)";
    }

    if (empty($surname)) {
        $errors[] = "Прізвище обов’язкове";
    } elseif (!preg_match('/^[a-zA-Zа-яА-ЯїЇіІєЄґҐ\s]{2,50}$/u', $surname)) {
        $errors[] = "Прізвище має містити тільки літери (2-50 символів)";
    }

    if (empty($phone_number)) {
        $errors[] = "Номер телефону обов’язковий";
    } elseif (!preg_match('/^\+?\d{10,15}$/', $phone_number)) {
        $errors[] = "Номер телефону має бути валідним (10-15 цифр)";
    }

    if (empty($age) || $age < 12 || $age > 100) {
        $errors[] = "Вкажіть коректний вік (12-100)";
    }

    // Перевірка існування користувача
    if (empty($errors)) {
        $stmt = $mysqli->prepare("SELECT phone_number FROM users WHERE phone_number = ?");
        if (!$stmt) {
            $errors[] = "Помилка підготовки запиту: " . $mysqli->error;
        } else {
            $stmt->bind_param("s", $phone_number);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $errors[] = "Користувач з таким номером уже існує!";
            }
            $stmt->close();
        }
    }

    // Реєстрація користувача
    if (empty($errors)) {
        $mysqli->begin_transaction();
        try {
            // Вставка в таблицю users
            $stmt = $mysqli->prepare("INSERT INTO users (user_name, surname, phone_number, age, registration_date) VALUES (?, ?, ?, ?, NOW())");
            if (!$stmt) {
                throw new Exception("Помилка підготовки запиту (users): " . $mysqli->error);
            }
            $stmt->bind_param("sssi", $user_name, $surname, $phone_number, $age);
            $stmt->execute();
            $user_id = $mysqli->insert_id;
            $stmt->close();

            // Вставка в таблицю fitness
            $stmt1 = $mysqli->prepare("INSERT INTO fitness (user_id, lunges_forward, plank, squats, glute_bridge, abs_exercises, push_ups, burpees, mountain_climbers, high_knee_run) VALUES (?, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
            if (!$stmt1) {
                throw new Exception("Помилка підготовки запиту (fitness): " . $mysqli->error);
            }
            $stmt1->bind_param("i", $user_id);
            $stmt1->execute();
            $stmt1->close();

            // Вставка в таблицю hardwork
            $stmt2 = $mysqli->prepare("INSERT INTO hardwork (user_id, back_extension, t_bar_row, dumbbell_row, seated_row, barbell_row, barbell_upright_row, stationary_bike, barbell_lunges, leg_extension, crossover_leg_abduction, barbell_squats, leg_adduction, leg_curl, arm_adduction, dip_bars, crossover_arm, barbell_bench_press, dumbbell_bench_press) VALUES (?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
            if (!$stmt2) {
                throw new Exception("Помилка підготовки запиту (hardwork): " . $mysqli->error);
            }
            $stmt2->bind_param("i", $user_id);
            $stmt2->execute();
            $stmt2->close();

            // Вставка в таблицю pilates
            $stmt3 = $mysqli->prepare("INSERT INTO pilates (user_id, spine_stretch, leg_lift_stretch, scissor_twist, swan_exercise, ball_exercise, pelvic_circles, swimming_exercise, kneeling_leg_extension, shoulder_bridge, push_ups, basket_exercise, boomerang_exercise) VALUES (?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
            if (!$stmt3) {
                throw new Exception("Помилка підготовки запиту (pilates): " . $mysqli->error);
            }
            $stmt3->bind_param("i", $user_id);
            $stmt3->execute();
            $stmt3->close();

            // Підтверджуємо транзакцію
            $mysqli->commit();

            // Автоматичний вхід
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_number'] = $phone_number;
            $_SESSION['user_name'] = $user_name;
            $success = "Реєстрація успішна! Ласкаво просимо, " . htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
            header("Location: my-profile.php");
            exit();
        } catch (Exception $e) {
            $mysqli->rollback();
            $errors[] = "Помилка при реєстрації: " . $e->getMessage();
        }
    }
}


$page_title = "Реєстрація";
?>
<?php include '../components/header.php'; ?>
<?php include '../components/guest_navbar.php'; ?>

    <div class="main">
        <h2>Реєстрація</h2>

        <?php include '../components/messages.php'; ?>

        <form method="POST" action="signup.php">
            <label for="user_name">Ім’я:</label>
            <input type="text" id="user_name" name="user_name" required>

            <label for="surname">Прізвище:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="phone_number">Номер телефону:</label>
            <input type="tel" id="phone_number" name="phone_number" required>

            <label for="age">Вік:</label>
            <input type="number" id="age" name="age" min="12" max="100" required>

            <button type="submit" name="register">Зареєструватися</button>

            <h3>Вже маєте акаунт? <a href="login.php">Вхід</a></h3>
        </form>
    </div>

<?php include '../components/footer.php'; ?>