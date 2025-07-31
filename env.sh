#!/bin/bash

ENV_FILE=".env"
ENCRYPTED_FILE=".env.encrypted"
ENV_MODE=""
KEY=""
ACTION=""
DB_HOST=""
DB_USERNAME=""
DB_PASSWORD=""
DB_PORT="25060"

function show_help() {
    echo "Usage:"
    echo "  Encrypt: ./env.sh encrypt your_secret_key"
    echo "  Decrypt & Set Env:"
    echo "    ./env.sh --local key"
    echo "    ./env.sh --production key db_host db_user db_pass"
    exit 1
}

function encrypt_env() {
    if [ ! -f "$ENV_FILE" ]; then
        echo "❌ File $ENV_FILE tidak ditemukan."
        exit 1
    fi

    openssl enc -aes-256-cbc -salt -in "$ENV_FILE" -out "$ENCRYPTED_FILE" -pass pass:"$KEY"
    echo "✅ Enkripsi selesai. Hasil disimpan di $ENCRYPTED_FILE"
}

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
            sed -i 's|^APP_URL=.*|APP_URL=http://localhost:8000|' "$ENV_FILE"
            sed -i 's|^DB_HOST=.*|DB_HOST=127.0.0.1|' "$ENV_FILE"
            sed -i 's|^DB_USERNAME=.*|DB_USERNAME=postgres|' "$ENV_FILE"
            sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=postgres|' "$ENV_FILE"
            ;;
        staging|production)
            sed -i "s|^APP_ENV=.*|APP_ENV=$ENV_MODE|" "$ENV_FILE"
            if [ "$ENV_MODE" = "staging" ]; then
                sed -i 's|^APP_URL=.*|APP_URL=https://staging.ngabaca.me|' "$ENV_FILE"
            else
                sed -i 's|^APP_URL=.*|APP_URL=https://ngabaca.me|' "$ENV_FILE"
            fi

            sed -i "s|^DB_HOST=.*|DB_HOST=$DB_HOST|" "$ENV_FILE"
            sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" "$ENV_FILE"
            sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" "$ENV_FILE"
            sed -i "s|^DB_PORT=.*|DB_PORT=$DB_PORT|" "$ENV_FILE"

            APP_URL=$(grep '^APP_URL=' "$ENV_FILE" | cut -d '=' -f2 | tr -d '"')
            if [ -z "$APP_URL" ]; then
                echo "❌ APP_URL tidak ditemukan, GOOGLE_REDIRECT_URI tidak bisa di-set"
            else
                sed -i "s|^GOOGLE_REDIRECT_URI=.*|GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback|" "$ENV_FILE"
            fi
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
