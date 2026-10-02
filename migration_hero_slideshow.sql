-- ============================================================
-- Migration: add Hero Slideshow feature
-- Run this ONCE in phpMyAdmin (SQL tab, inside your sabyinyo_crest
-- database) if your site was set up before this feature existed.
-- Safe to run even if you're not sure — CREATE TABLE IF NOT EXISTS
-- means it won't break anything if it's already there.
-- ============================================================

CREATE TABLE IF NOT EXISTS hero_slides (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO hero_slides (image, sort_order) VALUES
('assets/img/exterior_balcony_day.jpg', 1),
('assets/img/exterior_gate_teal.jpg', 2),
('assets/img/porch_plants.jpg', 3);
