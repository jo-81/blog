<?php

namespace Framework\Session;

class PHPSession implements SessionInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(private array $options = []) {}

    public function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start($this->options);
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            $_SESSION = [];
        }
    }
}