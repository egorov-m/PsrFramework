<?php

namespace Csu\App\Middleware;

class AuthSession
{
    public function createSession(string $username, string $password): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['username'] = $username;
        $_SESSION['authenticated'] = true;
    }
}

