<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? '';

unset(
    $_SESSION['errors'],
    $_SESSION['old'],
    $_SESSION['success']
);

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация — Блеск</title>
</head>
<body>

<h1>Регистрация</h1>

<?php if ($success): ?>
    <p style="color: green;"><?= e($success) ?></p>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <p style="color: red;"><?= e($errors['general']) ?></p>
<?php endif; ?>

<form action="/backend/actions/register.php" method="POST">

    <label for="login">Логин:</label>
    <input
        type="text"
        id="login"
        name="login"
        value="<?= e($old['login'] ?? '') ?>"
        required
    >
    <?php if (isset($errors['login'])): ?>
        <p style="color: red;"><?= e($errors['login']) ?></p>
    <?php endif; ?>
    <br>

    <label for="password">Пароль:</label>
    <input
        type="password"
        id="password"
        name="password"
        required
    >
    <?php if (isset($errors['password'])): ?>
        <p style="color: red;"><?= e($errors['password']) ?></p>
    <?php endif; ?>
    <br>

    <label for="full_name">ФИО:</label>
    <input
        type="text"
        id="full_name"
        name="full_name"
        value="<?= e($old['full_name'] ?? '') ?>"
        required
    >
    <?php if (isset($errors['full_name'])): ?>
        <p style="color: red;"><?= e($errors['full_name']) ?></p>
    <?php endif; ?>
    <br>

    <label for="phone">Телефон:</label>
    <input
        type="tel"
        id="phone"
        name="phone"
        placeholder="8(999)123-45-67"
        value="<?= e($old['phone'] ?? '') ?>"
        required
    >
    <?php if (isset($errors['phone'])): ?>
        <p style="color: red;"><?= e($errors['phone']) ?></p>
    <?php endif; ?>
    <br>

    <label for="email">Электронная почта:</label>
    <input
        type="email"
        id="email"
        name="email"
        value="<?= e($old['email'] ?? '') ?>"
        required
    >
    <?php if (isset($errors['email'])): ?>
        <p style="color: red;"><?= e($errors['email']) ?></p>
    <?php endif; ?>
    <br><br>

    <button type="submit">Создать пользователя</button>

</form>

<p>
    Уже зарегистрированы?
    <a href="login.php">Вход</a>
</p>

</body>
</html>
