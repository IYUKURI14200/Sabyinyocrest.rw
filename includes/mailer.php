<?php
/**
 * includes/mailer.php — sends email via Gmail SMTP using PHPMailer.
 */
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Send an email. Returns true on success, or a string with the error message on failure.
 */
function send_mail(string $to, string $toName, string $subject, string $htmlBody, string $textBody) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to, $toName);
        $mail->addReplyTo($to, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $textBody;

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        return $mail->ErrorInfo ?: $e->getMessage();
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Build and send the "new booking request" notification to the hotel.
 */
function send_booking_notification(array $booking): bool {
    $settings = get_settings();
    $to = BOOKING_NOTIFY_EMAIL !== '' ? BOOKING_NOTIFY_EMAIL : $settings['email'];

    $subject = 'New booking request — ' . $booking['name'] . ' (' . $booking['check_in'] . ' to ' . $booking['check_out'] . ')';

    $html = '<div style="font-family:sans-serif; max-width:520px;">'
        . '<h2 style="margin-bottom:4px;">New booking request</h2>'
        . '<p style="color:#666; margin-top:0;">via ' . e($settings['brand_name']) . ' website</p>'
        . '<table cellpadding="8" style="border-collapse:collapse; width:100%;">'
        . '<tr><td style="border:1px solid #eee;"><b>Name</b></td><td style="border:1px solid #eee;">' . e($booking['name']) . '</td></tr>'
        . '<tr><td style="border:1px solid #eee;"><b>Email</b></td><td style="border:1px solid #eee;">' . e($booking['email']) . '</td></tr>'
        . '<tr><td style="border:1px solid #eee;"><b>Phone</b></td><td style="border:1px solid #eee;">' . e($booking['phone']) . '</td></tr>'
        . '<tr><td style="border:1px solid #eee;"><b>Room</b></td><td style="border:1px solid #eee;">' . e($booking['room_name'] ?: 'No preference') . '</td></tr>'
        . '<tr><td style="border:1px solid #eee;"><b>Check-in</b></td><td style="border:1px solid #eee;">' . e($booking['check_in']) . '</td></tr>'
        . '<tr><td style="border:1px solid #eee;"><b>Check-out</b></td><td style="border:1px solid #eee;">' . e($booking['check_out']) . '</td></tr>'
        . '<tr><td style="border:1px solid #eee;"><b>Message</b></td><td style="border:1px solid #eee;">' . nl2br(e($booking['message'])) . '</td></tr>'
        . '</table></div>';

    $text = "New booking request\n\n"
        . "Name: {$booking['name']}\n"
        . "Email: {$booking['email']}\n"
        . "Phone: {$booking['phone']}\n"
        . "Room: " . ($booking['room_name'] ?: 'No preference') . "\n"
        . "Check-in: {$booking['check_in']}\n"
        . "Check-out: {$booking['check_out']}\n"
        . "Message: {$booking['message']}\n";

    $result = send_mail($to, $settings['brand_name'], $subject, $html, $text);
    return $result === true;
}
