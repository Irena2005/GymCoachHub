<?php
include '../components/auth_check.php';
$page_title = "Фітнес | Online Coach";
$include_main_js = true;

$username = $_SESSION['username'] ?? ' спортсмене';

include '../components/header.php';
include '../components/navbar.php';
?>

    <header>
        <h1>Фітнес, <?= $username ?>!</h1>
        <hr class="custom-line">
        <p>Що таке фітнес?<br>
            <span class="highlight">
               Фітнес — це комплекс вправ, спрямованих на покращення фізичної форми, витривалості та загального здоров'я.
               Він включає кардіо, силові вправи та вправи на гнучкість, які можна виконувати вдома чи в залі.
               Фітнес допомагає спалювати калорії, зміцнювати м'язи та підвищувати енергію.
               Це ідеальний вибір для тих, хто хоче підтримувати активний спосіб життя та досягати своїх фітнес-цілей.
           </span>
        </p>
    </header>

    <div class="random">
        <input type="hidden" id="trainingType" value="fitness">
        <a href="#" id="generate">Згенерувати тренування</a>
    </div>

<?php include '../components/modal.php'; ?>
<?php include '../components/modal-movie.php'; ?>

    <section class="fitness">
        <?php
        $sql = "SELECT * FROM fitness_data ORDER BY id ASC";
        $result = $mysqli->query($sql);
        $class = 'fitness-link';

        include '../components/training-list.php';

        ?>
    </section>

<?php include '../components/footer.php'; ?>