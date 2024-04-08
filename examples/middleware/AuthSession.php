<?php

namespace Csu\PsrFramework\Examples\middleware;

class AuthSession
{
    public function createSession(string $username, string $password): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); // Запуск сессии, если она еще не была запущена
        }

        $_SESSION['username'] = $username; // Сохраняем логин в сессии
        $_SESSION['authenticated'] = true; // Устанавливаем признак аутентификации
    }
}

