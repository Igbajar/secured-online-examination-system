# Secured Online Examination System

A web-based examination management and assessment system developed using PHP, MySQL, HTML, CSS, and JavaScript. The system provides separate administrative and student workflows for creating examinations, managing questions, conducting timed assessments, automatically evaluating submissions, and recording examination results.

## Project Overview

The Secured Online Examination System was developed as an academic software engineering project to provide a structured platform for conducting computer-based examinations.

The system implements role-based access for administrators and students and combines server-side processing with client-side examination controls.

## Objectives

The primary objectives of the system are to:

* Provide a web-based platform for conducting examinations.
* Authenticate users before granting access to examination resources.
* Separate administrator and student functions.
* Allow administrators to create examinations.
* Allow administrators to create multiple-choice questions.
* Allow students to take available examinations.
* Enforce examination time limits.
* Automatically evaluate submitted answers.
* Record examination results in a MySQL database.
* Provide administrators with access to student examination results.

## Key Features

### Administrator

Administrators can:

* Authenticate through the administrator login.
* Create examinations.
* Specify examination duration.
* Add questions to examinations.
* Define four answer options for each question.
* Specify the correct answer.
* View available examinations.
* View submitted student results.
* View student scores and percentages.

### Student

Students can:

* Authenticate through the login system.
* Access the student dashboard.
* View available examinations.
* View examination duration.
* Start an examination.
* Answer multiple-choice questions.
* Submit an examination.
* Receive an examination score.
* Return to the student dashboard.
* Log out securely.

## Security Features

The project incorporates several security-oriented mechanisms.

### Password Verification

User passwords are verified using PHP's password verification functionality rather than comparing plaintext passwords directly.

### Session-Based Authentication

The application uses PHP sessions to maintain authenticated user state.

### Role-Based Access Control

Student and administrator resources are protected according to the authenticated user's role.

### Session Fixation Protection

The login process regenerates the session identifier after successful authentication to reduce the risk of session fixation.

### Prepared SQL Statements

Database operations involving user-supplied values use PDO prepared statements to reduce SQL injection risks.

### Output Escaping

User-controlled values displayed in HTML are escaped using `htmlspecialchars()`.

### Examination Timer

Each examination has a defined duration and includes a client-side countdown timer.

### Tab-Switch Detection

The examination interface detects browser visibility changes and warns the student when the examination tab is switched away from.

### Automatic Submission

The examination can be automatically submitted when the configured examination time expires or when the maximum tab-switch warning threshold is reached.

### Session Termination

The logout process clears session data, removes the session cookie where applicable, and destroys the PHP session.

## System Workflow

```text
                    ┌─────────────────────┐
                    │       Login         │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │ Authenticate User   │
                    └──────────┬──────────┘
                               │
                    ┌──────────┴──────────┐
                    │                     │
                    ▼                     ▼
             ┌──────────────┐      ┌──────────────┐
             │    Admin     │      │    Student   │
             └──────┬───────┘      └──────┬───────┘
                    │                     │
                    ▼                     ▼
             Create Exams          View Available Exams
                    │                     │
                    ▼                     ▼
             Add Questions           Start Examination
                    │                     │
                    │                     ▼
                    │                Answer Questions
                    │                     │
                    │                     ▼
                    │                Submit Exam
                    │                     │
                    │                     ▼
                    │                 Calculate Score
                    │                     │
                    └──────────────┬──────┘
                                   ▼
                            Store Examination
                                Results
```

## Database Architecture

The system uses MySQL as its database management system.

The database contains four primary entities:

```text
users
  │
  ├───────────────┐
  │               │
  ▼               ▼
results          exams
                  │
                  ▼
              questions
```

### Main Tables

#### `users`

Stores authenticated users and their roles.

Typical roles include:

* `admin`
* `student`

#### `exams`

Stores examination information such as:

* Examination title
* Examination duration

#### `questions`

Stores examination questions and their multiple-choice options, together with the correct option.

#### `results`

Stores examination results including:

* Student
* Examination
* Score
* Total number of questions
* Submission timestamp

## Technology Stack

