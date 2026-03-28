#!/bin/bash
# LoggyShip FTP Deploy Script for All-Inkl
# Usage: ./deploy.sh
#
# Prerequisites: lftp installed (brew install lftp / apt install lftp)

set -e

# Load credentials from ~/.loggyship/ftp-credentials
# Create this file with: FTP_HOST=, FTP_PORT=, FTP_USER=, FTP_PASS=
if [ -f ~/.loggyship/ftp-credentials ]; then
    source ~/.loggyship/ftp-credentials
else
    echo "❌ Missing ~/.loggyship/ftp-credentials"
    echo "   Create it with FTP_HOST, FTP_PORT, FTP_USER, FTP_PASS variables"
    exit 1
fi

echo "🚀 Deploying LoggyShip to All-Inkl..."

# Build assets first
echo "📦 Building assets..."
npm run build

# Copy production env
echo "⚙️  Preparing production environment..."
cp .env.production .env.deploy

echo "📡 Uploading via FTP..."
lftp -e "
set ftp:ssl-allow yes
set ssl:verify-certificate no
set net:timeout 30
set net:max-retries 4
set net:reconnect-interval-base 2
set mirror:parallel-transfer-count 5

mirror --reverse --delete --verbose \
  --exclude .git/ \
  --exclude .github/ \
  --exclude node_modules/ \
  --exclude screenshots/ \
  --exclude tests/ \
  --exclude .env \
  --exclude .env.production \
  --exclude deploy.sh \
  --exclude storage/logs/ \
  --exclude storage/framework/cache/ \
  --exclude storage/framework/sessions/ \
  --exclude storage/framework/views/ \
  ./ ./

# Upload .env.deploy as .env
put .env.deploy -o .env

# Ensure storage directories exist
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/app
mkdir -p bootstrap/cache

bye
" -u "$FTP_USER,$FTP_PASS" "ftp://$FTP_HOST:$FTP_PORT"

rm -f .env.deploy

echo ""
echo "✅ Deployment complete!"
echo ""
echo "⚠️  Post-deployment steps (run via SSH or All-Inkl WebFTP terminal):"
echo "   1. php artisan migrate --force"
echo "   2. php artisan config:cache"
echo "   3. php artisan route:cache"
echo "   4. php artisan view:cache"
echo "   5. chmod -R 775 storage bootstrap/cache"
echo ""
echo "🌐 Your app should be live at: http://$FTP_HOST"
