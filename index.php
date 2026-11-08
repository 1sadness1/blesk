<?php
declare(strict_types=1);

require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Блеск — клининговые услуги';
$isLoggedIn = Auth::isLoggedIn();
$isAdmin = Auth::isAdmin();

$mainLink = $isAdmin
    ? '/frontend/admin.php'
    : ($isLoggedIn ? '/frontend/application_create.php' : '/frontend/login.php');
$mainLinkText = $isAdmin ? 'Панель администратора' : 'Создать заявку';
?>

<?php require __DIR__ . '/frontend/partials/header.php'; ?>
    <main class="hero">
        <h1>Клининговая компания «Блеск»</h1>
        <p class="subtitle">Уборка квартир и офисов</p>

        <section class="slider" aria-label="Фотографии услуг">
            <div class="slide active"><img src="/frontend/assets/images/slide-1.webp" alt="Чистая светлая гостиная"></div>
            <div class="slide"><img src="/frontend/assets/images/slide-2.webp" alt="Уборка кухни"></div>
            <div class="slide"><img src="/frontend/assets/images/slide-3.webp" alt="Инвентарь для уборки"></div>
            <div class="slide"><img src="/frontend/assets/images/slide-4.webp" alt="Уютная чистая спальня"></div>
            <button class="slider-arrow previous" type="button" aria-label="Предыдущее фото">‹</button>
            <button class="slider-arrow next" type="button" aria-label="Следующее фото">›</button>
        </section>

        <div class="dots" aria-label="Выбор фотографии">
            <button class="dot active" type="button" aria-label="Фото 1" aria-current="true"></button>
            <button class="dot" type="button" aria-label="Фото 2"></button>
            <button class="dot" type="button" aria-label="Фото 3"></button>
            <button class="dot" type="button" aria-label="Фото 4"></button>
        </div>

        <a class="button-primary" href="<?= htmlspecialchars($mainLink, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($mainLinkText, ENT_QUOTES, 'UTF-8') ?>
        </a>
    </main>
    <script src="/frontend/assets/js/app.js"></script>
    <?php require __DIR__ . '/frontend/partials/footer.php'; ?>

