#!/bin/bash

ENV_FILE=".env"
ENCRYPTED_FILE=".env.encrypted"
ENV_MODE=""
KEY=""
ACTION=""

# Fungsi menampilkan bantuan
function show_help() {
    echo "Usage:"
    echo "  Encrypt: ./env.sh encrypt your_secret_key"
    echo "  Decrypt & Set Env:"
    echo "    ./env.sh --local your_secret_key"
    echo "    ./env.sh --staging your_secret_key"
    echo "    ./env.sh --production your_secret_key"
    echo
    echo "Flags:"
    echo "  -L, --local       Set APP_ENV=local, DB_HOST=127.0.0.1"
    echo "  -S, --staging     Set APP_ENV=staging, DB_HOST=AWS RDS"
    echo "  -P, --production  Set APP_ENV=production, DB_HOST=AWS RDS"
    exit 1
}

# Fungsi enkripsi
function encrypt_env() {
    if [ ! -f "$ENV_FILE" ]; then
        echo "❌ File $ENV_FILE tidak ditemukan."
        exit 1
    fi

    openssl enc -aes-256-cbc -salt -in "$ENV_FILE" -out "$ENCRYPTED_FILE" -pass pass:"$KEY"
    echo "✅ Enkripsi selesai. Hasil disimpan di $ENCRYPTED_FILE"
}

# Fungsi dekripsi dan pengubahan env
function decrypt_and_set_env() {
    if [ ! -f "$ENCRYPTED_FILE" ]; then
        echo "❌ File $ENCRYPTED_FILE tidak ditemukan."
        exit 1
    fi

    openssl enc -aes-256-cbc -d -in "$ENCRYPTED_FILE" -out "$ENV_FILE" -pass pass:"$KEY"
    echo "✅ Dekripsi berhasil ke $ENV_FILE"

    case $ENV_MODE in
        local)
            sed -i 's|^APP_ENV=.*|APP_ENV=local|' "$ENV_FILE"
            sed -i 's|http[s]\?://[^"]*|http://localhost:8000|g' "$ENV_FILE"
            sed -i 's|^DB_HOST=.*|DB_HOST=127.0.0.1|' "$ENV_FILE"
            sed -i 's|^DB_USERNAME=.*|DB_USERNAME=postgres|' "$ENV_FILE"
            sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=postgres|' "$ENV_FILE"
            ;;
        staging|production)
            sed -i "s|^APP_ENV=.*|APP_ENV=$ENV_MODE|" "$ENV_FILE"
            if [ "$ENV_MODE" = "staging" ]; then
                sed -i 's|http[s]\?://[^"]*|https://staging.ngabaca.me|g' "$ENV_FILE"
            else
                sed -i 's|http[s]\?://[^"]*|https://ngabaca.me|g' "$ENV_FILE"
            fi
            sed -i 's|^DB_HOST=.*|DB_HOST=ngabaca-db.cr0k6muq4p8c.ap-southeast-2.rds.amazonaws.com|' "$ENV_FILE"
            sed -i 's|^DB_USERNAME=.*|DB_USERNAME=postgres|' "$ENV_FILE"
            sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=ngabaca-postgres|' "$ENV_FILE"
            ;;
        *)
            echo "❌ Invalid environment: $ENV_MODE"
            exit 1
            ;;
    esac

    echo "✅ APP_ENV, APP_URL, dan DB config berhasil di-set ke mode '$ENV_MODE'"
}

# Parse argumen
if [ $# -lt 2 ]; then
    show_help
fi

case $1 in
    encrypt)
        ACTION="encrypt"
        KEY="$2"
        ;;
    --local|-L)
        ACTION="decrypt"
        ENV_MODE="local"
        KEY="$2"
        ;;
    --staging|-S)
        ACTION="decrypt"
        ENV_MODE="staging"
        KEY="$2"
        ;;
    --production|-P)
        ACTION="decrypt"
        ENV_MODE="production"
        KEY="$2"
        ;;
    *)
        show_help
        ;;
esac

# Jalankan tindakan sesuai pilihan
if [ "$ACTION" = "encrypt" ]; then
    encrypt_env
elif [ "$ACTION" = "decrypt" ]; then
    decrypt_and_set_env
else
    show_help
fi
