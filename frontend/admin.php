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

$statusFilter = trim((string)($_GET['status'] ?? ''));

$allowedStatuses = [
    'Новая',
    'В работе',
    'Выполнено',
    'Отменено'
];

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = '';
}

if ($statusFilter !== '') {
    $applications = array_values(array_filter(
        $applications,
        static function (array $application) use ($statusFilter): bool {
            return (string)$application['status_name'] === $statusFilter;
        }
    ));
}


$serviceFilter = trim((string)($_GET['service'] ?? ''));

$allowedServices = [
    'Общий клининг',
    'Генеральная уборка',
    'Послестроительная уборка',
    'Химчистка ковров и мебели',
    'Иная услуга'
];

if (!in_array($serviceFilter, $allowedServices, true)) {
    $serviceFilter = '';
}

if ($serviceFilter !== '') {
    $applications = array_values(array_filter(
        $applications,
        static function (array $application) use ($serviceFilter): bool {
            $serviceName = (string)$application['service_name'];

            if ($serviceFilter === 'Иная услуга') {
                return $serviceName === 'Иная услуга'
                    || str_starts_with($serviceName, 'Иная услуга:');
            }

            return $serviceName === $serviceFilter;
        }
    ));
}


$perPage = 2;

$totalApplications = count($applications);
$totalPages = max(1, (int)ceil($totalApplications / $perPage));

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

if ($page === false || $page === null || $page < 1) {
    $page = 1;
}

$page = min($page, $totalPages);

$offset = ($page - 1) * $perPage;

$applications = array_slice($applications, $offset, $perPage);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$pageTitle = 'Панель администратора — Блеск';
require __DIR__ . '/partials/header.php';
?>

<main class="admin-page">

<h1>Панель администратора</h1>





<form action="/frontend/admin.php" method="GET" class="admin-filters">

    <div class="admin-filter-field">
        <label for="status-filter">Статус</label>

        <select name="status" id="status-filter" onchange="this.form.requestSubmit()">
            <option value="">Все статусы</option>

            <?php foreach ($allowedStatuses as $status): ?>
                <option value="<?= e($status) ?>"<?= $statusFilter === $status ? 'selected' : '' ?>>
                    <?= e($status) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="admin-filter-field">
        <label for="service-filter">Вид услуги</label>

        <select name="service" id="service-filter" onchange="this.form.requestSubmit()">
            <option value="">Все услуги</option>

            <?php foreach ($allowedServices as $service): ?>
                <option value="<?= e($service) ?>"<?= $serviceFilter === $service ? 'selected' : '' ?>>
                    <?= e($service) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($statusFilter !== '' || $serviceFilter !== ''): ?>
        <a class="admin-filter-reset" href="/frontend/admin.php">Сбросить</a>
    <?php endif; ?>

</form>


<?php if ($flash): ?>
    <div class="admin-toast <?= $flash['type'] === 'success' ? 'admin-toast-success' : 'admin-toast-error' ?>"role="status"><?= e((string)$flash['message']) ?></div>
<?php endif; ?>


<?php if (empty($applications)): ?>

    <p>Заявок пока нет.</p>

<?php else: ?>

    
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Заявка</th>
                <th>Клиент</th>
                <th>Услуга</th>
                <th>Дата</th>
                <th>Статус</th>
                <th>Действие</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($applications as $application): ?>
                <?php
                $statusId = (int)$application['status_id'];
                $statusName = (string)$application['status_name'];

                $statusClass = match ($statusName) {
                    'Новая' => 'status-new',
                    'В работе' => 'status-progress',
                    'Выполнено' => 'status-completed',
                    'Отменено' => 'status-cancelled',
                    default => ''
                };

                $nextStatuses = [];

                if ($statusId === 1) {
                    $nextStatuses = [2 => 'Взять в работу'];
                } elseif ($statusId === 2) {
                    $nextStatuses = [
                        3 => 'Перевести в выполнено',
                        4 => 'Отменить'
                    ];
                }

                $date = explode(
                    ' ',
                    (string)$application['desired_datetime']
                )[0];
                ?>

                <tr>
                    <td>№ <?= (int)$application['id'] ?></td>

                    <td><?= e((string)$application['login']) ?></td>

                    <td><?= e((string)$application['service_name']) ?></td>

                    <td><?= e($date) ?></td>

                    <td><span class="application-status <?= e($statusClass) ?>"><?= e($statusName) ?></span></td>

                    <td>
                        <div class="admin-actions">

                            <?php if (!empty($nextStatuses)): ?>

                                <?php foreach ($nextStatuses as $newId => $label): ?>

                                    <form action="/backend/actions/admin_status.php" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= e((string)$_SESSION['admin_csrf_token']) ?>">

                                        <input type="hidden" name="application_id"value="<?= (int)$application['id'] ?>">

                                        <input type="hidden" name="status_id" value="<?= (int)$newId ?>">

                                        <button type="submit" class="<?= $newId === 4 ? 'admin-action-cancel' : 'admin-action-primary' ?>"><?= e($label) ?></button>
                                    </form>

                                <?php endforeach; ?>

                            <?php else: ?>
                                <span class="admin-no-action">—</span>
                            <?php endif; ?>

                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<?php if ($totalPages > 1): ?>
    <nav class="admin-pagination" aria-label="Страницы заявок">

        <?php if ($page > 1): ?>
            <a href="<?= e('/frontend/admin.php?' . http_build_query([
                'status' => $statusFilter,
                'service' => $serviceFilter,
                'page' => $page - 1
            ])) ?>" aria-label="Предыдущая страница">‹</a>
        <?php else: ?>
            <span class="admin-pagination-disabled">‹</span>
        <?php endif; ?>

        <span class="admin-pagination-count">
            <?= $page ?> / <?= $totalPages ?>
        </span>

        <?php if ($page < $totalPages): ?>
            <a href="<?= e('/frontend/admin.php?' . http_build_query([
                'status' => $statusFilter,
                'service' => $serviceFilter,
                'page' => $page + 1
            ])) ?>" aria-label="Следующая страница">›</a>
        <?php else: ?>
            <span class="admin-pagination-disabled">›</span>
        <?php endif; ?>

    </nav>
<?php endif; ?>


<?php endif; ?>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
