<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

Auth::requireUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Метод не разрешён');
}

$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token) ||
    !isset($_SESSION['review_csrf_token']) ||
    !hash_equals($_SESSION['review_csrf_token'], $token)
) {
    $_SESSION['review_flash'] = 'Ошибка проверки запроса';

    header('Location: /frontend/applications.php', true, 303);
    exit;
}

$applicationId = filter_var(
    $_POST['application_id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$rating = filter_var(
    $_POST['rating'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => [
        'min_range' => 1,
        'max_range' => 5
    ]]
);

$reviewText = $_POST['review_text'] ?? '';

if (
    !is_int($applicationId) ||
    !is_int($rating) ||
    !is_string($reviewText) ||
    trim($reviewText) === ''
) {
    $_SESSION['review_flash'] = 'Заполните текст отзыва и выберите оценку от 1 до 5';

    header('Location: /frontend/applications.php', true, 303);
    exit;
}

try {
    $success = Review::create(
        (int) Auth::id(),
        $applicationId,
        $reviewText,
        $rating
    );

    if ($success) {
        $_SESSION['review_flash'] = 'Отзыв успешно отправлен!';
    } else {
        $_SESSION['review_flash'] = 'Нельзя оставить отзыв для этой заявки. Проверьте её статус и наличие предыдущего отзыва.';
    }

} catch (Throwable $e) {
    error_log($e->getMessage());

    $_SESSION['review_flash'] = 'Произошла ошибка при сохранении отзыва';
}

header('Location: /frontend/applications.php', true, 303);
exit;
