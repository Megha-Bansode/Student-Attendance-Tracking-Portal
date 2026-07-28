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

    $pdo->exec("CREATE TABLE IF NOT EXISTS condonations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER,
        type TEXT,
        date TEXT,
        reason TEXT,
        document TEXT,
        status TEXT DEFAULT 'Pending',
        submitted_at TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        role TEXT,
        status TEXT DEFAULT 'Pending',
        created_at TEXT,
        resolved_at TEXT
    )");

    // Specialized Key for Admin Password Reset
    define('ADMIN_RESET_KEY', 'SUPER_SECRET_KEY_123');

    // Seed data if database is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        // Seed initial departments
        $stmtInsDept = $pdo->prepare("INSERT INTO departments (code, name, hod, established, intake) VALUES (?, ?, ?, ?, ?)");
        $stmtInsDept->execute(['CSE', 'Computer Engineering', 'Dr. Sarah Jenkins', 2010, 120]);
        $stmtInsDept->execute(['IT', 'Information Technology', 'Prof. Rajesh Sharma', 2012, 60]);
        $stmtInsDept->execute(['ENTC', 'Electronics & Telecommunication', 'Dr. Amit Patel', 2008, 60]);

        // Seed Admin
        $stmtAdmin = $pdo->prepare("INSERT INTO users (username, password, role, name) VALUES (?, ?, ?, ?)");
        $stmtAdmin->execute(['Admin@college.edu', 'Admin@123', 'admin', 'Super Admin']);

        // Seed Faculty
        $stmtFaculty = $pdo->prepare("INSERT INTO users (username, password, role, name, class, division) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtFaculty->execute(['Faculty@123', 'Faculty@123', 'faculty', 'Megha Mam', 'First Year', 'A']);
        $meghaId = $pdo->lastInsertId();

        $stmtFaculty->execute(['faculty2', 'Faculty@123', 'faculty', 'Second Faculty', 'Second Year', 'A']);
        $faculty2Id = $pdo->lastInsertId();

        // Seed subjects
        $stmtSubject = $pdo->prepare("INSERT INTO subjects (name, class) VALUES (?, ?)");
        $stmtSubject->execute(['Probability and statistics', 'First Year']);
        $probStatsId = $pdo->lastInsertId();
        
        $stmtSubject->execute(['Data Structures', 'Second Year']);
        $dsId = $pdo->lastInsertId();

        // Assign subjects to faculty
        $stmtAlloc = $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)");
        $stmtAlloc->execute([$meghaId, $probStatsId]);
        $stmtAlloc->execute([$faculty2Id, $dsId]);

        // Seed Students based on provided list
        $students = [
            ['zprn' => '125UAM1173', 'name' => 'MARKAD KRUSHNA HARICHANDRA', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 86],
            ['zprn' => '125UAM1134', 'name' => 'MAURYA SHIVAM BRAJESH', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 72],
            ['zprn' => '125UAM1123', 'name' => 'NAGARE SARTHAK PARMESHWAR', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 60],
            ['zprn' => '125UAM1035', 'name' => 'NAGTILAK PRATIK SANTOSH', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 90],
            ['zprn' => '125UAM1108', 'name' => 'NANDVATE AMEY JAIDEV', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 80],
            ['zprn' => '125UAM1129', 'name' => 'NIKHIL SHASHIKANT GAIKWAD', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 70],
            ['zprn' => '125UAM1058', 'name' => 'NIVANGUNE JAYJEET RAMDAS', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 83],
            ['zprn' => '125UAM1127', 'name' => 'OM RAHUL PATIL', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 90],
            ['zprn' => '125UAM1085', 'name' => 'PALKAR SURAJ SANJAY', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 76],
            ['zprn' => '125UAM1064', 'name' => 'PALLAVI NARESH NAGPURE', 'dept' => 'Ai & ML', 'class' => 'First Year', 'att' => 85]
        ];

        $stmtStudent = $pdo->prepare("INSERT INTO users (username, password, role, name, zprn, class, department, division) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $student_ids = [];
        
        foreach ($students as $s) {
            $username = $s['zprn'];
            $stmtStudent->execute([$username, $username, 'student', $s['name'], $s['zprn'], $s['class'], $s['dept'], 'A']);
            $id = $pdo->lastInsertId();
            
            // Create exactly 100 attendance records with the desired distribution
            $statuses = array_merge(
                array_fill(0, $s['att'], 'Present'),
                array_fill(0, 100 - $s['att'], 'Absent')
            );
            shuffle($statuses);
            
            $student_ids[] = [
                'id' => $id,
                'class' => $s['class'],
                'statuses' => $statuses
            ];
        }

        // Generate Attendance exactly matching % (out of 100 lectures)
        $stmtAttendance = $pdo->prepare("INSERT INTO attendance (student_id, subject_id, date, status, marked_by) VALUES (?, ?, ?, ?, ?)");
        
        $total_lectures = 100;
        // Generate 100 valid dates (skip Sundays)
        $dates = [];
        $days_back = 0;
        while (count($dates) < $total_lectures) {
            $date = date('Y-m-d', strtotime("-$days_back days"));
            if (date('N', strtotime($date)) != 7) {
                $dates[] = $date;
            }
            $days_back++;
        }
        $dates = array_reverse($dates); // chronological order
        
        for ($i = 0; $i < $total_lectures; $i++) {
            $date = $dates[$i];
            foreach ($student_ids as $stu) {
                $sub_id = $probStatsId;
                $fac_id = $meghaId;
                $status = $stu['statuses'][$i];
                $stmtAttendance->execute([$stu['id'], $sub_id, $date, $status, $fac_id]);
            }
        }

        // Seed schedules
        $stmtSchedule = $pdo->prepare("INSERT INTO schedules (subject_id, division, class, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            $stmtSchedule->execute([$probStatsId, 'A', 'First Year', $day, '09:00 AM', '10:00 AM']);
            $stmtSchedule->execute([$dsId, 'A', 'Second Year', $day, '10:15 AM', '11:15 AM']);
        }

        // Seed condonations for each student
        $stmtInsCond = $pdo->prepare("INSERT INTO condonations (student_id, type, date, reason, document, status, submitted_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($student_ids as $stu) {
            $student_id = $stu['id'];
            $stmtInsCond->execute([$student_id, 'Medical', date('Y-m-d', strtotime('-5 days')), 'Fever recovery leave.', 'medical_certificate.pdf', 'Approved', date('Y-m-d H:i:s', strtotime('-6 days'))]);
            $stmtInsCond->execute([$student_id, 'Sports', date('Y-m-d', strtotime('-12 days')), 'Represented college in tournament.', 'sports_invitation.pdf', 'Pending', date('Y-m-d H:i:s', strtotime('-14 days'))]);
            $stmtInsCond->execute([$student_id, 'Duty', date('Y-m-d', strtotime('-2 days')), 'Attended seminar.', 'seminar_certificate.pdf', 'Pending', date('Y-m-d H:i:s', strtotime('-3 days'))]);
        }
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
