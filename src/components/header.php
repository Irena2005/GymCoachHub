<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'Online Coach' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/style.css">
    <?php if (isset($include_main_js)): ?>
        <script src="/src/js/main.js" defer></script>
        <script src="/src/js/youtube-player.js" defer></script>
    <?php endif; ?>
</head>
<body>
<div class="navbar-bg"></div>