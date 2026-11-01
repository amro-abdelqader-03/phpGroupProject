<?php

require_once __DIR__ . '/Env.php';

/**
 * Central Gemini configuration.
 *
 * Keeping the model, endpoint and timeout here means they can be changed in one
 * place (or through environment variables) without touching the controller.
 */
class GeminiConfig
{
    private const DEFAULT_MODEL = 'gemini-3.5-flash-lite';
    private const DEFAULT_API_BASE = 'https://generativelanguage.googleapis.com/v1beta';
    private const DEFAULT_TIMEOUT = 30;

    private static bool $booted = false;

    private static function boot(): void
    {
        if (self::$booted) {
            return;
        }

        Env::load(dirname(__DIR__, 2) . '/.env');
        self::$booted = true;
    }

    public static function apiKey(): string
    {
        self::boot();

        return trim((string) Env::get('GEMINI_API_KEY', ''));
    }

    public static function model(): string
    {
        self::boot();

        $model = trim((string) Env::get('GEMINI_MODEL', self::DEFAULT_MODEL));
        $model = preg_replace('#^models/#i', '', $model) ?? '';

        if (!preg_match('/^[A-Za-z0-9._-]+$/', $model)) {
            return self::DEFAULT_MODEL;
        }

        return $model;
    }

    public static function apiBase(): string
    {
        self::boot();

        $base = trim((string) Env::get('GEMINI_API_BASE', self::DEFAULT_API_BASE));

        if (!filter_var($base, FILTER_VALIDATE_URL)) {
            return self::DEFAULT_API_BASE;
        }

        return rtrim($base, '/');
    }

    public static function timeoutSeconds(): int
    {
        self::boot();

        $timeout = filter_var(
            Env::get('GEMINI_TIMEOUT', (string) self::DEFAULT_TIMEOUT),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 5, 'max_range' => 180]]
        );

        return $timeout === false ? self::DEFAULT_TIMEOUT : $timeout;
    }
}
