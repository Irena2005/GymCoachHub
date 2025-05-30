<?php
include '../components/auth_check.php';
$page_title = "Вибір типу тренувань | Online Coach";
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <header>
        <h1>Вибір типу тренувань, <?= $username ?>!</h1>
        <p>Оберіть тип тренувань, який підходить саме вам:<br>
            <span class="highlight">
               Силові тренування для розвитку сили та м’язової маси, пілатес для гнучкості та міцного кору,
               або фітнес для активного способу життя та витривалості. 
               <br> ( Кожну з вправ рекомендовано виконувати 2-3 рази по 10-15 повторень*)
           </span>
        </p>
    </header>

    <hr class="custom-line">

    <section class="traintypes">
        <a href="hardtype.php">
            <img src="/src/images/hard.jpg" alt="Силові тренування">
            <p>Силові тренування</p>
        <a href="pilates.php">
            <img src="/src/images/pilates.jpg" alt="Пілатес">
            <p>Пілатес</p>
        </a>
        <a href="fitness.php">
            <img src="/src/images/fitness.jpg" alt="Фітнес">
            <p>Фітнес</p>
        </a>
    </section>

<?php include '../components/footer.php'; ?>