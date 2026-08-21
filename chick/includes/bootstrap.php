<?php
// Shared entry point for every page: session, database connection and helpers.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../connect.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/products.php';
