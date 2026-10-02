-- ============================================================
-- Migration: add Featured Video section
-- Run this ONCE in phpMyAdmin (SQL tab, inside your sabyinyo_crest
-- database). Safe to run even if unsure.
-- ============================================================

ALTER TABLE settings
  ADD COLUMN IF NOT EXISTS video_enabled TINYINT(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS video_source ENUM('upload','youtube') DEFAULT 'upload',
  ADD COLUMN IF NOT EXISTS video_file VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS video_youtube_id VARCHAR(50) NULL,
  ADD COLUMN IF NOT EXISTS video_title VARCHAR(150) DEFAULT 'A Look Inside',
  ADD COLUMN IF NOT EXISTS video_caption VARCHAR(300) DEFAULT 'Our guests, our team, and a taste of the Sabyinyo Crest experience.';
