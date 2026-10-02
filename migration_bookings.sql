-- ============================================================
-- Migration: add Booking Requests feature
-- Run this ONCE in phpMyAdmin (SQL tab, inside your sabyinyo_crest
-- database). Safe to run even if unsure — CREATE TABLE IF NOT EXISTS
-- won't break anything if it's already there.
-- ============================================================

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  room_id INT NULL,
  room_name VARCHAR(120) NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  message TEXT,
  status ENUM('new','confirmed','cancelled') DEFAULT 'new',
  email_sent TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
