<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /frontend/register.php');
    exit;
}

$data = [
    'login' => trim($_POST['login'] ?? ''),
    'password' => $_POST['password'] ?? '',
    'full_name' => trim($_POST['full_name'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'email' => trim($_POST['email'] ?? '')
];

$errors = Validator::validateRegistration($data);

if (empty($errors) && User::findByLogin($data['login'])) {
    $errors['login'] = 'Этот логин уже занят';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;

    $_SESSION['old'] = [
        'login' => $data['login'],
        'full_name' => $data['full_name'],
        'phone' => $data['phone'],
        'email' => $data['email']
    ];

    header('Location: /frontend/register.php');
    exit;
}

try {
    User::create($data);

    $_SESSION['success'] = 'Регистрация прошла успешно!';

    header('Location: /frontend/register.php');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());

    $_SESSION['errors'] = [
        'general' => 'Не удалось завершить регистрацию'
    ];

    header('Location: /frontend/register.php');
    exit;
}
