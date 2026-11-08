<?php
$pageTitle = $pageTitle ?? 'Блеск — клининговые услуги';
$isLoggedIn = Auth::isLoggedIn();
$isAdmin = Auth::isAdmin();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">

        <a class="logo" href="/index.php">Блеск</a>

        <nav class="desktop-nav" aria-label="Основная навигация">

            <a href="/index.php">Главная</a>

            <?php if (!$isLoggedIn): ?>

                <a href="/frontend/login.php">Вход</a>
                <a href="/frontend/register.php">Регистрация</a>

            <?php elseif ($isAdmin): ?>

                <a href="/frontend/admin.php">Панель администратора</a>

                <form class="logout-form"
                      action="/backend/actions/logout.php"
                      method="POST">
                    <button class="logout-button" type="submit">
                        Выйти
                    </button>
                </form>

            <?php else: ?>

                <a href="/frontend/application_create.php">
                    Создать заявку
                </a>

                <a href="/frontend/applications.php">
                    Мои заявки
                </a>

                <form class="logout-form"
                      action="/backend/actions/logout.php"
                      method="POST">
                    <button class="logout-button" type="submit">
                        Выйти
                    </button>
                </form>

            <?php endif; ?>

        </nav>

        <details class="mobile-menu">

            <summary aria-label="Открыть меню">
                <span></span>
                <span></span>
                <span></span>
            </summary>

            <nav aria-label="Мобильная навигация">

                <a href="/index.php">Главная</a>

                <?php if (!$isLoggedIn): ?>

                    <a href="/frontend/login.php">Вход</a>
                    <a href="/frontend/register.php">Регистрация</a>

                <?php elseif ($isAdmin): ?>

                    <a href="/frontend/admin.php">
                        Панель администратора
                    </a>

                    <form class="logout-form"
                          action="/backend/actions/logout.php"
                          method="POST">
                        <button class="logout-button" type="submit">
                            Выйти
                        </button>
                    </form>

                <?php else: ?>

                    <a href="/frontend/application_create.php">
                        Создать заявку
                    </a>

                    <a href="/frontend/applications.php">
                        Мои заявки
                    </a>

                    <form class="logout-form"
                          action="/backend/actions/logout.php"
                          method="POST">
                        <button class="logout-button" type="submit">
                            Выйти
                        </button>
                    </form>

                <?php endif; ?>

            </nav>

        </details>

    </div>
</header>
