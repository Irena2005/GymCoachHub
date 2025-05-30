-- Встановлює кодування для з'єднання з базою даних на utf8mb4 (підтримує всі символів Unicode)
SET NAMES utf8mb4;
-- Таблиця users: інформація про користувачів
CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор користувача',
                                     user_name VARCHAR(50) NOT NULL COMMENT 'Імя користувача',
    surname VARCHAR(50) NOT NULL COMMENT 'Прізвище користувача',
    phone_number VARCHAR(15) NOT NULL COMMENT 'Номер телефону користувача',
    age INT NOT NULL CHECK (age >= 12 AND age <= 100) COMMENT 'Вік користувача (12-100 років)',
    registration_date DATE NOT NULL COMMENT 'Дата реєстрації',
    CONSTRAINT uk_phone_number UNIQUE (phone_number)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Таблиця для зберігання даних користувачів';

-- Таблиця user_base_measurements: базові виміри користувача (встановлюються один раз, але можуть редагуватися)
CREATE TABLE IF NOT EXISTS user_base_measurements (
                                                      id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                                      user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (звязок з таблицею users)',
                                                      weight DECIMAL(5,1) NOT NULL COMMENT 'Базова вага користувача (кг)',
    height INT NOT NULL COMMENT 'Базовий зріст користувача (см)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата створення запису',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата оновлення запису',
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT uk_user_base UNIQUE (user_id)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Таблиця для зберігання базових вимірів користувачів';

-- Таблиця user_measurements: історія вимірів ваги та зросту користувача
CREATE TABLE IF NOT EXISTS user_measurements (
                                                 id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                                 user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (звязок з таблицею users)',
                                                 weight DECIMAL(5,1) NOT NULL COMMENT 'Вага користувача (кг)',
    height INT NOT NULL COMMENT 'Зріст користувача (см)',
    measurement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата і час виміру',
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Таблиця для зберігання історії вимірів користувачів';

-- Таблиця fitness: дані про фітнес-вправи
CREATE TABLE IF NOT EXISTS fitness (
                                       id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                       user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (звязок з таблицею users)',
                                       lunges_forward INT NOT NULL DEFAULT 0 COMMENT 'Кількість випадів вперед',
                                       plank INT NOT NULL DEFAULT 0 COMMENT 'Час утримання планки (секунди)',
                                       squats INT NOT NULL DEFAULT 0 COMMENT 'Кількість присідань',
                                       glute_bridge INT NOT NULL DEFAULT 0 COMMENT 'Кількість підйомів тазу (місток)',
                                       abs_exercises INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ на прес',
                                       push_ups INT NOT NULL DEFAULT 0 COMMENT 'Кількість віджимань',
                                       burpees INT NOT NULL DEFAULT 0 COMMENT 'Кількість берпі',
                                       mountain_climbers INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "альпінізм"',
                                       high_knee_run INT NOT NULL DEFAULT 0 COMMENT 'Кількість бігу з підняттям колін',
                                       FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Таблиця для зберігання даних про фітнес-вправи';

-- Таблиця hardwork: дані про силові тренування
CREATE TABLE IF NOT EXISTS hardwork (
                                        id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                        user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (звязок з таблицею users)',
                                        back_extension INT NOT NULL DEFAULT 0 COMMENT 'Кількість екстензій спини',
                                        t_bar_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість Т-тяги',
                                        dumbbell_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг з гантеллю',
                                        seated_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг сидячи',
                                        barbell_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг штанги',
                                        barbell_upright_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг штанги до підборіддя',
                                        stationary_bike INT NOT NULL DEFAULT 0 COMMENT 'Час бігу на велотренажері (хвилини)',
                                        barbell_lunges INT NOT NULL DEFAULT 0 COMMENT 'Кількість випадів зі штангою',
                                        leg_extension INT NOT NULL DEFAULT 0 COMMENT 'Кількість розгинань ніг',
                                        crossover_leg_abduction INT NOT NULL DEFAULT 0 COMMENT 'Кількість відведень ніг у кросовері',
                                        barbell_squats INT NOT NULL DEFAULT 0 COMMENT 'Кількість присідань зі штангою',
                                        leg_adduction INT NOT NULL DEFAULT 0 COMMENT 'Кількість зведень ніг',
                                        leg_curl INT NOT NULL DEFAULT 0 COMMENT 'Кількість згинань ніг',
                                        arm_adduction INT NOT NULL DEFAULT 0 COMMENT 'Кількість зведень рук',
                                        dip_bars INT NOT NULL DEFAULT 0 COMMENT 'Кількість віджимань на брусах',
                                        crossover_arm INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ на кросовері для рук',
                                        barbell_bench_press INT NOT NULL DEFAULT 0 COMMENT 'Кількість жиму штанги',
                                        dumbbell_bench_press INT NOT NULL DEFAULT 0 COMMENT 'Кількість жиму гантель',
                                        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Таблиця для зберігання даних про силові тренування';

-- Таблиця pilates: дані про заняття пілатесом
CREATE TABLE IF NOT EXISTS pilates (
                                       id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                       user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (звязок з таблицею users)',
                                       spine_stretch INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ на розтягування хребта',
                                       leg_lift_stretch INT NOT NULL DEFAULT 0 COMMENT 'Кількість піднімань ноги та розтяжки',
                                       scissor_twist INT NOT NULL DEFAULT 0 COMMENT 'Кількість скручувань "ножиці"',
                                       swan_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "лебідь"',
                                       ball_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "мячик"',
                                       pelvic_circles INT NOT NULL DEFAULT 0 COMMENT 'Кількість кругових рухів тазом',
                                       swimming_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "плавання"',
                                       kneeling_leg_extension INT NOT NULL DEFAULT 0 COMMENT 'Кількість розгинань ніг у положенні на колінах',
                                       shoulder_bridge INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "берізка"',
                                       push_ups INT NOT NULL DEFAULT 0 COMMENT 'Кількість віджимань',
                                       basket_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "кошик"',
                                       boomerang_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "бумеранг"',
                                       FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Таблиця для зберігання даних про заняття пілатесом';

CREATE TABLE `pilates_data` (
                                `id` INT AUTO_INCREMENT PRIMARY KEY,
                                `data_id` VARCHAR(50) NOT NULL,
                                `img` VARCHAR(255) NOT NULL,
                                `title` VARCHAR(255) NOT NULL,
                                `movie` VARCHAR(255) DEFAULT NULL,
                                `link` VARCHAR(255) DEFAULT NULL,
                                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pilates_data` (`data_id`, `img`, `title`, `movie`)
VALUES
    ('spine_stretch', '/src/images/spine.jpg', 'Розтягнення хребта', 'https://youtu.be/XZGuNaEV-nM?si=emX68HRUHk-2wKKK'),
    ('leg_lift_stretch', '/src/images/rocker.jpg', 'Піднімання ніг з розтягненням', 'https://youtu.be/E4FSgzPlUcs?si=hpm7yEqzCKb7JeLW'),
    ('scissor_twist', '/src/images/cross.jpg', 'Скручування ножиць', 'https://youtu.be/a2L7tfx8XbU?si=QwC_idUQ3dIju7S6'),
    ('swan_exercise', '/src/images/dive.jpg', 'Вправа лебідь', 'https://youtu.be/mjZZ22GLcDc?si=4izh0KsOHNPHKDrh'),
    ('ball_exercise', '/src/images/ball.jpg', 'Вправа з мячем', 'https://youtu.be/elkcXFPyaW8?si=1Zkibf6S3u8aphlM'),
    ('pelvic_circles', '/src/images/circles.jpg', 'Кола тазом', 'https://youtu.be/YDM1g6f9aDA?si=Sc_bJUxTRP5SZGp4'),
    ('swimming_exercise', '/src/images/swim.jpg', 'Вправа плавання', 'https://youtu.be/rbcvbbtB6E0?si=FYV9-RbofNIhtJnM'),
    ('kneeling_leg_extension', '/src/images/kneeling.jpg', 'Розгинання ніг на колінах', 'https://youtu.be/hgLDMHCcw4k?si=65ZDMZiLMYeHiiev'),
    ('shoulder_bridge', '/src/images/knife.jpg', 'Плечовий міст', 'https://youtu.be/SXYp9AJ1uWM?si=HiIa12NKse2foJK_'),
    ('push_ups', '/src/images/pushup.jpg', 'Віджимання', 'https://youtu.be/Ny0qFffcemg?si=Ob60hWwUWqblo4k1'),
    ('basket_exercise', '/src/images/rocking.jpg', 'Вправа кошик', 'https://youtu.be/KQWHWl7yV9g?si=fiIj2x97jTZ01cjZ'),
    ('boomerang_exercise', '/src/images/boomerang.jpg', 'Вправа бумеранг', 'https://youtu.be/SRonJI25raE?si=bloV4weQvtb4bTmj');

CREATE TABLE `fitness_data` (
                                `id` INT AUTO_INCREMENT PRIMARY KEY,
                                `data_id` VARCHAR(50) NOT NULL,
                                `img` VARCHAR(255) NOT NULL,
                                `title` VARCHAR(255) NOT NULL,
                                `movie` VARCHAR(255) DEFAULT NULL,
                                `link` VARCHAR(255) DEFAULT NULL,
                                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Вправи фітнес для відображення на сторінці';

INSERT INTO `fitness_data` (`data_id`, `img`, `title`, `movie`)
VALUES
    ('lunges_forward', '/src/images/lunge(fit).jpg', 'Випади вперед', 'https://youtu.be/tQNktxPkSeE?si=xTn9i6bi8NetpEEV'),
    ('plank', '/src/images/planl(fit).jpg', 'Планка', 'https://youtu.be/pvIjsG5Svck?si=fo3cRcLVV-LYiXo0'),
    ('squats', '/src/images/squat(fit).jpg', 'Присідання', 'https://youtu.be/mGvzVjuY8SY?si=jQ-fLfXTRC4KY5B8'),
    ('glute_bridge', '/src/images/bridge(fit).jpg', 'Сідничний міст', 'https://youtu.be/_leI4qFfPVw?si=SN5unbaVvHp8pBdX'),
    ('abs_exercises', '/src/images/crunch(fit).jpg', 'Вправи на прес', 'https://youtu.be/MKmrqcoCZ-M?si=H4dn42jyFxjw-I5m'),
    ('push_ups', '/src/images/pushup(fit).jpg', 'Віджимання', 'https://youtu.be/RWS6Q4IPO-8?si=YNa7rq-333orsTDE'),
    ('burpees', '/src/images/burpees(fit).jpg', 'Берпі', 'https://youtu.be/qLBImHhCXSw?si=CNSPuWEPoKii7UbH'),
    ('mountain_climbers', '/src/images/mountain(fit).jpg', 'Альпініст', 'https://youtu.be/kLh-uczlPLg?si=4uMhJFeEIoi-oXgh'),
    ('high_knee_run', '/src/images/highknees(fit).jpg', 'Біг із високим підніманням колін', 'https://youtu.be/FvjmPRU3zn4?si=Sk6hwFpb9NEcnCe4');

CREATE TABLE `hardwork_data` (
                                 `id` INT AUTO_INCREMENT PRIMARY KEY,
                                 `data_id` VARCHAR(50) NOT NULL,
                                 `img` VARCHAR(255) NOT NULL,
                                 `title` VARCHAR(255) NOT NULL,
                                 `movie` VARCHAR(255) DEFAULT NULL,
                                 `link` VARCHAR(255) DEFAULT NULL,
                                 `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                 `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Вправи силових тренувань для відображення на сторінці';

INSERT INTO `hardwork_data` (`data_id`, `img`, `title`, `movie`)
VALUES
    ('back_extension', '/src/images/back_extension.jpg', 'Екстензія спини', 'https://youtu.be/ENXyYltB7CM?si=GjXlC5eFnl64kJz4'),
    ('t_bar_row', '/src/images/t_bar_row.jpg', 'Т-тяга', 'https://youtu.be/TyLoy3n_a10?si=l4LA_5gs50ZsBXJL'),
    ('dumbbell_row', '/src/images/dumbbell_row.jpg', 'Тяга з гантеллю', 'https://youtu.be/Ch4UZW_j08Q?si=v5ZWcqFCP0f8RQ_K'),
    ('seated_row', '/src/images/seated_row.jpg', 'Тяга сидячи', 'https://youtu.be/hUV6XDtNTLU?si=DB7MKID0jYYHObPd'),
    ('barbell_row', '/src/images/barbell_row.jpg', 'Тяга штанги', 'https://youtu.be/D3E6BEuROfM?si=U1Qf96zvoc_TbIcr'),
    ('barbell_upright_row', '/src/images/barbell_upright_row.jpg', 'Тяга штанги до підборіддя', 'https://youtu.be/ayFFc1N54SA?si=LuTwlmWFXIWhC6k7'),
    ('stationary_bike', '/src/images/stationary_bike.jpg', 'Біг на велотренажері', 'https://youtu.be/rEqRmKAQ5xM?si=-KtFfCZdU27d2rYq'),
    ('barbell_lunges', '/src/images/barbell_lunges.jpg', 'Випади зі штангою', 'https://youtu.be/P5J-131KYiU?si=tN0s8RvghRz_Y8Qh'),
    ('leg_extension', '/src/images/leg_extension.jpg', 'Розгинання ніг', 'https://youtu.be/qYxo9ZFvHQE?si=7KKii2q6t8A-KyjJ'),
    ('crossover_leg_abduction', '/src/images/crossover_leg_abduction.jpg', 'Відведення ніг у кросовері', 'https://youtu.be/EHq78mQYLbI?si=DW267bA4UXKyiRmS'),
    ('barbell_squats', '/src/images/barbell_squats.jpg', 'Присідання зі штангою', 'https://youtu.be/Cj7dKDbFA94?si=Rh4kixpagoygQZWb'),
    ('leg_adduction', '/src/images/leg_adduction.jpg', 'Зведення ніг', 'https://youtu.be/e9AqTFMmP18?si=1FuesudT2_9CPbxJ'),
    ('leg_curl', '/src/images/leg_curl.jpg', 'Згинання ніг', 'https://youtu.be/SiwJ_T62l9c?si=DNp-Mv22fff5lCno'),
    ('arm_adduction', '/src/images/arm_adduction.jpg', 'Зведення рук', 'https://youtu.be/JJaIcqxoUZw?si=3ldCalFbLfvKp4qS'),
    ('dip_bars', '/src/images/dip_bars.jpg', 'Віджимання на брусах', 'https://youtu.be/WNiEv8e9aKU?si=GFSH-RdpZtWeU46C'),
    ('crossover_arm', '/src/images/crossover_arm.jpg', 'Вправи на кросовері для рук', 'https://youtu.be/EsXgqAxVKbA?si=sR_q1Ctf_3TZ5Es-'),
    ('barbell_bench_press', '/src/images/barbell_bench_press.jpg', 'Жим штанги', 'https://youtu.be/Jb2bMaxqnXI?si=VGJpMgEUHzTCtwnJ'),
    ('dumbbell_bench_press', '/src/images/dumbbell_bench_press.jpg', 'Жим гантель', 'https://youtu.be/mXdyLcQ_VZU?si=xTP2ycBZVRUl8Gcg');