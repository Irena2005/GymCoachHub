<?php
// guest_navbar.php
?>
<nav class="navbar">
    <a href="../index.php">Головна</a>
    <a href="<?= basename($_SERVER['PHP_SELF']) === '../pages/login.php' ? '../pages/signup.php' : '../pages/login.php' ?>">
        <?= basename($_SERVER['PHP_SELF']) === '../pages/login.php' ? 'Реєстрація' : 'Вхід' ?>
    </a>
</nav>