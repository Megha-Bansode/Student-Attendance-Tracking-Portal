# AttendEase — Student Attendance Tracking Portal

AttendEase is a comprehensive, role-based Student Attendance Tracking Portal built with PHP, SQLite/PDO, Bootstrap 5, and vanilla JavaScript. The platform digitizes attendance recording, percentage analytics, monthly reports, and leave (condonation) approval workflows.

---

## 🚀 Cloudflare Deployment

AttendEase is configured to run behind **Cloudflare** for maximum security, global CDN caching, and automated SSL/TLS encryption.

Because AttendEase is a full-stack PHP & SQLite application, it uses **Cloudflare Tunnel (`cloudflared`)** or **Container Hosting with Cloudflare Proxy**.

👉 **[Read the Full Cloudflare Deployment Guide (CLOUDFLARE_DEPLOYMENT.md)](./CLOUDFLARE_DEPLOYMENT.md)**

### Quick Start with Cloudflare Tunnel (Docker Compose)
1. Copy `.env.example` to `.env` and set your `CLOUDFLARE_TUNNEL_TOKEN`.
2. Start the application:
   ```bash
   docker compose up -d
   ```

### Quick Test Tunnel (30 seconds, no account needed)
If you have `cloudflared` installed locally:
```bash
./start-cloudflare-tunnel.sh
```

---

## 🛠 Features

- **Role-Based Access Control (RBAC):** Distinct dashboards for Super Admin, Faculty, and Students.
- **Attendance Management:** Daily marking, 48-hour editing window, and instant percentage tracking.
- **Condonation Requests:** Medical/event leave workflow with PDF proof attachments.
- **Reporting & Analytics:** Class-level, monthly trend, and student summary reports with client-side PDF downloads (`html2pdf.js`).
- **Security:** Pre-configured `.htaccess` file preventing unauthorized access to SQLite database files, logs, and sensitive configurations.

---

## 💻 Local Development Setup

1. **Requirements:** PHP 8.1+ with `pdo_sqlite` and `sqlite3` extensions enabled.
2. **Start Development Server:**
   ```bash
   php -S localhost:8000
   ```
3. Open your browser and navigate to `http://localhost:8000`. The SQLite database and initial seed data will initialize automatically.

---

## 📄 License
This project is open-source and available under standard academic and development terms.
