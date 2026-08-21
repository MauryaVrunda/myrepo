<?php
// Database connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect('localhost', 'root', '', 'portfgenie', 3307);
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(503);
    exit("Service temporarily unavailable. Please try again later.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';              // Reset token provided by the user
    $newPassword = $_POST['new_password'] ?? ''; // New password entered by the user
    $confirmPassword = $_POST['confirm_password'] ?? ''; // Confirmation of the new password

    if ($newPassword === $confirmPassword) {
        try {
            // Check if token exists, is valid, and hasn't expired
            $query = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
            $query->bind_param("s", $token); // Bind token parameter securely
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                // Token is valid; retrieve user's details
                $user = $result->fetch_assoc();

                // Hash the new password securely
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the user's password in the database
                $updateQuery = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
                $updateQuery->bind_param("ss", $hashedPassword, $token);
                $updateQuery->execute();

                echo "<h2>"."Password has been reset successfully!"."</h2>";
            } else {
                echo "Invalid or expired token.";
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Password reset failed: " . $e->getMessage());
            echo "Error updating the password. Please try again.";
        }
    } else {
        echo "Passwords do not match. Please ensure both fields are identical.";
    }
}
?>