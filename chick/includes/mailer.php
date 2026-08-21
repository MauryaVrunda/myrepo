<?php
// Single place where PHPMailer is loaded and configured.
// Credentials come from the environment, or from includes/mail_config.php
// (not committed - copy mail_config.sample.php and fill it in).

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$phpmailerBase = __DIR__ . '/..';
if (file_exists("$phpmailerBase/vendor/autoload.php")) {
    require_once "$phpmailerBase/vendor/autoload.php";
} else {
    $phpmailerSrc = file_exists("$phpmailerBase/src/PHPMailer.php")
        ? "$phpmailerBase/src"
        : "$phpmailerBase/phpmailer/src";

    require_once "$phpmailerSrc/Exception.php";
    require_once "$phpmailerSrc/PHPMailer.php";
    require_once "$phpmailerSrc/SMTP.php";
}

if (file_exists(__DIR__ . '/mail_config.php')) {
    require_once __DIR__ . '/mail_config.php';
}

function mail_setting(string $name, string $default = ''): string
{
    $value = getenv($name);
    if ($value === false || $value === '') {
        $value = defined($name) ? (string) constant($name) : $default;
    }

    return $value;
}

// Sends a mail through the shop SMTP account. Returns false when delivery fails.
function send_site_mail(string $subject, string $body, bool $isHtml = true, ?string $to = null, ?string $fromName = null): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = mail_setting('MAIL_HOST', 'smtp.gmail.com');
        $mail->SMTPAuth = true;
        $mail->Username = mail_setting('MAIL_USERNAME');
        $mail->Password = mail_setting('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = intval(mail_setting('MAIL_PORT', '587'));

        $mail->setFrom(mail_setting('MAIL_FROM', $mail->Username), $fromName ?? mail_setting('MAIL_FROM_NAME', 'Chic Charm Beads'));
        $mail->addAddress($to ?? mail_setting('MAIL_TO', $mail->Username));

        $mail->isHTML($isHtml);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
