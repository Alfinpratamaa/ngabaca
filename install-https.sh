#!/bin/bash

echo "🔐 Installing Certbot & enabling HTTPS..."

sudo apt update
sudo apt install -y certbot python3-certbot-nginx

if [ -z "$APP_DOMAIN" ]; then
    echo "❌ APP_DOMAIN is not set. Please set it in your environment variables."
    exit 1
fi

if [ -z "$CONTACT_EMAIL" ]; then
    echo "❌ CONTACT_EMAIL is not set. Please set it in your environment variables."
    exit 1
fi

sudo certbot --nginx --non-interactive --agree-tos --redirect \
  --email "$CONTACT_EMAIL" \
  -d "$APP_DOMAIN" -d www."$APP_DOMAIN"

echo "✅ HTTPS has been enabled on ngabaca.me"
