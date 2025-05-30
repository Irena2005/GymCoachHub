<?php
// Включаємо файл з перевіркою авторизації - переконуємося, що користувач залогінений
global $mysqli;
include '../components/auth_check.php';
// Встановлюємо заголовок сторінки
$page_title = "Мій профіль";
// Отримуємо ID користувача з сесії (попередньо збережений при вході)
$user_id = $_SESSION['user_id'];
// Отримуємо номер телефону користувача з сесії
$phone_number = $_SESSION['user_number'];

// Додатково перевіряємо дані користувача в базі даних для безпеки
$stmt = $mysqli->prepare("SELECT user_name FROM users WHERE id = ? AND phone_number = ?");
if ($stmt) { // Перевіряємо, чи вдало підготовлений запит
    // Прив'язуємо параметри до запиту: i - integer (для user_id), s - string (для номера телефону)
    $stmt->bind_param("is", $user_id, $phone_number);
    // Виконуємо запит до бази даних
    $stmt->execute();
    // Отримуємо результат запиту
    $result = $stmt->get_result();
    // Перевіряємо, чи є результат (чи існує користувач з такими даними)
    if ($row = $result->fetch_assoc()) {
        // Отримуємо ім'я користувача і екрануємо спеціальні символи для безпеки
        $username = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
    } else {
        // Якщо користувача з такими даними немає, видаляємо сесію і перенаправляємо на сторінку входу
        session_unset(); // Очищаємо змінні сесії
        session_destroy(); // Знищуємо сесію
        header("Location: login.php"); // Перенаправляємо на сторінку входу
        exit(); // Зупиняємо виконання скрипта
    }
    // Закриваємо підготовлений запит для звільнення ресурсів
    $stmt->close();
} else {
    // Якщо сталася помилка при підготовці запиту, зберігаємо текст помилки
    $errors[] = "Помилка підготовки запиту: " . $mysqli->error;
}

// Отримуємо базові виміри користувача (початкові значення ваги і зросту)
$base_measurements = []; // Створюємо пустий масив для зберігання базових вимірів
$stmt = $mysqli->prepare("SELECT weight, height FROM user_base_measurements WHERE user_id = ?");
if ($stmt) { // Перевіряємо успішність підготовки запиту
    // Прив'язуємо ID користувача до запиту
    $stmt->bind_param("i", $user_id);
    // Виконуємо запит
    $stmt->execute();
    // Отримуємо результат
    $result = $stmt->get_result();
    // Якщо є результат, зберігаємо його в масив базових вимірів
    if ($row = $result->fetch_assoc()) {
        $base_measurements = $row;
    }
    // Закриваємо запит
    $stmt->close();
}

// Отримуємо останній запис вимірів користувача
$latest_measurement = []; // Створюємо пустий масив для зберігання останнього виміру
// Підготовка запиту, що вибирає останній запис з таблиці user_measurements для поточного користувача
$query = "SELECT weight, height, measurement_date FROM user_measurements WHERE user_id = ? ORDER BY measurement_date DESC LIMIT 1";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    // Прив'язуємо ID користувача до запиту
    $stmt->bind_param("i", $user_id);
    // Виконуємо запит
    $stmt->execute();
    // Отримуємо результат
    $result = $stmt->get_result();
    // Якщо є результат, зберігаємо його в масив останнього виміру
    if ($row = $result->fetch_assoc()) {
        $latest_measurement = $row;
    }
    // Закриваємо запит
    $stmt->close();
}

// Отримуємо історію всіх вимірів користувача
$measurements = []; // Створюємо пустий масив для зберігання історії вимірів
$query = "SELECT weight, height, measurement_date FROM user_measurements WHERE user_id = ? ORDER BY measurement_date DESC";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    // Прив'язуємо ID користувача до запиту
    $stmt->bind_param("i", $user_id);
    // Виконуємо запит
    $stmt->execute();
    // Отримуємо результат
    $result = $stmt->get_result();
    // Заповнюємо масив measurements всіма записами з результату
    while ($row = $result->fetch_assoc()) {
        $measurements[] = $row; // Додаємо кожний запис до масиву
    }
    // Закриваємо запит
    $stmt->close();
}

