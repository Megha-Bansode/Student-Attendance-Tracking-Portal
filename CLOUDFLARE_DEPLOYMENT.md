# Deploying AttendEase with Cloudflare

This guide explains how to deploy **AttendEase** (PHP & SQLite) to production using Cloudflare's edge network, security, and global CDN.

---

## ⚠️ Important Note on Cloudflare Pages vs. Cloudflare Tunnel

* **Cloudflare Pages** is designed for **static assets (HTML/CSS/JS)** and serverless JavaScript/Wasm functions. It does **not** have a native Apache/Nginx + PHP runtime, nor a persistent local filesystem for SQLite. If you deploy raw `.php` files to Cloudflare Pages directly, the PHP scripts will not execute.
* **The Official Solution for PHP on Cloudflare:** **Cloudflare Tunnel (`cloudflared`)** or **Cloud-Hosted Container (Render/Fly.io/VPS) with Cloudflare Proxy**.
  - **Cloudflare Tunnel** securely connects your PHP/SQLite application running in a container or on a server to Cloudflare's edge network **without opening any ports, configuring firewalls, or requiring a public IP address**.
  - All web traffic is routed through Cloudflare, providing **free automatic SSL/TLS, DDoS protection, Web Application Firewall (WAF), and global CDN caching**.

---

## Option 1: Cloudflare Tunnel with Docker Compose (Recommended)

This is the standard, production-ready setup for hosting AttendEase behind Cloudflare.

### Prerequisites
- [Docker & Docker Compose](https://docs.docker.com/get-docker/)
- A free [Cloudflare Account](https://dash.cloudflare.com/)
- A domain added to Cloudflare (e.g., `yourdomain.com`)

### Step-by-Step Setup

1. **Create a Cloudflare Tunnel**:
   - Log into the [Cloudflare Zero Trust Dashboard](https://one.dash.cloudflare.com/).
   - Go to **Networks** > **Tunnels**.
   - Click **Create a tunnel**.
   - Select **Cloudflared** as the connector type and name your tunnel (e.g. `attendease-tunnel`).
   - Copy the long **Tunnel Token** generated on the setup page.

2. **Configure Public Hostname**:
   - Under the tunnel's **Public Hostname** tab, click **Add a public hostname**.
   - **Subdomain**: e.g., `attendance` (or leave blank for root domain).
   - **Domain**: Select your domain (e.g., `yourdomain.com`).
   - **Service Type**: `HTTP`
   - **URL**: `attendease-app:80` *(uses Docker's internal service network)*.
   - Click **Save hostname**.

3. **Configure Environment Variables**:
   Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
   Open `.env` and paste your tunnel token:
   ```env
   PORT=8000
   CLOUDFLARE_TUNNEL_TOKEN=eyJhIjoi...your_token_here...
   ```

4. **Launch AttendEase**:
   ```bash
   docker compose up -d --build
   ```

5. **Verify**:
   Visit `https://attendance.yourdomain.com` in your browser. Your portal is now live with full Cloudflare edge protection and SSL!

---

## Option 2: Quick Test Tunnel (Instant Public URL in 30 Seconds)

If you just want a temporary public URL without setting up a domain or Cloudflare account:

1. **Install the Cloudflare CLI (`cloudflared`)**:
   - **macOS**: `brew install cloudflared`
   - **Ubuntu/Debian**:
     ```bash
     curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
     sudo dpkg -i cloudflared.deb
     ```
   - **Windows**: `winget install Cloudflare.cloudflared`

2. **Start the Application & Tunnel**:
   Run the included helper script:
   ```bash
   ./start-cloudflare-tunnel.sh
   ```
   Or manually:
   ```bash
   # Terminal 1: Start PHP
   php -S 0.0.0.0:8000

   # Terminal 2: Expose to Cloudflare
   cloudflared tunnel --url http://localhost:8000
   ```
3. Cloudflare will output a free public URL (e.g., `https://random-words.trycloudflare.com`). Anyone can access your portal via this secure HTTPS link.

---

## Option 3: Deploy to Free Cloud Host (Render / Fly.io) + Cloudflare DNS Proxy

If you want 24/7 cloud hosting without running your own server:

1. **Deploy to Render.com / Fly.io / Railway**:
   - Connect this GitHub repository.
   - The included `Dockerfile` will be auto-detected and built.
   - Render/Fly will provide an assigned hostname (e.g. `attendease.onrender.com`).
2. **Route through Cloudflare**:
   - Go to your Cloudflare Dashboard > **DNS** > **Records**.
   - Add a `CNAME` record:
     - **Name**: `attendance`
     - **Target**: `attendease.onrender.com`
     - **Proxy status**: **Proxied** (Orange cloud icon enabled).
   - Under **SSL/TLS**, set encryption mode to **Full** or **Strict**.

---

## Security Features Included in this Repository

- **`.htaccess` Security Protection**: Blocks direct web access to sensitive SQLite databases (`.db`, `.sqlite`), SQL schemas (`.sql`), server logs (`.log`), and environment secrets (`.env`).
- **Security Headers**: Injects `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, and `Referrer-Policy`.
- **Gzip / Deflate Compression**: Reduces bandwidth for faster edge asset delivery via Cloudflare CDN.
- **Docker Volume Persistence**: SQLite database `attendease.db` is saved in a dedicated Docker volume (`attendease_data`) so data persists across container restarts.
