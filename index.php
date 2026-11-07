<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = "Блеск — клининговые услуги";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
</head>
<body>
    <h1>Клининговая компания «Блеск»</h1>
    <p>Добро пожаловать на наш сайт!</p>
    <?php if (Auth::isLoggedIn()): ?>

    <p>Вы успешно вошли в систему!</p>
    <p>Ваш ID: <?= Auth::id() ?></p>

    <form action="/backend/actions/logout.php" method="POST">
        <button type="submit">Выйти</button>
    </form>

<?php else: ?>

    <p>Вы не авторизованы.</p>
    <a href="/frontend/login.php">Войти</a>

<?php endif; ?>
</body>
</html>