| Technology | Purpose                                       |
| ---------- | --------------------------------------------- |
| PHP        | Server-side application logic                 |
| MySQL      | Relational database                           |
| PDO        | Database connectivity and prepared statements |
| HTML5      | Application structure                         |
| CSS3       | Interface styling                             |
| JavaScript | Timer and examination controls                |
| Apache     | Local web server                              |
| XAMPP      | Local development environment                 |

## Project Structure

```text
secured-online-examination-system/
│
├── database/
│   └── schema.sql
│
├── admin.php
├── dashboard.php
├── db.php
├── login.php
├── logout.php
├── submit_exam.php
├── take_exam.php
├── .gitignore
└── README.md
```

## Installation

### Requirements

Before running the application, install:

* XAMPP
* Apache
* MySQL
* A modern web browser

### 1. Install XAMPP

Install XAMPP on the development computer and start:

* Apache
* MySQL

### 2. Copy the Project

Copy the project directory into the XAMPP web root:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\secured-online-examination-system\
```

### 3. Create the Database

Open:

```text
http://localhost/phpmyadmin
```

Create the required database and import:

```text
database/schema.sql
```

The database schema defines the tables required by the application.

### 4. Configure Database Connection

Open the database connection configuration used by the application and ensure that the database host, database name, username, and password correspond to the local XAMPP/MySQL environment.

### 5. Start the Application

Open:

```text
http://localhost/secured-online-examination-system/
```

The login page should be displayed.

## Examination Workflow

### Administrator Workflow

```text
Login
  ↓
Administrator Dashboard
  ↓
Create Examination
  ↓
Set Examination Duration
  ↓
Add Questions
  ↓
Define Answer Options
  ↓
Define Correct Answer
  ↓
Monitor Examination Results
```

### Student Workflow

```text
Login
  ↓
Student Dashboard
  ↓
Select Examination
  ↓
Read Questions
  ↓
Answer Questions
  ↓
Complete Examination
  ↓
Submit
  ↓
Automatic Evaluation
  ↓
View Score
```

## Examination Security Controls

The examination interface includes several controls intended to reduce opportunities for inappropriate examination behavior.

These include:

* Browser tab-switch detection.
* Tab-switch warnings.
* Automatic submission after repeated warnings.
* Countdown timer.
* Automatic submission when time expires.
* Server-side answer evaluation.
* Role-based access control.

## Limitations

This project represents an academic software engineering implementation and should not automatically be considered production-ready for high-stakes examinations.

A production deployment would require additional security controls, testing, monitoring, infrastructure hardening, and operational safeguards.

Potential areas for further development include:

* CSRF protection.
* Stronger server-side examination timing enforcement.
* More comprehensive input validation.
* Rate limiting.
* Account lockout and brute-force protection.
* Audit logging.
* HTTPS enforcement.
* Secure production secret management.
* Enhanced administrator controls.
* Improved examination analytics.
* Randomized examination questions.
* Question banks and examination pools.
* More comprehensive anti-cheating mechanisms.
* Automated security testing.

## Future Enhancements

Potential future versions may include:

* Question randomization.
* Question banks.
* Examination scheduling.
* Email notifications.
* Advanced result analytics.
* PDF result generation.
* Student performance dashboards.
* Examination reports.
* Multi-factor authentication.
* Comprehensive audit trails.
* API integration.
* Improved responsive interface.
* Deployment using a production-grade hosting environment.

## Academic Background

This project forms part of the author's academic and software engineering work and is related to research and development activities involving secure digital systems and web-based information systems.

The author has also published research relating to the design of a secured online course examination system for institutions and colleges.

## Author

**Engr. Igbajar Abraham**

Computer Engineer | Cybersecurity | Software Engineering | ICT Research & Development

## License

This repository is provided for educational, academic, research, and portfolio purposes.

Before using the system commercially or deploying it in a production examination environment, conduct appropriate security assessment, testing, and authorization.

## Disclaimer

This project is provided as a demonstration and academic portfolio project.

It should undergo appropriate security testing, code review, penetration testing, infrastructure hardening, and operational validation before being used for real-world high-stakes examinations.
