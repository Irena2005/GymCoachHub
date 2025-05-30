<?php

include '../components/auth_check.php';
                        $page_title = "Пілатес | Online Coach";
                        $include_main_js = true;

                        $username = $_SESSION['username'] ?? ' спортсмене';

                        include '../components/header.php';
                        include '../components/navbar.php';
                        ?>

                        <header>
                            <h1>Пілатес, <?= $username ?>!</h1>
                            <hr class="custom-line">
                            <p>Що таке пілатес?<br>
                                <span class="highlight">
                                   Пілатес — це система вправ, спрямованих на зміцнення м'язів кора, покращення гнучкості та постави.
                                   Він поєднує контрольовані рухи, дихання та концентрацію, що сприяє гармонійному розвитку тіла.
                                   Пілатес підходить для всіх рівнів підготовки, допомагаючи зняти напругу, покращити координацію
                                   та досягти балансу між силою і гнучкістю.
                               </span>
                            </p>
                        </header>

                        <div class="random">
                            <input type="hidden" id="trainingType" value="pilates">
                            <a href="#" id="generate">Згенерувати тренування</a>
                        </div>

                        <?php include '../components/modal.php'; ?>
                        <?php include '../components/modal-movie.php'; ?>

                        <section class="pilates">
                            <?php

                            // Запит до бази даних для отримання всіх вправ пілатесу
                            $sql = "SELECT * FROM pilates_data ORDER BY id ASC";
                            $result = $mysqli->query($sql);

                            $class = 'pilates-link';
                            include '../components/training-list.php';
                            ?>
                        </section>


    <script>

    </script>


                        <?php include '../components/footer.php'; ?>