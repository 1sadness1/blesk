<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

$errors = $_SESSION['login_errors'] ?? [];
$oldLogin = $_SESSION['login_old'] ?? '';

unset(
    $_SESSION['login_errors'],
    $_SESSION['login_old']
);

$pageTitle = 'Авторизация — Блеск';
require __DIR__ . '/partials/header.php';
?>

<main class="login-page">

    <h1>Авторизация</h1>

    <?php if (isset($errors['general'])): ?>
        <p class="login-error">
            <?= htmlspecialchars(
                (string)$errors['general'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
    <?php endif; ?>

    <form
        class="login-form"
        action="/backend/actions/login.php"
        method="POST"
    >

        <label for="login">Логин:</label>
        <input
            type="text"
            id="login"
            name="login"
            placeholder="Введите логин"
            value="<?= htmlspecialchars($oldLogin, ENT_QUOTES, 'UTF-8') ?>"
            autocomplete="username"
            required
        >

        <label for="password">Пароль:</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Введите пароль"
            autocomplete="current-password"
            required
        >

        <button class="button-primary" type="submit">
            Войти
        </button>

    </form>

    <p class="login-switch">
        Ещё не зарегистрированы?
        <a href="/frontend/register.php">Регистрация</a>
    </p>

</main>

<script src="/frontend/assets/js/app.js" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
