-- ============================================================
-- schema.sql
-- Creates the database + appointments table used by:
--   get_appointments.php, mark_attended.php, clear_all.php,
--   guard-dashboard.php
--
-- Column names here match EXACTLY what get_appointments.php
-- selects (trn, hash_code, full_name, email, mobile_number,
-- purpose, appointment_date, appointment_time, outlet_name,
-- outlet_address, status). If your booking form (confirm.php)
-- writes to this table, make sure its INSERT uses these same
-- column names.
-- ============================================================

CREATE DATABASE IF NOT EXISTS psa_appointment_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE psa_appointment_db;

CREATE TABLE IF NOT EXISTS appointments (
    id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    trn              VARCHAR(50)  NULL,                 -- transaction reference number (form-side)
    hash_code        VARCHAR(100) NOT NULL,             -- "refNumber" shown to guard / printed on slip
    full_name        VARCHAR(150) NOT NULL,
    email            VARCHAR(150) NULL,
    mobile_number    VARCHAR(30)  NULL,
    purpose          VARCHAR(150) NOT NULL,
    appointment_date DATE         NOT NULL,
    appointment_time VARCHAR(20)  NOT NULL,             -- stored like "09:00 AM" to match dashboard JS parsing
    outlet_name      VARCHAR(150) NULL,
    outlet_address   VARCHAR(255) NULL,
    status           ENUM('Pending','Attended','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_hash_code (hash_code),
    KEY idx_date_time (appointment_date, appointment_time),
    KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- Sample rows (safe to delete). Useful to verify the dashboard
-- renders before wiring up the real booking form.
-- ─────────────────────────────────────────────────────────────
INSERT INTO appointments
    (trn, hash_code, full_name, email, mobile_number, purpose, appointment_date, appointment_time, outlet_name, outlet_address, status)
VALUES
    ('TRN-0001', 'HC-AAA111', 'Juan Dela Cruz', 'juan@example.com', '09171234567', 'National ID - New Application', CURDATE(), '09:00 AM', 'PSA CRS Main Gate 1', 'Quezon City', 'Pending'),
    ('TRN-0002', 'HC-BBB222', 'Maria Santos',   'maria@example.com', '09181234567', 'National ID - Correction',     CURDATE(), '09:00 AM', 'PSA CRS Main Gate 1', 'Quezon City', 'Pending')
ON DUPLICATE KEY UPDATE hash_code = hash_code;
