<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('booking.php');
}
csrf_check();

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$checkIn  = trim($_POST['check_in'] ?? '');
$checkOut = trim($_POST['check_out'] ?? '');
$roomId   = !empty($_POST['room_id']) ? (int)$_POST['room_id'] : null;
$message  = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '') $errors[] = 'Please enter your name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
if ($phone === '') $errors[] = 'Please enter a phone number.';

$inDate = DateTime::createFromFormat('Y-m-d', $checkIn);
$outDate = DateTime::createFromFormat('Y-m-d', $checkOut);
if (!$inDate) $errors[] = 'Please choose a valid check-in date.';
if (!$outDate) $errors[] = 'Please choose a valid check-out date.';
if ($inDate && $outDate && $outDate <= $inDate) $errors[] = 'Check-out date must be after check-in date.';
if ($inDate && $inDate < new DateTime('today')) $errors[] = 'Check-in date cannot be in the past.';

if ($errors) {
    flash_set(implode(' ', $errors), 'error');
    // repopulate the form by echoing $_POST back via a redirect is lossy over GET,
    // so we store it in session briefly instead.
    $_SESSION['booking_prefill'] = $_POST;
    redirect('booking.php');
}

$roomName = null;
if ($roomId) {
    $stmt = db()->prepare('SELECT name FROM rooms WHERE id = ?');
    $stmt->execute([$roomId]);
    $room = $stmt->fetch();
    if ($room) $roomName = $room['name'];
}

$stmt = db()->prepare('INSERT INTO bookings (name, email, phone, room_id, room_name, check_in, check_out, message) VALUES (?,?,?,?,?,?,?,?)');
$stmt->execute([$name, $email, $phone, $roomId, $roomName, $checkIn, $checkOut, $message]);
$bookingId = db()->lastInsertId();

$emailSent = send_booking_notification([
    'name' => $name, 'email' => $email, 'phone' => $phone,
    'room_name' => $roomName, 'check_in' => $checkIn, 'check_out' => $checkOut,
    'message' => $message,
]);

if ($emailSent) {
    db()->prepare('UPDATE bookings SET email_sent = 1 WHERE id = ?')->execute([$bookingId]);
}

flash_set(
    "Thanks, {$name} — your request for {$checkIn} to {$checkOut} has been received. We'll be in touch shortly to confirm.",
    'success'
);
redirect('booking.php');
