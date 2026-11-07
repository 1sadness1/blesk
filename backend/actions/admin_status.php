<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

Auth::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Метод не разрешён');
}

$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token) ||
    !isset($_SESSION['admin_csrf_token']) ||
    !hash_equals($_SESSION['admin_csrf_token'], $token)
) {
    $_SESSION['admin_flash'] = [
        'type' => 'error',
        'message' => 'Ошибка проверки запроса'
    ];

    header('Location: /frontend/admin.php', true, 303);
    exit;
}

$applicationId = filter_var(
    $_POST['application_id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$newStatusId = filter_var(
    $_POST['status_id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1, 'max_range' => 4]]
);

if ($applicationId === false || $newStatusId === false) {
    $_SESSION['admin_flash'] = [
        'type' => 'error',
        'message' => 'Некорректные данные заявки'
    ];

    header('Location: /frontend/admin.php', true, 303);
    exit;
}

try {
    $result = Application::changeStatus(
        (int) $applicationId,
        (int) $newStatusId
    );

    if ($result) {
        $_SESSION['admin_flash'] = [
            'type' => 'success',
            'message' => 'Статус заявки успешно изменён'
        ];
    } else {
        $_SESSION['admin_flash'] = [
            'type' => 'error',
            'message' => 'Недопустимый переход статуса'
        ];
    }

} catch (Throwable $e) {
    error_log($e->getMessage());

    $_SESSION['admin_flash'] = [
        'type' => 'error',
        'message' => 'Ошибка при изменении статуса'
    ];
}

header('Location: /frontend/admin.php', true, 303);
exit;
