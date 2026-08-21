<?php
require 'includes/mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $sent = send_site_mail(
            'New Contact Form Submission',
            "
                <h3>New Message from Contact Form</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Message:</strong><br>{$message}</p>
            ",
            true,
            'support@careerguide.com',
            'Career Guide'
        );

        if ($sent) {
            echo "<p style='color:green;'>Message sent successfully!</p>";
        } else {
            echo "<p style='color:red;'>Mailer Error: could not send your message.</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }
}
?>
