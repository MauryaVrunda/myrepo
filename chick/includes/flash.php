<?php
// One-shot messages stored in the session between redirects.

function set_flash(string $key, $value): void
{
    $_SESSION[$key] = $value;
}

function has_flash(string $key): bool
{
    return isset($_SESSION[$key]);
}

// Returns the message and removes it from the session.
function take_flash(string $key)
{
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $value = $_SESSION[$key];
    unset($_SESSION[$key]);

    return $value;
}

function render_flash(string $key, string $color): void
{
    $message = take_flash($key);
    if ($message === null) {
        return;
    }

    echo "<p style='color: $color; font-weight: bold;'>" . htmlspecialchars((string) $message) . "</p>";
}
