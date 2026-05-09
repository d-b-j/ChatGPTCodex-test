<?php

namespace App\Helpers;

class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    public static function isSuAdmin(): bool
    {
        return self::role() === 'suadmin';
    }

    public static function isAdmin(): bool
    {
        return in_array(
            self::role(),
            ['suadmin', 'admin']
        );
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {

            header('Location: /start');
            exit;
        }
    }

    public static function requireRole(
        array $roles
    ): void {

        if (
            !in_array(self::role(), $roles)
        ) {

            http_response_code(403);

            exit('Forbidden');
        }
    }
}