<?php
require 'includes/bootstrap.php';
require 'includes/mailer.php';

// Sanitize form inputs
$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Save message to database
$sql = "INSERT INTO contact_messages (name, email, message) 
        VALUES ('$name', '$email', '$message')";

$email_sent = send_site_mail(
    "New Contact Message from $name",
    "You received a message:\n\nName: $name\nEmail: $email\nMessage:\n$message",
    false
);
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
    if ($conn->query($sql) === TRUE && $email_sent) {
        echo "<h2>🎉 Thank you, $name!</h2>";
        echo "<p>Your message has been saved and emailed to us. We'll contact you soon 💌</p>";
    } elseif ($conn->query($sql) === TRUE && !$email_sent) {
        echo "<h2>✔️ Message Saved!</h2>";
        echo "<p>We saved your message, but email sending failed.</p>";
    } else {
        echo "<h2>❌ Error</h2>";
        echo "<p>Something went wrong while saving your message.</p>";
        echo "<p>" . $conn->error . "</p>";
    }
    $conn->close();
    ?>
    <a href="contact.html">Back to Contact Page</a>
  </div>
</body>
</html>