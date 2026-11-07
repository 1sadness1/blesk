<?php

declare(strict_types=1);

class Auth
{
    private const ADMIN_ROLE_ID = 2;

    public static function login(string $login, string $password): bool
    {
        $user = User::findByLogin($login);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role_id'] = (int) $user['role_id'];
        unset($_SESSION['role']); 
        return true;
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function id(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public static function isAdmin(): bool
    {
        return self::isLoggedIn()
            && (int) ($_SESSION['role_id'] ?? 0) === self::ADMIN_ROLE_ID;
    }

    public static function requireUser(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /frontend/login.php');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireUser();
        if (!self::isAdmin()) {
            http_response_code(403);
            exit('Доступ запрещён');
        }
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['role_id'], $_SESSION['role']);
        session_regenerate_id(true);
    }
}