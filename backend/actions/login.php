<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /frontend/login.php');
    exit;
}

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$login = is_string($login) ? trim($login) : '';
$password = is_string($password) ? $password : '';

if ($login === '' || $password === '') {
    $_SESSION['login_errors'] = [
        'general' => 'Заполните логин и пароль'
    ];

    $_SESSION['login_old'] = $login;

    header('Location: /frontend/login.php');
    exit;
}

if (Auth::login($login, $password)) {

    header('Location: /index.php');
    exit;

} else {

    $_SESSION['login_errors'] = [
        'general' => 'Неверный логин или пароль'
    ];

    $_SESSION['login_old'] = $login;

    header('Location: /frontend/login.php');
    exit;
}
