<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

Auth::requireUser();
$currentUser = User::findById((int)Auth::id());
$defaultPhone = (string)($currentUser['phone'] ?? '');

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? null;

unset(
    $_SESSION['errors'],
    $_SESSION['old'],
    $_SESSION['success']
);

function eApplication($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$desiredDate = (string)($old['desired_date'] ?? '');
$desiredTime = (string)($old['desired_time'] ?? '');

if (
    ($desiredDate === '' || $desiredTime === '') &&
    !empty($old['desired_datetime'])
) {
    if (preg_match(
        '/^(\d{2}\.\d{2}\.\d{4})\s+(\d{2}:\d{2})$/',
        (string)$old['desired_datetime'],
        $matches
    )) {
        $desiredDate = $matches[1];
        $desiredTime = $matches[2];
    }
}

$selectedService = (string)($old['service_name'] ?? '');
$isOtherService = $selectedService === 'Иная услуга';

$pageTitle = 'Создать заявку — Блеск';
require __DIR__ . '/partials/header.php';
?>

<main class="application-page">

    <h1>Создать заявку</h1>

    <?php if ($success): ?>
        <p class="form-success">
            <?= eApplication($success) ?>
        </p>
    <?php endif; ?>

    <?php if (isset($errors['general'])): ?>
        <p class="form-error">
            <?= eApplication($errors['general']) ?>
        </p>
    <?php endif; ?>

    <form
        class="application-form"
        action="/backend/actions/application_create.php"
        method="POST"
        novalidate
    >

        <div class="form-field">
            <label for="address">Адрес уборки*</label>

            <input
                type="text"
                id="address"
                name="address"
                placeholder="Город, улица, дом"
                value="<?= eApplication($old['address'] ?? '') ?>"
                required
            >

            <?php if (isset($errors['address'])): ?>
                <p class="field-error">
                    <?= eApplication($errors['address']) ?>
                </p>
            <?php endif; ?>
        </div>
    
<div class="form-row">

    <div class="form-field">
        <label for="contact_phone">Контактный телефон*</label>

        <input
            type="tel"
            id="contact_phone"
            name="contact_phone"
            placeholder="8(999)123-45-67"
            value="<?= eApplication($old['contact_phone'] ?? $defaultPhone) ?>" required>

        <?php if (isset($errors['contact_phone'])): ?>
            <p class="field-error">
                <?= eApplication($errors['contact_phone']) ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="form-field">
        <label for="desired_datetime">Дата и время*</label>

        <input
            type="text"
            id="desired_datetime"
            name="desired_datetime"
            placeholder="ДД.ММ.ГГГГ ЧЧ:ММ"
            value="<?= eApplication($old['desired_datetime'] ?? '') ?>"
            required
        >

        <?php if (isset($errors['desired_datetime'])): ?>
            <p class="field-error">
                <?= eApplication($errors['desired_datetime']) ?>
            </p>
        <?php endif; ?>
    </div>

</div>

        <div class="form-field">

            <label for="service_name">
                Вид услуги*
            </label>

            <select
                id="service_name"
                name="service_name"
                required
            >
                <option value="">
                    Выберите услугу
                </option>

                <?php
                $services = [
                    'Общий клининг',
                    'Генеральная уборка',
                    'Послестроительная уборка',
                    'Химчистка ковров и мебели',
                    'Иная услуга'
                ];
                ?>

                <?php foreach ($services as $service): ?>
                    <option
                        value="<?= eApplication($service) ?>"
                        <?= $selectedService === $service ? 'selected' : '' ?>
                    >
                        <?= eApplication($service) ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <?php if (isset($errors['service_name'])): ?>
                <p class="field-error">
                    <?= eApplication($errors['service_name']) ?>
                </p>
            <?php endif; ?>

        </div>

        <div
            class="form-field other-service-field"
            id="other-service-field"
            <?= $isOtherService ? '' : 'hidden' ?>
        >

            <label for="other_service_description">
                Опишите услугу*
            </label>

            <textarea
                id="other_service_description"
                name="other_service_description"
                rows="3"
                placeholder="Кратко опишите, какая услуга требуется"
                <?= $isOtherService ? 'required' : '' ?>
            ><?= eApplication($old['other_service_description'] ?? '') ?></textarea>

            <?php if (isset($errors['other_service_description'])): ?>
                <p class="field-error">
                    <?= eApplication($errors['other_service_description']) ?>
                </p>
            <?php endif; ?>

        </div>

        
<div class="form-field payment-field">

    <p class="payment-label">Способ оплаты*</p>

    <div class="payment-options">

        <label class="payment-option">
            <input
                type="radio"
                name="payment_method_id"
                value="1"
                <?= ($old['payment_method_id'] ?? '1') === '1'
                    ? 'checked'
                    : '' ?>
            >
            Наличными
        </label>

        <label class="payment-option">
            <input
                type="radio"
                name="payment_method_id"
                value="2"
                <?= ($old['payment_method_id'] ?? '1') === '2'
                    ? 'checked'
                    : '' ?>
            >
            Банковской картой
        </label>

    </div>

    <?php if (isset($errors['payment_method_id'])): ?>
        <p class="field-error">
            <?= eApplication($errors['payment_method_id']) ?>
        </p>
    <?php endif; ?>

</div>

        <button class="button-primary" type="submit">Отправить</button>

    </form>

</main>

<script src="/frontend/assets/js/app.js" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
