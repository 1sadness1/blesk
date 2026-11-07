<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

Auth::requireUser();

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? null;

unset(
    $_SESSION['errors'],
    $_SESSION['old'],
    $_SESSION['success']
);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание заявки — Блеск</title>
</head>
<body>

<h1>Оформление заявки</h1>

<?php if ($success): ?>
    <p style="color: green;">
        <?= htmlspecialchars((string)$success, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <p style="color: red;">
        <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form action="/backend/actions/application_create.php"
      method="POST" novalidate>

    <p>
        <label for="address">Адрес уборки:</label>
        <input type="text" id="address" name="address"
            value="<?= htmlspecialchars($old['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <?php if (isset($errors['address'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($errors['address'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <label for="contact_phone">Контактный телефон:</label>
        <input type="tel" id="contact_phone" name="contact_phone"
            placeholder="8(999)123-45-67"
            value="<?= htmlspecialchars($old['contact_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <?php if (isset($errors['contact_phone'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($errors['contact_phone'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <label for="desired_datetime">Желаемая дата и время:</label>
        <input type="text" id="desired_datetime"
            name="desired_datetime"
            placeholder="15.11.2026 14:00"
            value="<?= htmlspecialchars($old['desired_datetime'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <?php if (isset($errors['desired_datetime'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($errors['desired_datetime'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <label for="service_name">Вид услуги:</label>
        <input type="text" id="service_name" name="service_name"
            placeholder="Генеральная уборка"
            value="<?= htmlspecialchars($old['service_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <?php if (isset($errors['service_name'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($errors['service_name'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <label for="payment_method_id">Способ оплаты:</label>
        <select id="payment_method_id" name="payment_method_id">
            <option value="">Выберите способ оплаты</option>

            <option value="1"
                <?= ($old['payment_method_id'] ?? '') === '1' ? 'selected' : '' ?>>
                Наличными
            </option>

            <option value="2"
                <?= ($old['payment_method_id'] ?? '') === '2' ? 'selected' : '' ?>>
                Банковской картой
            </option>
        </select>
    </p>

    <?php if (isset($errors['payment_method_id'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($errors['payment_method_id'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <button type="submit">Отправить заявку</button>
    </p>

</form>

<p><a href="/index.php">На главную</a></p>

</body>
</html>
