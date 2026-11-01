<?php

/**
 * Minimal .env loader for the project.
 *
 * The project does not use Composer, so this keeps configuration dependency-free.
 * Existing environment variables always take precedence over values in the file.
 */
class Env
{
    private static array $loadedFiles = [];

    public static function load(string $path): void
    {
        $path = realpath($path) ?: $path;

        if (isset(self::$loadedFiles[$path])) {
            return;
        }

        self::$loadedFiles[$path] = true;

        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_starts_with($line, 'export ')) {
                $line = trim(substr($line, 7));
            }

            $parts = explode('=', $line, 2);

            if (count($parts) !== 2) {
                continue;
            }

            $name = trim($parts[0]);
            $value = trim($parts[1]);

            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
                continue;
            }

            if (
                strlen($value) >= 2
                && (
                    ($value[0] === '"' && $value[strlen($value) - 1] === '"')
                    || ($value[0] === "'" && $value[strlen($value) - 1] === "'")
                )
            ) {
                $value = substr($value, 1, -1);
            }

            // Do not overwrite values supplied by the web server or shell.
            if (getenv($name) !== false) {
                continue;
            }

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;

            if (function_exists('putenv')) {
                putenv($name . '=' . $value);
            }
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        foreach ([$_ENV, $_SERVER] as $source) {
            if (array_key_exists($key, $source) && $source[$key] !== '') {
                return (string) $source[$key];
            }
        }

        $value = getenv($key);

        if ($value === false || $value === '') {
            return $default;
        }

        return (string) $value;
    }
}
