#!/usr/bin/env bash
# ==============================================================================
# AttendEase Cloudflare Tunnel Quick Launcher
# ==============================================================================
set -e

PORT=${PORT:-8000}

echo "=========================================================="
echo "  AttendEase — Cloudflare Quick Tunnel Launcher"
echo "=========================================================="

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "❌ Error: PHP is not installed or not in PATH."
    exit 1
fi

# Check if cloudflared is installed
if ! command -v cloudflared &> /dev/null; then
    echo "⚠️  'cloudflared' CLI tool is not installed."
    echo ""
    echo "To install cloudflared on macOS:"
    echo "   brew install cloudflared"
    echo ""
    echo "To install on Linux:"
    echo "   curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb"
    echo "   sudo dpkg -i cloudflared.deb"
    echo ""
    echo "Alternatively, use Docker Compose with your Cloudflare Tunnel token:"
    echo "   docker compose up -d"
    echo ""
    exit 1
fi

echo "🚀 Starting PHP built-in server on http://localhost:$PORT..."
php -S 0.0.0.0:$PORT > /dev/null 2>&1 &
PHP_PID=$!

# Trap cleanup to stop PHP on exit
cleanup() {
    echo ""
    echo "🛑 Shutting down PHP server (PID: $PHP_PID)..."
    kill $PHP_PID 2>/dev/null || true
    echo "Done."
}
trap cleanup EXIT INT TERM

sleep 1

echo "🌐 Connecting to Cloudflare's Edge Network via Quick Tunnel..."
echo "----------------------------------------------------------"
echo "Look below for your temporary https://*.trycloudflare.com URL"
echo "Press Ctrl+C to stop the tunnel and server."
echo "----------------------------------------------------------"

cloudflared tunnel --url http://localhost:$PORT
