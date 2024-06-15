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

    /**
     * Retrieves the singleton instance of the Session class.
     *
     * @return Session The singleton instance of the Session class.
     */
    public static function instance(): Session
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Starts the session if not already started and sets the 'started_at' timestamp.
     */
    public function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $this->set('started_at', time());
        }
    }

    /**
     * Retrieves all session variables.
     *
     * @return array Associative array of all session variables.
     */
    public function getAll(): array
    {
        return $_SESSION;
    }

    /**
     * Retrieves the value of a session variable by its key.
     *
     * @param string $key The key of the session variable to retrieve.
     * @return mixed|null The value of the session variable if set, otherwise null.
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    /**
     * Sets the value of a session variable.
     *
     * @param string $key The key of the session variable to set.
     * @param mixed $value The value to assign to the session variable.
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unsets (removes) a session variable by its key.
     *
     * @param string $key The key of the session variable to unset.
     */
    public function unset(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clears all session variables.
     */
    public function clear(): void
    {
        session_unset();
    }

    /**
     * Cleans the output buffer and returns its contents.
     *
     * @return false|string The contents of the output buffer or false on failure.
     */
    public function clean(): false|string
    {
        return ob_get_clean();
    }

    /**
     * Checks if a session variable exists by its key.
     *
     * @param string $key The key of the session variable to check.
     * @return bool True if the session variable exists, otherwise false.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Regenerates the session ID.
     */
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }
}