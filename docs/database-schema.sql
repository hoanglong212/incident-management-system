-- Location-Based Incident Management System
-- Database schema for MySQL 8.x
-- Note: This script is designed for a fresh local development database.

CREATE DATABASE IF NOT EXISTS incident_management_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE incident_management_system;

-- =========================================================
-- 1. Roles
-- =========================================================
CREATE TABLE IF NOT EXISTS roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 2. Users
-- =========================================================
CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NULL,
  avatar_url VARCHAR(255) NULL,
  status ENUM('ACTIVE', 'INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,

  CONSTRAINT fk_users_role
    FOREIGN KEY (role_id) REFERENCES roles(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  INDEX idx_users_role_id (role_id),
  INDEX idx_users_status (status),
  INDEX idx_users_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 3. Incident Categories
-- =========================================================
CREATE TABLE IF NOT EXISTS incident_categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,

  INDEX idx_incident_categories_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 4. Incidents
-- =========================================================
CREATE TABLE IF NOT EXISTS incidents (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(30) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  priority ENUM('LOW', 'MEDIUM', 'HIGH', 'URGENT') NOT NULL DEFAULT 'MEDIUM',
  status ENUM('NEW', 'ASSIGNED', 'IN_PROGRESS', 'PENDING', 'RESOLVED', 'CLOSED', 'REJECTED') NOT NULL DEFAULT 'NEW',
  reporter_id BIGINT UNSIGNED NOT NULL,
  assigned_to BIGINT UNSIGNED NULL,

  address VARCHAR(255) NULL,
  ward VARCHAR(100) NULL,
  district VARCHAR(100) NULL,
  city VARCHAR(100) NULL,
  latitude DECIMAL(10, 7) NOT NULL,
  longitude DECIMAL(10, 7) NOT NULL,

  -- For MySQL 8.x spatial queries. Insert this value from backend using:
  -- ST_SRID(POINT(longitude, latitude), 4326)
  -- If your MySQL version supports SRID column attributes, you may change this to:
  -- location POINT NOT NULL SRID 4326
  location POINT NOT NULL,

  occurred_at DATETIME NULL,
  assigned_at DATETIME NULL,
  resolved_at DATETIME NULL,
  closed_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,

  CONSTRAINT fk_incidents_category
    FOREIGN KEY (category_id) REFERENCES incident_categories(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  CONSTRAINT fk_incidents_reporter
    FOREIGN KEY (reporter_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  CONSTRAINT fk_incidents_assigned_to
    FOREIGN KEY (assigned_to) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,

  INDEX idx_incidents_code (code),
  INDEX idx_incidents_category_id (category_id),
  INDEX idx_incidents_reporter_id (reporter_id),
  INDEX idx_incidents_assigned_to (assigned_to),
  INDEX idx_incidents_status (status),
  INDEX idx_incidents_priority (priority),
  INDEX idx_incidents_created_at (created_at),
  INDEX idx_incidents_deleted_at (deleted_at),
  SPATIAL INDEX idx_incidents_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 5. Incident Attachments
-- =========================================================
CREATE TABLE IF NOT EXISTS incident_attachments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  incident_id BIGINT UNSIGNED NOT NULL,
  uploaded_by BIGINT UNSIGNED NOT NULL,
  file_url VARCHAR(255) NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) NOT NULL,
  file_size BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,

  CONSTRAINT fk_attachments_incident
    FOREIGN KEY (incident_id) REFERENCES incidents(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  CONSTRAINT fk_attachments_uploaded_by
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  INDEX idx_attachments_incident_id (incident_id),
  INDEX idx_attachments_uploaded_by (uploaded_by),
  INDEX idx_attachments_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 6. Incident Comments
-- =========================================================
CREATE TABLE IF NOT EXISTS incident_comments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  incident_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,

  CONSTRAINT fk_comments_incident
    FOREIGN KEY (incident_id) REFERENCES incidents(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  CONSTRAINT fk_comments_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  INDEX idx_comments_incident_id (incident_id),
  INDEX idx_comments_user_id (user_id),
  INDEX idx_comments_created_at (created_at),
  INDEX idx_comments_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 7. Incident Status Logs
-- =========================================================
CREATE TABLE IF NOT EXISTS incident_status_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  incident_id BIGINT UNSIGNED NOT NULL,
  old_status ENUM('NEW', 'ASSIGNED', 'IN_PROGRESS', 'PENDING', 'RESOLVED', 'CLOSED', 'REJECTED') NULL,
  new_status ENUM('NEW', 'ASSIGNED', 'IN_PROGRESS', 'PENDING', 'RESOLVED', 'CLOSED', 'REJECTED') NOT NULL,
  action VARCHAR(50) NOT NULL DEFAULT 'STATUS_CHANGED',
  changed_by BIGINT UNSIGNED NOT NULL,
  note TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_status_logs_incident
    FOREIGN KEY (incident_id) REFERENCES incidents(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  CONSTRAINT fk_status_logs_changed_by
    FOREIGN KEY (changed_by) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  INDEX idx_status_logs_incident_id (incident_id),
  INDEX idx_status_logs_changed_by (changed_by),
  INDEX idx_status_logs_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 8. Notifications
-- =========================================================
CREATE TABLE IF NOT EXISTS notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  incident_id BIGINT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  type VARCHAR(50) NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_notifications_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  CONSTRAINT fk_notifications_incident
    FOREIGN KEY (incident_id) REFERENCES incidents(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,

  INDEX idx_notifications_user_id (user_id),
  INDEX idx_notifications_incident_id (incident_id),
  INDEX idx_notifications_is_read (is_read),
  INDEX idx_notifications_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 9. Audit Logs
-- =========================================================
CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(100) NOT NULL,
  entity_id BIGINT UNSIGNED NULL,
  old_value JSON NULL,
  new_value JSON NULL,
  ip_address VARCHAR(50) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_audit_logs_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,

  INDEX idx_audit_logs_user_id (user_id),
  INDEX idx_audit_logs_action (action),
  INDEX idx_audit_logs_entity (entity_type, entity_id),
  INDEX idx_audit_logs_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Seed Data
-- =========================================================
INSERT INTO roles (name, description) VALUES
  ('USER', 'Reporter who creates and follows personal incident tickets'),
  ('ADMIN', 'Coordinator who reviews, assigns, and manages incidents'),
  ('TECHNICIAN', 'Staff member who handles assigned incidents'),
  ('MANAGER', 'Manager who monitors dashboard, map, and reports')
ON DUPLICATE KEY UPDATE
  description = VALUES(description),
  updated_at = CURRENT_TIMESTAMP;

INSERT INTO incident_categories (name, description) VALUES
  ('Traffic', 'Traffic accidents, congestion, road incidents, or transport-related issues'),
  ('Camera', 'Camera malfunction, disconnected camera, or abnormal monitoring device behavior'),
  ('Network', 'Internet, LAN, Wi-Fi, server connection, or network infrastructure issues'),
  ('Equipment', 'Hardware, computer, screen, printer, sensor, or other device issues'),
  ('Security', 'Security, safety, unauthorized access, or emergency incidents'),
  ('Environment', 'Flooding, waste, pollution, weather impact, or environmental incidents'),
  ('Facility', 'Building, room, electricity, lighting, air conditioner, or infrastructure issues'),
  ('Software', 'Application error, system bug, login issue, or software malfunction'),
  ('Other', 'Other incidents that do not match existing categories')
ON DUPLICATE KEY UPDATE
  description = VALUES(description),
  updated_at = CURRENT_TIMESTAMP;

-- =========================================================
-- Example spatial query
-- Find incidents within 1 kilometer of a given point.
-- POINT order is POINT(longitude, latitude), not POINT(latitude, longitude).
-- =========================================================
-- SELECT id, code, title, status, priority, address,
--        ST_Distance_Sphere(location, ST_SRID(POINT(106.7009000, 10.7769000), 4326)) AS distance_meters
-- FROM incidents
-- WHERE deleted_at IS NULL
--   AND ST_Distance_Sphere(location, ST_SRID(POINT(106.7009000, 10.7769000), 4326)) <= 1000
-- ORDER BY distance_meters ASC;
