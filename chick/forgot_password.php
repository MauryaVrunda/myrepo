<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ REMOVE THIS LINE:
// require 'vendor/autoload.php'; ← ❌ DELETE THIS

// ✅ USE MANUAL INCLUDES INSTEAD:
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Connect to database (optional)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect('localhost', 'root', '', 'portfgenie', 3307);
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(503);
    exit("Service temporarily unavailable. Please try again later.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vrundamaurya07@gmail.com';
            $mail->Password   = 'msft qfxp bfjj hgbu'; // App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('vrundamaurya07@gmail.com', 'Career Guide');
            $mail->addAddress('support@careerguide.com');

            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "
                <h3>New Message from Contact Form</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Message:</strong><br>{$message}</p>
            ";

            $mail->send();
            echo "<p style='color:green;'>Message sent successfully!</p>";
        } catch (Exception $e) {
            error_log("Password reset email failed for $email: " . $mail->ErrorInfo);
            echo "<p style='color:red;'>Message could not be sent. Please try again later.</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }
}
?>
