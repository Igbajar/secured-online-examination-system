CREATE DATABASE IF NOT EXISTS exam_system;
USE exam_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL
);

CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    duration_minutes INT NOT NULL
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option CHAR(1) NOT NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    exam_id INT NOT NULL,
    score INT NOT NULL,
    total INT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (exam_id) REFERENCES exams(id)
);

-- Insert Default Admin (Password: admin123) and Student (Password: student123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1fWbI91fK2A4qA/1vC1.q7UuL5wS/KG', 'admin'),
('student1', '$2y$10$wT8m9M2sZ5pB8vV2mX0E.O1/6V3pQ5Z2Y1X0E1V2M3P4Q5R6S7T8U', 'student');

-- Sample Exam & Questions
INSERT INTO exams (title, duration_minutes) VALUES ('PHP & Security Basics', 5);
INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES
(1, 'Which PHP superglobal is used to collect form data after submitting an HTML form with method="post"?', '$_GET', '$_POST', '$_REQUEST', '$_SERVER', 'B'),
(1, 'How do you prevent SQL Injection in PHP?', 'Use htmlspecialchars()', 'Use Prepared Statements', 'Use md5()', 'Use addslashes()', 'B');