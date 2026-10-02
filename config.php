<?php
/**
 * config.php — site configuration
 * Edit the values below to match your hosting environment, then
 * make sure this file is NOT publicly downloadable (most PHP hosts
 * already block .php files from being served as plain text, but on
 * shared hosting it's good practice to keep this one level above
 * the public web root if your host allows it).
 */

// ---- Database credentials ----
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'sabyinyo_crest');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---- App ----
define('SITE_ROOT', __DIR__);                 // absolute filesystem path
define('UPLOADS_DIR', SITE_ROOT . '/uploads'); // where admin-uploaded photos are stored
define('UPLOADS_URL', 'uploads');              // relative URL to the uploads folder
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024);   // 5MB per image
define('VIDEO_MAX_UPLOAD_BYTES', 60 * 1024 * 1024); // 60MB per video

// ---- Sessions / security ----
// Generate your own random string for production, e.g. bin2hex(random_bytes(32))
define('APP_SECRET', 'change-this-to-a-long-random-string-before-going-live');

// ---- Email (booking notifications) ----
// Using Gmail SMTP. To set this up:
//  1. Turn on 2-Step Verification on the Gmail account you want to send from:
//     https://myaccount.google.com/security
//  2. Create an "App Password" for this site:
//     https://myaccount.google.com/apppasswords
//  3. Paste that 16-character app password below (NOT your normal Gmail password).
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', 'iyukurialexandre7@gmail.com');   // the Gmail account sending the emails
define('SMTP_PASS', 'ucoc yolw nwtk txxx');  // the App Password from step 2 above
define('MAIL_FROM', SMTP_USER);
define('MAIL_FROM_NAME', 'Sabyinyo Crest Website');
// Where booking requests get sent. Leave blank to use the hotel email set in
// Admin -> Contact & Footer instead.
define('BOOKING_NOTIFY_EMAIL', 'iyukurialexandre7@gmail.com');

date_default_timezone_set('Africa/Kigali');
