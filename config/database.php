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

    // Seed condonations if empty
    $stmtCondonations = $pdo->query("SELECT COUNT(*) FROM condonations");
    if ($stmtCondonations->fetchColumn() == 0) {
        $stmtInsCond = $pdo->prepare("INSERT INTO condonations (student_id, type, date, reason, document, status, submitted_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // Seed for student Shivam Maurya (id 12)
        $stmtInsCond->execute([12, 'Medical', '2026-07-10', 'Fever recovery leave.', 'medical_certificate.pdf', 'Approved', '2026-07-11 09:00:00']);
        $stmtInsCond->execute([12, 'Sports', '2026-07-18', 'Represented college in Inter-College Cricket tournament.', 'sports_invitation.pdf', 'Pending', '2026-07-19 14:30:00']);
        $stmtInsCond->execute([12, 'Duty', '2026-07-15', 'Attended AI & ML national seminar.', 'seminar_certificate.pdf', 'Pending', '2026-07-16 10:15:00']);
    }



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

        // Generate Random Students
        $first_names = ['Aarav', 'Vihaan', 'Aditya', 'Arjun', 'Sai', 'Rohan', 'Krishna', 'Ishaan', 'Shaurya', 'Atharv', 'Ananya', 'Diya', 'Avni', 'Kavya', 'Isha', 'Riya', 'Aisha', 'Zara', 'Neha', 'Pooja', 'Rahul', 'Amit', 'Vikram', 'Raj', 'Sanjay'];
        $last_names = ['Sharma', 'Verma', 'Gupta', 'Kumar', 'Singh', 'Patel', 'Joshi', 'Mishra', 'Reddy', 'Rao', 'Das', 'Roy', 'Nair', 'Pillai', 'Menon', 'Bose', 'Sengupta', 'Chatterjee', 'Iyer', 'Murthy'];
        
        $stmtStudent = $pdo->prepare("INSERT INTO users (username, password, role, name, zprn, class, department, division) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $classes = ['First Year' => 'A', 'Second Year' => 'A'];
        $student_ids = [];
        $zprn_counter = 1000;
        
        foreach ($classes as $class_name => $division) {
            for ($i = 0; $i < 30; $i++) { // 30 students per class
                $fn = $first_names[array_rand($first_names)];
                $ln = $last_names[array_rand($last_names)];
                $name = $fn . ' ' . $ln;
                $zprn = '125UAM' . $zprn_counter++;
                $username = $zprn;
                
                // Exclude Aarav Mehta
                if (strtolower($name) === 'aarav mehta' || strtolower($name) === 'arav mehta') {
                    $name = 'Tanay Shelar';
                }
                
                $stmtStudent->execute([$username, $username, 'student', $name, $zprn, $class_name, 'CSE', $division]);
                $student_ids[] = ['id' => $pdo->lastInsertId(), 'class' => $class_name, 'division' => $division];
            }
        }

        // Generate Random Attendance for the last 7 days
        $stmtAttendance = $pdo->prepare("INSERT INTO attendance (student_id, subject_id, date, status, marked_by) VALUES (?, ?, ?, ?, ?)");
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            // Skip Sundays
            if (date('N', strtotime($date)) == 7) continue;

            foreach ($student_ids as $stu) {
                // Determine subject and faculty based on class
                if ($stu['class'] == 'First Year') {
                    $sub_id = $probStatsId;
                    $fac_id = $meghaId;
                } else {
                    $sub_id = $dsId;
                    $fac_id = $faculty2Id;
                }
                
                // 90% chance of being present
                $status = (rand(1, 100) <= 90) ? 'Present' : 'Absent';
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
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
