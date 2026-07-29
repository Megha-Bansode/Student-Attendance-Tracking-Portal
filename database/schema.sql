CREATE TABLE IF NOT EXISTS users (
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
);

CREATE TABLE IF NOT EXISTS departments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT UNIQUE,
    name TEXT,
    hod TEXT,
    established INTEGER,
    intake INTEGER,
    status TEXT DEFAULT 'Active'
);

CREATE TABLE IF NOT EXISTS subjects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    class TEXT
);

CREATE TABLE IF NOT EXISTS faculty_subjects (
    faculty_id INTEGER,
    subject_id INTEGER,
    PRIMARY KEY (faculty_id, subject_id)
);

CREATE TABLE IF NOT EXISTS schedules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_id INTEGER,
    division TEXT,
    class TEXT,
    day_of_week TEXT,
    start_time TEXT,
    end_time TEXT
);

CREATE TABLE IF NOT EXISTS attendance (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER,
    subject_id INTEGER,
    date TEXT,
    status TEXT,
    marked_by INTEGER
);

CREATE TABLE IF NOT EXISTS condonations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER,
    type TEXT,
    date TEXT,
    reason TEXT,
    document TEXT,
    status TEXT DEFAULT 'Pending',
    submitted_at TEXT
);

CREATE TABLE IF NOT EXISTS password_resets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    role TEXT,
    status TEXT DEFAULT 'Pending',
    created_at TEXT,
    resolved_at TEXT
);
