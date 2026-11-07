<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function (string $className): void {
    $file = __DIR__ . '/classes/' . $className . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});
