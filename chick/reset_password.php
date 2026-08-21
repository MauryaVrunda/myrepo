<?php
require_once __DIR__ . '/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';              // Reset token provided by the user
    $newPassword = $_POST['new_password'] ?? ''; // New password entered by the user
    $confirmPassword = $_POST['confirm_password'] ?? ''; // Confirmation of the new password

    if ($token === '' || strlen($newPassword) < 8) {
        echo "A reset token and a password of at least 8 characters are required.";
    } elseif ($newPassword === $confirmPassword) {
        // Check if token exists, is valid, and hasn't expired
        $query = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
        $query->bind_param("s", $token); // Bind token parameter securely
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            // Token is valid; retrieve user's details
            $user = $result->fetch_assoc();

            // Hash the new password securely
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $updateQuery = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ? AND reset_expiry > NOW()");
            $updateQuery->bind_param("ss", $hashedPassword, $token);
            $updateQuery->execute();

            if ($updateQuery->affected_rows > 0) {
                echo "<h2>"."Password has been reset successfully!"."</h2>";
            } else {
                echo "Error updating the password. Please try again.";
            }
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        echo "Passwords do not match. Please ensure both fields are identical.";
    }
}
?>