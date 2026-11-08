<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

Auth::requireUser();

$applications = Application::forUser((int) Auth::id());

$reviews = Review::forUser((int)Auth::id());

if (!isset($_SESSION['review_csrf_token'])) {
    $_SESSION['review_csrf_token'] = bin2hex(random_bytes(32));
}

$reviewFlash = $_SESSION['review_flash'] ?? null;
unset($_SESSION['review_flash']);

$pageTitle = 'Мои заявки — Блеск';
require __DIR__ . '/partials/header.php';
?>

<main class="applications-page">

<div class="applications-heading">
    <h1>Мои заявки</h1>

    <a href="/frontend/application_create.php" class="applications-create-button">Создать заявку</a>
</div>

<?php if (empty($applications)): ?>

    <p>У вас пока нет заявок.</p>

<?php else: ?>
<?php if ($reviewFlash !== null): ?>
    <p><?= htmlspecialchars((string)$reviewFlash,ENT_QUOTES,'UTF-8') ?></p>
<?php endif; ?>
<div class="applications-grid">
    <?php foreach ($applications as $application): ?>

        <article class="application-card">

        
<?php
$cardStatus = (string)$application['status_name'];

$statusClass = match ($cardStatus) {
    'Выполнено' => 'status-completed',
    'В работе' => 'status-progress',
    'Отменено' => 'status-cancelled',
    default => 'status-new'
};
?>

<div class="application-card-header">
    <h2>Заявка № <?= (int)$application['id'] ?></h2>

    <span class="application-status <?= $statusClass ?>">
        <?= htmlspecialchars($cardStatus, ENT_QUOTES, 'UTF-8') ?>
    </span>
</div>

<p class="application-service">
    <?= htmlspecialchars((string)$application['service_name'], ENT_QUOTES, 'UTF-8') ?>
</p>

<p class="application-date">
    <?= htmlspecialchars((string)$application['desired_datetime'], ENT_QUOTES, 'UTF-8') ?>
</p>

<p class="application-address">
    <?= htmlspecialchars((string)$application['address'], ENT_QUOTES, 'UTF-8') ?>
</p>

<div class="application-review">


        
<?php
$applicationId = (int)$application['id'];
$statusName = (string)$application['status_name'];
$existingReview = $reviews[$applicationId] ?? null;
?>


<?php if ($existingReview !== null): ?>

    <p><strong>Отзыв оставлен</strong>· Оценка: <?= (int)$existingReview['rating'] ?> из 5</p>

    <button type="button" onclick="document.getElementById('view-review-<?= $applicationId ?>').showModal()" style="background:none; border:none; padding:0; color:#0645ad; text-decoration:underline; cursor:pointer; font:inherit;">Посмотреть отзыв</button>

    <dialog id="view-review-<?= $applicationId ?>"aria-labelledby="view-review-title-<?= $applicationId ?>"style="width:min(420px, 90%); box-sizing:border-box; padding:24px; border:1px solid #ccc; border-radius:10px;">

        <button type="button" aria-label="Закрыть окно" onclick="this.closest('dialog').close()" style="position:absolute; top:12px; right:12px; border:none; background:none; font-size:24px; cursor:pointer;">
            &times;
        </button>

        <h3 id="view-review-title-<?= $applicationId ?>">Ваш отзыв</h3>

        <p>Заявка № <?= $applicationId ?></p>

        <p><strong>Оценка:</strong><?= (int)$existingReview['rating'] ?> из 5</p>

        <p><strong>Текст отзыва:</strong></p>

        <p><?= nl2br(htmlspecialchars((string)$existingReview['review_text'],ENT_QUOTES,'UTF-8')) ?></p>

        <button type="button"onclick="this.closest('dialog').close()">Закрыть</button>

    </dialog>

<?php elseif ($statusName === 'Выполнено'): ?>

    <p>Услуга выполнена.</p>

    <button type="button" onclick="document.getElementById('review-dialog-<?= $applicationId ?>').showModal()" style="background:none; border:none; padding:0; color:#0645ad; text-decoration:underline; cursor:pointer; font:inherit;">Оставить отзыв</button>

    <dialog id="review-dialog-<?= $applicationId ?>"aria-labelledby="review-title-<?= $applicationId ?>"style="width: min(420px, 90%); box-sizing:border-box; padding:24px; border:1px solid #ccc; border-radius:10px;">

        <h3 id="review-title-<?= $applicationId ?>">Оставить отзыв</h3>
    <button type="button" aria-label="Закрыть окно" onclick="this.closest('dialog').close()"style="position:absolute; top:12px; right:12px; border:none; background:none; font-size:24px; cursor:pointer;">
    &times;
    </button>    

        <p>Заявка № <?= $applicationId ?></p>

        <form action="/backend/actions/review_create.php" method="POST">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['review_csrf_token'],ENT_QUOTES,'UTF-8') ?>">

            <input type="hidden" name="application_id" value="<?= $applicationId ?>">

            <p>
                <label for="rating-<?= $applicationId ?>">Ваша оценка:</label>
            </p>

            <p>
                <select name="rating" id="rating-<?= $applicationId ?>"required>
                    <option value="">Выберите оценку</option>
                    <option value="5">5 — Отлично</option>
                    <option value="4">4 — Хорошо</option>
                    <option value="3">3 — Нормально</option>
                    <option value="2">2 — Плохо</option>
                    <option value="1">1 — Очень плохо</option>
                </select>
            </p>

            <p>
                <label for="review-<?= $applicationId ?>">Текст отзыва:</label>
            </p>

            <p>
                <textarea name="review_text" id="review-<?= $applicationId ?>"rows="4" required style="width:100%; box-sizing:border-box;"></textarea>
            </p>

            <button type="submit">Отправить отзыв</button>

            <button type="button" onclick="this.closest('dialog').close()">Отмена</button>

        </form>

    </dialog>

<?php else: ?>

    <p class="review-hint">
        Отзыв после выполнения услуги
    </p>

<?php endif; ?>

</div>

</article>

<?php endforeach; ?>
</div>
<?php endif; ?>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
