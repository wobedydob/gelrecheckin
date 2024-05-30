<?php

namespace Service;

class Session
{

    /** Starts the session when instance is created. */
    public function __construct(?string $cacheExpire = null, ?string $cacheLimiter = null)
    {
        if (session_status() === PHP_SESSION_NONE) {

            if ($cacheLimiter !== null) {
                session_cache_limiter($cacheLimiter);
            }

            if ($cacheExpire !== null) {
                session_cache_expire($cacheExpire);
            }

            session_start();
        }
    }

    public static function new(): Session
    {
        return new self();
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

}