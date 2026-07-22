<?php
// Secure connection and database path config
$db_file = __DIR__ . '/attendease.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create tables if they do not exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT,
        role TEXT,
        name TEXT,
        zprn TEXT UNIQUE,
        class TEXT,
        department TEXT,
        division TEXT,
        status TEXT DEFAULT 'Active'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT UNIQUE,
        name TEXT,
        hod TEXT,
        established INTEGER,
        intake INTEGER,
        status TEXT DEFAULT 'Active'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS subjects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        class TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS faculty_subjects (
        faculty_id INTEGER,
        subject_id INTEGER,
        PRIMARY KEY (faculty_id, subject_id)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS schedules (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        subject_id INTEGER,
        division TEXT,
        class TEXT,
        day_of_week TEXT,
        start_time TEXT,
        end_time TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER,
        subject_id INTEGER,
        date TEXT,
        status TEXT,
        marked_by INTEGER
    )");

    // Seed departments if empty
    $stmtDept = $pdo->query("SELECT COUNT(*) FROM departments");
    if ($stmtDept->fetchColumn() == 0) {
        $stmtInsDept = $pdo->prepare("INSERT INTO departments (code, name, hod, established, intake) VALUES (?, ?, ?, ?, ?)");
        $stmtInsDept->execute(['CSE', 'Computer Engineering', 'Dr. Sarah Jenkins', 2010, 120]);
        $stmtInsDept->execute(['IT', 'Information Technology', 'Prof. Rajesh Sharma', 2012, 60]);
        $stmtInsDept->execute(['ENTC', 'Electronics & Telecommunication', 'Dr. Amit Patel', 2008, 60]);
    }

    // Seed data if database is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        // Seed Admin
        $stmtAdmin = $pdo->prepare("INSERT INTO users (username, password, role, name) VALUES (?, ?, ?, ?)");
        $stmtAdmin->execute(['Admin@college.edu', 'Admin@123', 'admin', 'Super Admin']);

        // Seed Faculty
        $stmtFaculty = $pdo->prepare("INSERT INTO users (username, password, role, name) VALUES (?, ?, ?, ?)");
        $stmtFaculty->execute(['Faculty@123', 'Faculty@123', 'faculty', 'Megha Mam']);
        $meghaId = $pdo->lastInsertId();

        $stmtFaculty->execute(['faculty2', 'Faculty@123', 'faculty', 'Second Faculty']);
        $faculty2Id = $pdo->lastInsertId();

        // Seed subjects (First Year)
        $stmtSubject = $pdo->prepare("INSERT INTO subjects (name, class) VALUES (?, ?)");
        $stmtSubject->execute(['Probability and statistics', 'First Year']);
        $probStatsId = $pdo->lastInsertId();

        // Assign subject to Megha Mam
        $stmtAlloc = $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)");
        $stmtAlloc->execute([$meghaId, $probStatsId]);

        // Seed Students - Division A (First Year, AI&ML)
        $stmtStudent = $pdo->prepare("INSERT INTO users (username, password, role, name, zprn, class, department, division) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtStudent->execute(['125UAM1209', '125UAM1209', 'student', 'Tanay shelar', '125UAM1209', 'First Year', 'AI&ML', 'A']);
        $stmtStudent->execute(['125UAM1192', '125UAM1192', 'student', 'Aryan jedhe', '125UAM1192', 'First Year', 'AI&ML', 'A']);
        $stmtStudent->execute(['125UAM1169', '125UAM1169', 'student', 'Sarthak Anbhule', '125UAM1169', 'First Year', 'AI&ML', 'A']);

        // Seed Students - Division B (First Year, AI&ML)
        $stmtStudent->execute(['125UAM1134', '125UAM1134', 'student', 'Shivam Maurya', '125UAM1134', 'First Year', 'AI&ML', 'B']);
        $stmtStudent->execute(['125UAM1129', '125UAM1129', 'student', 'Nikhil Gaikwad', '125UAM1129', 'First Year', 'AI&ML', 'B']);

        // Seed schedules for Megha Mam (First Year)
        $stmtSchedule = $pdo->prepare("INSERT INTO schedules (subject_id, division, class, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtSchedule->execute([$probStatsId, 'A', 'First Year', 'Monday', '09:00 AM', '10:00 AM']);
        $stmtSchedule->execute([$probStatsId, 'B', 'First Year', 'Wednesday', '11:00 AM', '12:00 PM']);
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
