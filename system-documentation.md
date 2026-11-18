# AttendEase - System Architecture & Bootstrap Documentation

## 1. Project Overview
AttendEase is an autonomous, comprehensive Student Attendance Tracking Portal engineered using native PHP, MySQL, and modern front-end technologies. The platform is designed to handle multiple roles (Super Admin, Faculty, and Students) to streamline attendance management, analytics, and leave approvals (condonations).

## 2. Core Architecture
The system follows a modular architectural pattern. 

### Technology Stack
- **Backend:** PHP 8+ (Procedural / Data Access Objects)
- **Database:** MySQL via PDO (PHP Data Objects) for secure parameterized queries.
- **Frontend Framework:** Custom UI utilizing Bootstrap 5 grid systems, supplemented by heavy custom CSS for glassmorphism, dark/light themes, and premium UI components.
- **JavaScript:** Vanilla JS for DOM manipulation, DOM-to-PDF (`html2pdf.js`) for report generation, and SweetAlert2 for non-intrusive notifications.

### Directory Structure
- `/assets`: Contains all static resources (`css/`, `js/`, `images/`, `documents/`).
- `/config`: Houses `database.php` which establishes the singleton PDO database connection.
- `/includes`: Reusable UI components (`header.php`, `footer.php`, sidebars, topbars).
- `/modules`: Core business logic segmented by domain (e.g., `/attendance`, `/dashboard`, `/departments`, `/subjects`, `/semesters`).
- `/reports`: Dedicated scripts and UI for generating analytics and compliance exports.
- `/users`: Management interfaces for Faculty and Students.

## 3. Database Bootstrap & Schema
The database uses a relational schema. The `users` table handles polymorphic roles (`admin`, `faculty`, `student`).

**Key Entities:**
- `users`: Core identity table with role-based columns.
- `departments`: Academic departments.
- `subjects`: Curriculum mapping to classes and divisions.
- `faculty_subjects`: Many-to-many pivot mapping faculty to subjects.
- `attendance`: Transactional table logging daily presence/absence per student/subject/date.
- `condonations`: Request tracker for leaves and medical absences.

## 4. Role-Based Access Control (RBAC)
Security is enforced at the top of every protected PHP script by checking the `$_SESSION['role']`.
1. **Super Admin**: Full visibility. Can configure departments, semesters, allocate subjects, and approve condonations.
2. **Faculty**: Scoped visibility. Can mark attendance for allocated subjects, edit within 48 hours, and view daily subject-wise reports.
3. **Student**: Scoped to self. Can view personal attendance history, submit condonation requests, and download their aggregate reports.

## 5. UI/UX Design System
The interface is designed with a premium aesthetic emphasizing:
- **Dynamic Theming:** Deep integration of a CSS variable-based Light/Dark mode toggle.
- **Glassmorphism:** Navigation menus and modals utilize semi-transparent backgrounds with backdrop-filters.
- **Micro-interactions:** Interactive hover states on stat cards and smooth CSS transitions.

## 6. How Everything Works Together
1. **Authentication:** Users log in via `/modules/authentication/login.php`. PDO validates credentials and establishes a Session.
2. **Dashboard Routing:** Based on the user's role, they are redirected to their respective dashboard (`admin-dashboard.php`, etc.).
3. **Data Flow:** When a faculty marks attendance, the data is `POST`ed to `faculty-mark-attendance.php`, validated against the `attendance` table, and inserted/updated in bulk. 
4. **Reporting Engine:** The reporting pages query the database, aggregate the statistics, and display them in DOM tables. The DOM is then scraped by `html2pdf.js` or custom CSV logic to generate downloadable artifacts directly on the client side without needing heavy server-side PDF libraries.
