<?php
$host = "localhost";       // Or your hosting DB server
$user = "root";            // Your DB username
$pass = "";                // Your DB password (on XAMPP it's usually empty)
$dbname = "chic-charm beads"; // Your database name

// Make mysqli throw on failure so a broken query can never be mistaken for an
// empty result or a successful write.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Anything not handled by the page itself is logged and reported as a generic
// failure instead of leaking queries, paths or credentials to the visitor.
set_exception_handler(function (Throwable $e) {
    error_log("Unhandled error: " . $e);
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo "Something went wrong. Please try again later.";
});

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(503);
    exit("Service temporarily unavailable. Please try again later.");
}
?>
