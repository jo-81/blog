<?php

declare(strict_types=1);

namespace Framework\Session;

class MessageFlash implements MessageFlashInterface
{
    private const SESSION_KEY = '_flash_messages';

    public function __construct(private SessionInterface $session) {}

    public function add(string $type, string|array $message): void
    {
        $flashes = $this->session->get(self::SESSION_KEY, []);

        if (!isset($flashes[$type])) {
            $flashes[$type] = [];
        }

        if (is_array($message)) {
            array_push($flashes[$type], $message);
        } else {
            $flashes[$type][] = $message;
        }

        $this->session->set(self::SESSION_KEY, $flashes);
    }

    public function has(string $type): bool
    {
        $flashes = $this->session->get(self::SESSION_KEY, []);

        return !empty($flashes[$type]);
    }

    public function get(string $type): array
    {
        $flashes = $this->session->get(self::SESSION_KEY, []);

        if (empty($flashes[$type])) {
            return [];
        }

        $messages = $flashes[$type];

        unset($flashes[$type]);

        if (empty($flashes)) {
            $this->session->remove(self::SESSION_KEY);
        } else {
            $this->session->set(self::SESSION_KEY, $flashes);
        }

        return $messages;
    }

    public function all(): array
    {
        $messages = $this->session->get(self::SESSION_KEY, []);

        $this->session->remove(self::SESSION_KEY);

        return $messages;
    }
}
