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
                'cookie_secure' => true, // Only if using HTTPS
                'use_strict_mode' => true,
            ]);
        }
    }

    private function __clone()
    {
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
        }
        $this->set('started_at', time());
    }

    /** Returns the current session. */
    public function getAll(): array
    {
        return $_SESSION;
    }

    /** Generic function. Returns the session searched by key. */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    /** Sets a new value in the session. */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /** Unsets a value by key. */
    public function unset(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /** Clears the session. */
    public function clear(): void
    {
        session_unset();
    }

    /** Checks if key exists in array. */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /** Regenerate session ID. */
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }
}