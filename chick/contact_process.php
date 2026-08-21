<?php
include 'connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// Save message to database
$message_saved = false;
try {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();
    $message_saved = true;
} catch (mysqli_sql_exception $e) {
    error_log("Contact message insert failed for $email: " . $e->getMessage());
}

// Default email status
$email_sent = false;

// Email sending with PHPMailer
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 🟣 Replace with your Gmail and App Password here
    $mail->Username = 'vrundamaurya07@gmail.com';         // Your Gmail
    $mail->Password =  'msft qfxp bfjj hgbu';      // App password from Google

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $mail->setFrom('vrundamaurya07@gmail.com', 'Chic Charm Beads');
    $mail->addAddress('vrundamaurya07@gmail.com'); // Send to self

    $mail->isHTML(false);
    $mail->Subject = "New Contact Message from $name";
    $mail->Body    = "You received a message:\n\nName: $name\nEmail: $email\nMessage:\n$message";

    $mail->send();
    $email_sent = true;
} catch (Exception $e) {
    error_log("Contact email failed for $email: " . $mail->ErrorInfo);
    $email_sent = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Message Status - Chic Charm Beads</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right,rgb(197, 247, 240),rgb(228, 207, 250));
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .status-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      max-width: 500px;
      text-align: center;
    }
    h2 {
      color: #7f5af0;
    }
    p {
      color: #555;
      font-size: 16px;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      background-color: #7f5af0;
      color: #fff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 20px;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }
    a:hover {
      background-color: #9c7dfc;
    }
  </style>
</head>
<body>
  <div class="status-container">
    <?php
    if ($message_saved && $email_sent) {
        echo "<h2>🎉 Thank you, " . htmlspecialchars($name) . "!</h2>";
        echo "<p>Your message has been saved and emailed to us. We'll contact you soon 💌</p>";
    } elseif ($message_saved) {
        echo "<h2>✔️ Message Saved!</h2>";
        echo "<p>We saved your message, but email sending failed.</p>";
    } else {
        echo "<h2>❌ Error</h2>";
        echo "<p>Something went wrong while saving your message. Please try again later.</p>";
    }
    $conn->close();
    ?>
    <a href="contact.html">Back to Contact Page</a>
  </div>
</body>
</html>