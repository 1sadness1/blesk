<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

Auth::requireAdmin();

if (!isset($_SESSION['admin_csrf_token'])) {
    $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
}

$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);

$applications = Application::allForAdmin();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора — Блеск</title>
</head>
<body>

<h1>Панель администратора</h1>

<h2>Заявки пользователей</h2>

<?php if ($flash): ?>
    <p style="color: <?= $flash['type'] === 'success' ? 'green' : 'red' ?>;">
        <?= e((string)$flash['message']) ?>
    </p>
<?php endif; ?>

<?php if (empty($applications)): ?>

    <p>Заявок пока нет.</p>

<?php else: ?>

    <?php foreach ($applications as $application): ?>

        <hr>

        <h3>Заявка № <?= (int)$application['id'] ?></h3>

        <p>
            <strong>Логин клиента:</strong>
            <?= e((string)$application['login']) ?>
        </p>

        <?php
        $fullName = trim(implode(' ', [
            $application['last_name'] ?? '',
            $application['first_name'] ?? '',
            $application['middle_name'] ?? ''
        ]));
        ?>

        <p>
            <strong>ФИО клиента:</strong>
            <?= e($fullName) ?>
        </p>

        <p>
            <strong>Адрес:</strong>
            <?= e((string)$application['address']) ?>
        </p>

        <p>
            <strong>Телефон:</strong>
            <?= e((string)$application['contact_phone']) ?>
        </p>

        <p>
            <strong>Дата и время:</strong>
            <?= e((string)$application['desired_datetime']) ?>
        </p>

        <p>
            <strong>Вид услуги:</strong>
            <?= e((string)$application['service_name']) ?>
        </p>

        <p>
            <strong>Способ оплаты:</strong>
            <?= e((string)$application['payment_method_name']) ?>
        </p>

        <p>
            <strong>Статус:</strong>
            <?= e((string)$application['status_name']) ?>
        </p>

        <h4>Изменить статус</h4>

        <?php
        $statusId = (int)$application['status_id'];
        $nextStatuses = [];

        if ($statusId === 1) {
            $nextStatuses = [2 => 'В работе'];
        } elseif ($statusId === 2) {
            $nextStatuses = [
                3 => 'Выполнено',
                4 => 'Отменено'
            ];
        }
        ?>

        <?php if (!empty($nextStatuses)): ?>

            <?php foreach ($nextStatuses as $newId => $label): ?>

                <form
                    action="/backend/actions/admin_status.php"
                    method="POST"
                >
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e($_SESSION['admin_csrf_token']) ?>"
                    >

                    <input
                        type="hidden"
                        name="application_id"
                        value="<?= (int)$application['id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="status_id"
                        value="<?= (int)$newId ?>"
                    >

                    <button type="submit">
                        <?= e($label) ?>
                    </button>

                </form>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Изменение статуса недоступно.</p>

        <?php endif; ?>

    <?php endforeach; ?>

<?php endif; ?>

<hr>

<form action="/backend/actions/logout.php" method="POST">
    <button type="submit">Выйти</button>
</form>

<p><a href="/index.php">На главную</a></p>

</body>
</html>
