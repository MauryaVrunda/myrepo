<?php
// Central configuration. Credentials are read from the environment (or a local
// .env file that must never be committed) instead of being hardcoded.

function load_env(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\"'");
        if (getenv($key) === false) {
            putenv("$key=$value");
        }
    }
}

function env(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    return ($value === false || $value === '') ? $default : $value;
}

load_env(__DIR__ . '/.env');

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'chic-charm beads'));

define('SMTP_HOST', env('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int) env('SMTP_PORT', '587'));
define('SMTP_USER', env('SMTP_USER', ''));
define('SMTP_PASS', env('SMTP_PASS', ''));
define('MAIL_FROM', env('MAIL_FROM', SMTP_USER));
define('MAIL_TO', env('MAIL_TO', SMTP_USER));

define('ADMIN_EMAIL', env('ADMIN_EMAIL', 'admin@gmail.com'));

// Mail is only attempted when SMTP credentials are configured.
function smtp_configured(): bool
{
    return SMTP_USER !== '' && SMTP_PASS !== '';
}
