<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function is_admin(): bool
{
    return isset($_SESSION['user_email']) && $_SESSION['user_email'] === ADMIN_EMAIL;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

function require_admin(): void
{
    if (!is_admin()) {
        header("Location: login.php");
        exit();
    }
}
