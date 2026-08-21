<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ REMOVE THIS LINE:
// require 'vendor/autoload.php'; ← ❌ DELETE THIS

// ✅ USE MANUAL INCLUDES INSTEAD:
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

require_once __DIR__ . '/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $message === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo "<p style='color:red;'>Please fill in all fields with a valid email address.</p>";
    } elseif (!smtp_configured()) {
        echo "<p style='color:red;'>Mail is not configured.</p>";
    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;

            $mail->setFrom(MAIL_FROM, 'Chic Charm Beads');
            $mail->addAddress(MAIL_TO);

            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "
                <h3>New Message from Contact Form</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Message:</strong><br>" . htmlspecialchars($message) . "</p>
            ";

            $mail->send();
            echo "<p style='color:green;'>Message sent successfully!</p>";
        } catch (Exception $e) {
            error_log("Forgot password mail error: " . $mail->ErrorInfo);
            echo "<p style='color:red;'>Could not send the message. Please try again later.</p>";
        }
    }
}
?>
