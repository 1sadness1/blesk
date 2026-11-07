<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

Auth::requireUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /frontend/application_create.php');
    exit;
}

$data = [
    'address' => trim((string)($_POST['address'] ?? '')),
    'contact_phone' => trim((string)($_POST['contact_phone'] ?? '')),
    'desired_datetime' => trim((string)($_POST['desired_datetime'] ?? '')),
    'service_name' => trim((string)($_POST['service_name'] ?? '')),
    'payment_method_id' => trim((string)($_POST['payment_method_id'] ?? ''))
];

$errors = [];

if ($data['address'] === '') {
    $errors['address'] = 'Введите адрес уборки';
}

if (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $data['contact_phone'])) {
    $errors['contact_phone'] = 'Формат телефона: 8(999)123-45-67';
}

if ($data['desired_datetime'] === '') {
    $errors['desired_datetime'] = 'Укажите дату и время уборки';
}

if ($data['service_name'] === '') {
    $errors['service_name'] = 'Укажите вид услуги';
}

if (!in_array($data['payment_method_id'], ['1', '2'], true)) {
    $errors['payment_method_id'] = 'Выберите способ оплаты';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $data;

    header('Location: /frontend/application_create.php');
    exit;
}

try {
    Application::create((int)Auth::id(), $data);

    $_SESSION['success'] = 'Заявка успешно создана!';
    unset($_SESSION['errors'], $_SESSION['old']);

    header('Location: /frontend/application_create.php');
    exit;

} catch (Throwable $e) {
    error_log($e->getMessage());

    $_SESSION['errors'] = [
        'general' => 'Не удалось сохранить заявку'
    ];
    $_SESSION['old'] = $data;

    header('Location: /frontend/application_create.php');
    exit;
}
