<?php
include '../components/auth_check.php';

$username = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') : null;

$page_title = "Про нас | Online Coach";
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <header>
        <h3>Хто ми такі та чому саме ми</h3>
        <h5>Online Coach</h5>
        <?php if ($username): ?>
            <p class="tagline">Вітаємо, <?= $username ?>!</p>
        <?php endif; ?>
    </header>

    <hr class="custom-line">

    <section class="about" id="about">
        <div class="about-content">
            <p>Чи знайома вам ситуація, коли ви приходите до тренажерного залу з певною метою, але не знаєте,
                з чого почати та як правильно скласти план тренувань? А як щодо онлайн-тренера у вашому телефоні?
                <strong>Online Coach</strong> — це саме те, що вам потрібно!
                Разом із нами ви зможете вдосконалювати своє тіло та відстежувати прогрес.
                Ми підберемо індивідуальний план тренувань, враховуючи ваші вподобання та цілі.
                Ви матимете щоденник тренувань, де зможете бачити свої результати.
                Разом ми створимо кращу версію вас!</p>
            <div class="item"><img src="/src/images/title1.jpg" alt="Тренування з Online Coach"></div>
        </div>
    </section>

    <hr class="custom-line">

    <section class="information">
        <div class="information-photo">
            <p>Досягаємо цілей разом із Online Coach</p>
            <div class="item"><img src="/src/images/inf2.jpg" alt="Тренування 1"></div>
            <div class="item"><img src="/src/images/inf1.jpg" alt="Тренування 2"></div>
            <div class="item"><img src="/src/images/inf3.JPG" alt="Тренування 3"></div>
        </div>
    </section>

    <div class="invite">
        <p>Хочеш так само?</p>
        <a href="<?= $username ? 'my-profile.php' : 'signup.php' ?>">Приєднуйся!</a>
    </div>

<?php include '../components/footer.php'; ?>