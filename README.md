# Sabyinyo Crest — Website + Admin Panel

A hotel website built with plain **HTML, CSS, JavaScript, PHP, and MySQL** —
no framework required. The public site pulls all of its content (rooms,
services, gallery, text, contact info) from a MySQL database, and the
`/admin` panel lets you manage everything through the browser.

This has been fully tested locally (PHP 8.3 + MariaDB 10.11) — login, every
admin CRUD action, and image uploads all verified working end-to-end before
delivery.

## 1. Requirements

- PHP 8.0+ with the **PDO MySQL** and **fileinfo** extensions (both are on
  by default on almost all hosting, including cPanel/shared hosting)
- MySQL 5.7+ or MariaDB 10.3+
- Any standard web server (Apache/LiteSpeed with `.htaccess` support, or Nginx)

## 2. Local setup (e.g. XAMPP / WAMP / MAMP, or a VPS)

1. **Create the database** and import the schema:
   ```
   mysql -u root -p -e "CREATE DATABASE sabyinyo_crest CHARACTER SET utf8mb4;"
   mysql -u root -p sabyinyo_crest < db.sql
   ```
2. **Edit `config.php`** with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sabyinyo_crest');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   ```
3. Point your web server's document root at this folder (the one containing
   `index.php`), or with PHP's built-in server for a quick local test:
   ```
   php -S localhost:8000
   ```
   then open `http://localhost:8000`.
4. Make sure the `uploads/` folder (and its `rooms/`, `gallery/`, `site/`
   subfolders) is **writable** by the web server, e.g.:
   ```
   chmod -R 755 uploads
   ```

## 3. Deploying to shared hosting (cPanel-style, common in Rwanda)

1. Upload every file/folder to your hosting account (e.g. via FTP or the
   File Manager) — usually into `public_html/`.
2. In cPanel → **MySQL Databases**, create a database and a user, and
   attach the user to the database with **All Privileges**.
3. In **phpMyAdmin**, open your new database and use **Import** to upload
   `db.sql`.
4. Edit `config.php` on the server with the database name/user/password
   cPanel gave you.
5. Visit `yourdomain.com/uploads/` in a browser — it should show a blank
   or forbidden page (not a directory listing). If your host requires it,
   set the `uploads/` folder permission to `755`.
6. Visit your site, then `yourdomain.com/admin/login.php` to sign in.

## 4. First login

```
URL:      yoursite.com/admin/login.php
Username: admin
Password: sabyinyo2026
```

**Change this password immediately** from Admin → Account Settings.

## 5. What you can manage from `/admin`

| Section | What it does |
|---|---|
| **Dashboard** | Quick stats and shortcuts |
| **Rooms** | Add/edit/delete rooms — name, price, description, photo |
| **Services** | Add/edit/delete services (with icon picker) |
| **Gallery** | Upload/caption/remove gallery photos |
| **Site Content** | Hero headline/subtitle/photo, welcome text, testimonial banner |
| **Contact & Footer** | Phone numbers, email, address, map, socials, footer lists |
| **Account Settings** | Change your admin username/password |

Every change is saved straight to MySQL and appears on the live site
immediately — no rebuilding or redeploying needed.

## 6. Project structure

```
├── index.php              Public homepage (reads from MySQL)
├── config.php             Database credentials — edit this first
├── db.sql                 Database schema + starter content — import once
├── includes/
│   ├── bootstrap.php      Included at the top of every page
│   ├── db.php             PDO connection
│   ├── functions.php      Helpers (escaping, uploads, CSRF, flash messages)
│   └── auth.php           Admin session/login helpers
├── assets/
│   ├── css/style.css      All styling (public site + admin panel)
│   ├── js/main.js         Public site interactions (nav, lightbox, reveal)
│   ├── js/admin.js        Admin panel interactions
│   └── img/                Default photos used by the starter content
├── uploads/                Admin-uploaded photos land here (writable)
│   ├── rooms/
│   ├── gallery/
│   └── site/
└── admin/
    ├── login.php / logout.php
    ├── dashboard.php
    ├── rooms.php, room_form.php, room_delete.php
    ├── services.php, service_form.php, service_delete.php
    ├── gallery.php, gallery_delete.php
    ├── content.php          Hero / welcome / testimonial text
    ├── contact.php           Contact details + footer lists
    ├── settings.php           Change admin username/password
    └── includes/header.php, footer.php   Shared admin layout
```

## 7. Security notes

- Passwords are hashed with PHP's `password_hash()` (bcrypt) — never stored
  in plain text.
- All forms are protected with CSRF tokens.
- All database queries use prepared statements (no SQL injection).
- Uploaded files are validated by real file content (not just the file
  extension) and stored outside of any PHP-executable context via
  `uploads/.htaccess`.
- Before going live, generate a random string for `APP_SECRET` in
  `config.php`, and — most importantly — **change the default admin
  password**.
- Consider enabling HTTPS (Let's Encrypt is free on most hosts) so login
  credentials aren't sent in plain text.

## 8. Notes on the design

The visual design (colors, fonts, the volcano-ridge divider motif) matches
the single-file version delivered earlier, now split cleanly across
`assets/css/style.css` and PHP templates so it's fully database-driven
instead of edited by hand.
