<?php

namespace Service;

class Session
{
    private static ?Session $instance = null;

    private function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => false, // only true if using HTTPS
                'use_strict_mode' => true,
            ]);
        }
    }

    public static function instance(): Session
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $this->set('started_at', time());
        }
    }

    public function getAll(): array
    {
        return $_SESSION;
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function unset(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function clear(): void
    {
        session_unset();
    }

    public function clean(): false|string
    {
        return ob_get_clean();
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }
}