<?php
// Session / access-control helpers shared by every page.

if (!defined('ADMIN_EMAIL')) {
    define('ADMIN_EMAIL', 'admin@gmail.com');
}

function redirect_to(string $location): void
{
    header("Location: $location");
    exit();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function current_user_id(): int
{
    return isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
}

function is_admin(): bool
{
    return isset($_SESSION['user_email']) && $_SESSION['user_email'] === ADMIN_EMAIL;
}

// Redirects to the login page unless a user is logged in, otherwise returns the user id.
function require_login(): int
{
    if (!is_logged_in()) {
        redirect_to('login.php');
    }

    return current_user_id();
}

// Redirects to the login page unless the admin is logged in.
function require_admin(): void
{
    if (!is_admin()) {
        redirect_to('login.php');
    }
}
