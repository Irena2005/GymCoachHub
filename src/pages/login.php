<?php
require '../components/config.php';


// Перевірка, чи користувач уже авторизований
if (isset($_SESSION['user_number'])) {
    header("Location: my-profile.php");
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone_number = trim(isset($_POST['phone_number']) ? $_POST['phone_number'] : '');

    if (empty($phone_number)) {
        $errors[] = "Номер телефону обов’язковий";
    } elseif (!preg_match('/^\+?\d{10,15}$/', $phone_number)) {
        $errors[] = "Номер телефону має бути валідним (10-15 цифр)";
    } else {
        $stmt = $mysqli->prepare("SELECT id, user_name FROM users WHERE phone_number = ?");
        if (!$stmt) {
            $errors[] = "Помилка підготовки запиту: " . $mysqli->error;
        } else {
            $stmt->bind_param("s", $phone_number);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_number'] = $phone_number;
                $_SESSION['user_name'] = $row['user_name'];
                header("Location: my-profile.php");
                exit();
            } else {
                $errors[] = "Користувач з таким номером не знайдений!";
            }
            $stmt->close();
        }
    }
}


$page_title = "Вхід";
?>
<?php include '../components/header.php'; ?>
<?php include '../components/guest_navbar.php'; ?>

    <div class="main">
        <h2>Вхід</h2>

        <?php include '../components/messages.php'; ?>

        <form method="POST" action="login.php">
            <label for="phone_number">Номер телефону:</label>
            <input type="tel" id="phone_number" name="phone_number" required>
            <button type="submit">Увійти</button>
            <h3>Ще не зареєстровані? <a href="signup.php">Реєстрація</a></h3>
        </form>
    </div>

<?php include '../components/footer.php'; ?>