-- ============================================================
-- Student Result Viewer System - Database Schema
-- ============================================================
-- HOW TO USE:
-- 1. Open phpMyAdmin (or MySQL command line)
-- 2. Create a new database (this script does it for you too)
-- 3. Import / run this whole file
-- ============================================================

CREATE DATABASE IF NOT EXISTS student_result_system;
USE student_result_system;

-- ------------------------------------------------------------
-- Table: students
-- Stores basic student details + login credentials
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    password VARCHAR(255) NULL,        -- optional, student may login with DOB only
    class VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Table: admin
-- Stores admin login credentials
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Default admin login -> username: admin , password: password
-- (password below is a bcrypt hash of "admin123")
INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username = username;

-- ------------------------------------------------------------
-- Table: results
-- One row = one exam result for one student (holds the summary)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    exam_name VARCHAR(100) NOT NULL,
    total_max_marks INT NOT NULL DEFAULT 0,
    total_obtained_marks INT NOT NULL DEFAULT 0,
    percentage DECIMAL(5,2) NOT NULL DEFAULT 0,
    grade VARCHAR(5) NOT NULL DEFAULT '',
    status VARCHAR(10) NOT NULL DEFAULT '',   -- Pass / Fail
    result_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Table: subject_marks
-- Subject wise marks that belong to one result (exam)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS subject_marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    result_id INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    max_marks INT NOT NULL DEFAULT 100,
    marks_obtained INT NOT NULL DEFAULT 0,
    FOREIGN KEY (result_id) REFERENCES results(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Sample data (optional - safe to delete)
-- ------------------------------------------------------------
INSERT INTO students (roll_no, name, dob, password, class, email, phone) VALUES
('101', 'Aarav Sharma', '2006-05-14', NULL, '10th A', 'aarav@example.com', '9999900001'),
('102', 'Priya Verma', '2006-08-22', NULL, '10th A', 'priya@example.com', '9999900002')
ON DUPLICATE KEY UPDATE roll_no = roll_no;

INSERT INTO results (student_id, exam_name, total_max_marks, total_obtained_marks, percentage, grade, status, result_date)
VALUES (1, 'Half Yearly Exam', 500, 410, 82.00, 'A', 'Pass', '2026-06-10');

INSERT INTO subject_marks (result_id, subject_name, max_marks, marks_obtained) VALUES
(1, 'English', 100, 85),
(1, 'Mathematics', 100, 90),
(1, 'Science', 100, 78),
(1, 'Social Science', 100, 80),
(1, 'Computer', 100, 77);