// Перевіряємо, чи є повідомлення про успіх або помилку в URL (передані через GET)
$success_message = isset($_GET['success']) ? htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') : '';
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : '';
?>
<?php
// Підключаємо шапку сайту
include '../components/header.php';
// Підключаємо навігаційне меню
include '../components/navbar.php';
?>

    <!-- Підключаємо CSS-файл для сторінки профілю -->
    <link rel="stylesheet" href="/src/styles/my-profile.css">
    <header>
        <!-- Виводимо привітання з ім'ям користувача -->
        <p class="tagline">Вітаємо, <?= $username ?>!</p>
    </header>

    <main class="container">
        <section class="profile-container">
            <div class="info-box">
                <h2>Інформація</h2>
                <!-- Перевіряємо, чи є базові виміри, і виводимо їх -->
                <?php if (!empty($base_measurements)): ?>
                    <!-- Виводимо базову вагу -->
                    <p><strong>Базова вага:</strong> <?= $base_measurements['weight'] ?> кг</p>
                    <!-- Виводимо базовий зріст -->
                    <p><strong>Базовий зріст:</strong> <?= $base_measurements['height'] ?> см</p>
                <?php else: ?>
                    <!-- Виводимо повідомлення, якщо базові виміри не встановлені -->
                    <p>Базові виміри ще не встановлено.</p>
                <?php endif; ?>

                <!-- Виводимо останній вимір і різницю з базовим, якщо обидва доступні -->
                <?php if (!empty($latest_measurement) && !empty($base_measurements)): ?>
                    <p><strong>Останній вимір:</strong></p>
                    <!-- Виводимо останню вагу і різницю з базовою -->
                    <p>Вага: <?= $latest_measurement['weight'] ?> кг
                        (Різниця: <?= number_format($latest_measurement['weight'] - $base_measurements['weight'], 1) ?>
                        кг)</p>
                    <!-- Виводимо останній зріст і різницю з базовим -->
                    <p>Зріст: <?= $latest_measurement['height'] ?> см
                        (Різниця: <?= $latest_measurement['height'] - $base_measurements['height'] ?> см)</p>
                <?php elseif (!empty($base_measurements)): ?>
                    <!-- Виводимо повідомлення, якщо немає останнього виміру -->
                    <p>Останній вимір ще не додано.</p>
                <?php endif; ?>

                <!-- Кнопка для відкриття модального вікна оновлення базових вимірів -->
                <button id="update-base-btn" class="modal-btn">Оновити базові виміри</button>

                <!-- Форма для додавання нових вимірів -->
                <form id="measurements-form" action="save_measurements.php" method="POST">
                    <!-- Поле для введення ваги -->
                    <label>Вага (кг): <input type="number" id="weight" name="weight" min="0" step="0.1"
                                             required></label>
                    <!-- Поле для введення зросту -->
                    <label>Зріст (см): <input type="number" id="height" name="height" min="0" required></label>
                    <!-- Кнопка відправки форми -->
                    <button type="submit" id="update-btn">Зберегти виміри</button>
                </form>

                <!-- Виводимо повідомлення про успіх, якщо воно є -->
                <?php if ($success_message): ?>
                    <p class="success-message"><?= $success_message ?></p>
                <?php endif; ?>
                <!-- Виводимо повідомлення про помилку, якщо воно є -->
                <?php if ($error_message): ?>
                    <p class="error-message"><?= $error_message ?></p>
                <?php endif; ?>
            </div>
            <!-- Виводимо зображення профілю -->
           <!-- <img src="/src/images/ball.jpg" class="profile-img" alt="Профільне зображення">
        </section>-->

        <!-- Секція з таблицею історії вимірів -->
        <section class="measurements">
            <h2>Історія вимірів</h2>
            <!-- Перевіряємо, чи є виміри і базові дані для порівняння -->
            <?php if (!empty($measurements) && !empty($base_measurements)): ?>
                <div class="table-responsive">
                    <!-- Створюємо таблицю для історії вимірів -->
                    <table class="measurements-table">
                        <thead>
                        <tr>
                            <!-- Заголовки стовпців таблиці -->
                            <th>Дата</th>
                            <th>Вага (кг)</th>
                            <th>Різниця у вазі (кг)</th>
                            <th>Зріст (см)</th>
                            <th>Різниця у зрості (см)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Циклом проходимо по всіх вимірах і виводимо їх у таблицю -->
                        <?php foreach ($measurements as $m): ?>
                            <tr>
                                <!-- Форматуємо дату виміру -->
                                <td><?= date('d.m.Y H:i', strtotime($m['measurement_date'])) ?></td>
                                <!-- Виводимо вагу -->
                                <td><?= $m['weight'] ?></td>
                                <!-- Обчислюємо і виводимо різницю з базовою вагою, округлюємо до 1 десяткового знаку -->
                                <td><?= number_format($m['weight'] - $base_measurements['weight'], 1) ?></td>
                                <!-- Виводимо зріст -->
                                <td><?= $m['height'] ?></td>
                                <!-- Обчислюємо і виводимо різницю з базовим зростом -->
                                <td><?= $m['height'] - $base_measurements['height'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- Виводимо повідомлення, якщо немає даних для таблиці -->
                <p class="no-data">Немає даних про виміри.</p>
            <?php endif; ?>
        </section>

        <!-- Модальне вікно для оновлення базових вимірів -->
        <div id="base-measurements-modal" class="modal">
            <div class="modal-content">
                <!-- Кнопка закриття модального вікна -->
                <span id="close-modal" class="close">×</span>
                <h2>Оновити базові виміри</h2>
                <!-- Форма для оновлення базових вимірів -->
                <form id="base-measurements-form" action="update_measurements.php" method="POST">
                    <!-- Поле для введення базової ваги -->
                    <label>Базова вага (кг): <input type="number" name="base_weight" min="0" step="0.1"
                                                    required></label>
                    <!-- Поле для введення базового зросту -->
                    <label>Базовий зріст (см): <input type="number" name="base_height" min="0" required></label>
                    <!-- Кнопка відправки форми -->
                    <button type="submit">Зберегти</button>
                </form>
            </div>
        </div>

        <!-- Секція тренувань користувача -->
        <section class="trainings">
            <h2>Ваші тренування</h2>
            <?php
            // Отримуємо дані про тренування з фітнесу
            $fitness_data = []; // Створюємо пустий масив для даних фітнесу
            $sql_fitness = "SELECT * FROM fitness WHERE user_id = ?"; // SQL-запит для вибірки даних з таблиці fitness
            $stmt_fitness = $mysqli->prepare($sql_fitness); // Підготовка запиту
            if ($stmt_fitness) { // Перевіряємо успішність підготовки запиту
                $stmt_fitness->bind_param("i", $user_id); // Прив'язуємо ID користувача до запиту
                $stmt_fitness->execute(); // Виконуємо запит
                $result_fitness = $stmt_fitness->get_result(); // Отримуємо результат запиту
                if ($result_fitness->num_rows > 0) { // Якщо є хоча б один рядок у результаті
                    $fitness_data = $result_fitness->fetch_assoc(); // Зберігаємо дані в масив
                }
                $stmt_fitness->close(); // Закриваємо запит
            }

            // Отримуємо дані про силові тренування (аналогічно до fitness)
            $hardwork_data = []; // Створюємо пустий масив для даних силових тренувань
            $sql_hardwork = "SELECT * FROM hardwork WHERE user_id = ?"; // SQL-запит для вибірки даних з таблиці hardwork
            $stmt_hardwork = $mysqli->prepare($sql_hardwork); // Підготовка запиту
            if ($stmt_hardwork) { // Перевіряємо успішність підготовки запиту
                $stmt_hardwork->bind_param("i", $user_id); // Прив'язуємо ID користувача до запиту
                $stmt_hardwork->execute(); // Виконуємо запит
                $result_hardwork = $stmt_hardwork->get_result(); // Отримуємо результат запиту
                if ($result_hardwork->num_rows > 0) { // Якщо є хоча б один рядок у результаті
                    $hardwork_data = $result_hardwork->fetch_assoc(); // Зберігаємо дані в масив
                }
                $stmt_hardwork->close(); // Закриваємо запит
            }

            // Отримуємо дані про тренування з пілатесу (аналогічно до fitness та hardwork)
            $pilates_data = []; // Створюємо пустий масив для даних пілатесу
            $sql_pilates = "SELECT * FROM pilates WHERE user_id = ?"; // SQL-запит для вибірки даних з таблиці pilates
            $stmt_pilates = $mysqli->prepare($sql_pilates); // Підготовка запиту
            if ($stmt_pilates) { // Перевіряємо успішність підготовки запиту
                $stmt_pilates->bind_param("i", $user_id); // Прив'язуємо ID користувача до запиту
                $stmt_pilates->execute(); // Виконуємо запит
                $result_pilates = $stmt_pilates->get_result(); // Отримуємо результат запиту
                if ($result_pilates->num_rows > 0) { // Якщо є хоча б один рядок у результаті
                    $pilates_data = $result_pilates->fetch_assoc(); // Зберігаємо дані в масив
                }
                $stmt_pilates->close(); // Закриваємо запит
            }

            // Функція для отримання назв вправ з бази даних
            function getExerciseTitles($table)
            { // Приймає назву таблиці як параметр
                global $mysqli; // Використовуємо глобальне з'єднання з базою даних
                $titles = []; // Створюємо пустий масив для зберігання назв вправ
                $sql = "SELECT data_id, title FROM {$table}_data"; // SQL-запит для вибірки ID і назв вправ
                $result = $mysqli->query($sql); // Виконуємо запит
                if ($result) { // Якщо запит успішний
                    while ($row = $result->fetch_assoc()) { // Проходимо по всіх результатах
                        $titles[$row['data_id']] = $row['title']; // Зберігаємо назви вправ у масиві, де ключ - ID вправи
                    }
                }
                return $titles; // Повертаємо масив назв вправ
            }

            // Отримуємо назви вправ для кожного типу тренувань
            $fitness_titles = getExerciseTitles('fitness'); // Назви вправ для фітнесу
            $hardwork_titles = getExerciseTitles('hardwork'); // Назви вправ для силових тренувань
            $pilates_titles = getExerciseTitles('pilates'); // Назви вправ для пілатесу
            ?>

            <!-- Контейнер для відображення статистики тренувань -->
            <div class="training-stats">
                <!-- Картка для фітнес-тренувань -->
                <div class="discipline-card">
                    <h3>Фітнес</h3>
                    <!-- Перевіряємо, чи є дані про фітнес-тренування -->
                    <?php if (!empty($fitness_data)): ?>
                        <div class="table-responsive">
                            <!-- Створюємо таблицю для відображення фітнес-тренувань -->
                            <table class="training-table">
                                <thead>
                                <tr>
                                    <!-- Заголовки стовпців таблиці -->
                                    <th>Вправа</th>
                                    <th>Кількість</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Циклом проходимо по всіх даних фітнесу -->
                                <?php foreach ($fitness_data as $key => $value):
                                    // Пропускаємо службові поля (id та user_id)
                                    if ($key != 'id' && $key != 'user_id'): ?>
                                        <tr>
                                            <!-- Виводимо назву вправи або ключ, якщо назва відсутня -->
                                            <td><?= isset($fitness_titles[$key]) ? $fitness_titles[$key] : $key ?></td>
                                            <!-- Виводимо кількість виконаних вправ -->
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Виводимо повідомлення, якщо немає даних про фітнес-тренування -->
                        <p class="no-data">Ви ще не виконували фітнес-тренування</p>
                    <?php endif; ?>
                </div>

                <!-- Картка для силових тренувань (аналогічно до фітнесу) -->
                <div class="discipline-card">
                    <h3>Силові тренування</h3>
                    <?php if (!empty($hardwork_data)): ?>
                        <div class="table-responsive">
                            <table class="training-table">
                                <thead>
                                <tr>
                                    <th>Вправа</th>
                                    <th>Кількість</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($hardwork_data as $key => $value):
                                    if ($key != 'id' && $key != 'user_id'): ?>
                                        <tr>
                                            <td><?= isset($hardwork_titles[$key]) ? $hardwork_titles[$key] : $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Ви ще не виконували силові тренування</p>
                    <?php endif; ?>
                </div>

                <!-- Картка для тренувань з пілатесу (аналогічно до фітнесу та силових) -->
                <div class="discipline-card">
                    <h3>Пілатес</h3>
                    <?php if (!empty($pilates_data)): ?>
                        <div class="table-responsive">
                            <table class="training-table">
                                <thead>
                                <tr>
                                    <th>Вправа</th>
                                    <th>Кількість</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($pilates_data as $key => $value):
                                    if ($key != 'id' && $key != 'user_id'): ?>
                                        <tr>
                                            <td><?= isset($pilates_titles[$key]) ? $pilates_titles[$key] : $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Ви ще не виконували тренування з пілатесу</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Посилання для додавання нового тренування -->
            <a href="/training.php" class="add-training-btn">Додати нове тренування</a>
        </section>
    </main>

    <script src="/src/js/my-profile.js" defer></script>

<?php
// Підключаємо футер (підвал) сайту
include '../components/footer.php';
?>