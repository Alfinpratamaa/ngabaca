#!/bin/bash

ENV_FILE=".env"
ENCRYPTED_FILE=".env.encrypted"
ENV_MODE=""
KEY=""
ACTION=""
DB_HOST="${DB_HOST:-localhost}"
DB_USERNAME="${DB_USERNAME:-postgres}"
DB_PASSWORD="${DB_PASSWORD:-postgres}"

# Fungsi bantuan
function show_help() {
    echo "Usage:"
    echo "  Encrypt: ./env.sh encrypt your_secret_key"
    echo "  Decrypt & Set Env:"
    echo "    ./env.sh --local key"
    echo "    ./env.sh --production key db_host db_user db_pass"
    exit 1
}

# Enkripsi
function encrypt_env() {
    if [ ! -f "$ENV_FILE" ]; then
        echo "❌ File $ENV_FILE tidak ditemukan."
        exit 1
    fi

    openssl enc -aes-256-cbc -salt -in "$ENV_FILE" -out "$ENCRYPTED_FILE" -pass pass:"$KEY"
    echo "✅ Enkripsi selesai. Hasil disimpan di $ENCRYPTED_FILE"
}

# Dekripsi dan pengaturan env
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

            sed -i "s|^DB_HOST=.*|DB_HOST=$DB_HOST|" "$ENV_FILE"
            sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" "$ENV_FILE"
            sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" "$ENV_FILE"
            ;;
        *)
            echo "❌ Invalid environment: $ENV_MODE"
            exit 1
            ;;
    esac

    echo "✅ APP_ENV, APP_URL, dan DB config berhasil di-set ke mode '$ENV_MODE'"
}

# Parse argumen
if [ "$1" = "encrypt" ]; then
    ACTION="encrypt"
    KEY="$2"
elif [[ "$1" =~ ^--(local|staging|production)$ ]]; then
    ACTION="decrypt"
    ENV_MODE="${1/--/}"
    KEY="$2"
    DB_HOST="$3"
    DB_USERNAME="$4"
    DB_PASSWORD="$5"
else
    show_help
fi

# Eksekusi aksi
if [ "$ACTION" = "encrypt" ]; then
    encrypt_env
elif [ "$ACTION" = "decrypt" ]; then
    decrypt_and_set_env
else
    show_help
fi
