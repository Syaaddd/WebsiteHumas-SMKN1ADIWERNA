-- Jalankan file ini di phpMyAdmin atau MySQL CLI
CREATE DATABASE IF NOT EXISTS humas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE humas;

CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50) UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','editor') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(255) NOT NULL,
  slug       VARCHAR(255) UNIQUE NOT NULL,
  content    LONGTEXT,
  image      VARCHAR(255),
  status     ENUM('draft','published') DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pages (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  slug             VARCHAR(100) UNIQUE NOT NULL,
  title            VARCHAR(255) NOT NULL,
  content          LONGTEXT,
  meta_description VARCHAR(255),
  updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  value TEXT
);

-- Default admin: username=admin, password=admin123
INSERT IGNORE INTO users (username, password) VALUES (
  'admin',
  '$2y$10$6Hj3g54qzj1oJjeGiXsCCOYAtChmuZ68kHZlsNI0B0l0Bb4.Qodo6'
);

-- Seed default settings
INSERT IGNORE INTO settings (`key`, value) VALUES
  ('site_name',        'Humas'),
  ('site_description', 'Website Resmi Humas'),
  ('site_email',       ''),
  ('site_phone',       ''),
  ('site_address',     ''),
  ('logo',             '');

-- Seed default pages
INSERT IGNORE INTO pages (slug, title, content, meta_description) VALUES
  ('about',   'Tentang Kami',   'Isi konten halaman tentang kami di sini.', 'Tentang Humas'),
  ('contact', 'Hubungi Kami',   'Silakan isi form di bawah untuk menghubungi kami.', 'Kontak Humas');
