<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

$errors = $_SESSION['login_errors'] ?? [];
$oldLogin = $_SESSION['login_old'] ?? '';

unset(
    $_SESSION['login_errors'],
    $_SESSION['login_old']
);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация — Блеск</title>
</head>
<body>
    <h1>Авторизация</h1>

    <?php if (isset($errors['general'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form action="/backend/actions/login.php" method="POST">

        <label for="login">Логин:</label>
        <input
            type="text"
            id="login"
            name="login"
            value="<?= htmlspecialchars($oldLogin, ENT_QUOTES, 'UTF-8') ?>"
            required
        >
        <br><br>

        <label for="password">Пароль:</label>
        <input
            type="password"
            id="password"
            name="password"
            required
        >
        <br><br>

        <button type="submit">Войти</button>

    </form>

    <p>
        Еще не зарегистрированы?
        <a href="register.php">Регистрация</a>
    </p>
</body>
</html>
