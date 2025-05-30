<?php
// messages.php
?>
<?php if (!empty($errors)): ?>
    <div class="error-message">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="success-message">
        <p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
<?php endif; ?>