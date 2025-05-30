<?php
include '../components/auth_check.php';
$page_title = "Силові тренування | Online Coach";
$include_main_js = true;
$username = $_SESSION['username'] ?? ' спортсмене';
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <header>
        <h1>Силові тренування, <?= $username ?>!</h1>
        <hr class="custom-line">
        <p>Що таке силові тренування?<br>
            <span class="highlight">
               Силові тренування — це комплекс вправ, спрямованих на розвиток сили, витривалості та м’язової маси.
               Вони включають вправи з власною вагою, тренажерами або вільними вагами (штанги, гантелі).
               Такі заняття допомагають зміцнити м’язи, покращити поставу та підвищити загальну фізичну форму.
               Силові тренування ідеально підходять для тих, хто хоче досягти рельєфного тіла та покращити здоров’я.
           </span>
        </p>
    </header>

    <div class="random">
        <input type="hidden" id="trainingType" value="hardwork">
        <a href="#" id="generate">Згенерувати тренування</a>
    </div>

<?php include '../components/modal.php'; ?>
<?php include '../components/modal-movie.php'; ?>

    <section class="hardexercise">
        <?php

        // Запит до бази даних для отримання всіх вправ пілатесу
        $sql = "SELECT * FROM hardwork_data ORDER BY id ASC";
        $result = $mysqli->query($sql);

        $class = 'hard-exercise-link';
        include '../components/training-list.php';
        ?>
    </section>

<?php include '../components/footer.php'; ?>