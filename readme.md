# 📚 Ngabaca - Collaboration Guide

## 🚀 Quick Start

### 1. Clone Repository

```bash
git clone https://github.com/alfinpratamaa/ngabaca.git
cd ngabaca
```

### 2. Setup Environment

Jalankan script instalasi dengan encryption key:

```bash
chmod +x install.sh
./install.sh YOUR_ENCRYPTION_KEY
```

Script ini akan:

- Decrypt file `.env.encrypted` menjadi `.env`
- Install dependencies Laravel (`composer install`)
- Install dependencies Node.js (`npm install`)
- Generate application key
- Setup database (migration & seed)

### 3. Jalankan Aplikasi

```bash
# Development server Laravel
composer run dev
```

```

## How to Contributing

1. Fork repository ini
2. Create feature branch
3. Commit changes
4. Push ke branch
5. Create Pull Request

## 🌿 Git Workflow

### Branch Naming Convention

```

feature/nama-fitur
bugfix/nama-bug
hotfix/nama-hotfix

````

### Workflow Steps

1. **Create Branch**

    ```bash
    git checkout -b feature/nama-fitur
    ```

2. **Commit Changes**

    ```bash
    git add .
    git commit -m "feat: menambahkan fitur X"
    ```

3. **Push Branch**

    ```bash
    git push origin feature/nama-fitur
    ```

4. **Create Pull Request**
    - Buka GitHub repository
    - Click "New Pull Request"
    - Pilih branch yang akan di-merge
    - Tambahkan deskripsi yang jelas

### Commit Message Format

````

type: deskripsi singkat

type bisa berupa:

- feat: fitur baru
- fix: perbaikan bug
- docs: update dokumentasi
- style: formatting, tidak mengubah logika
- refactor: refactoring code
- test: menambahkan test
- chore: maintenance task

```

## 📁 Project Structure

```

ngabaca/
├── app/ # Laravel application
├── resources/ # Views, assets, lang
├── public/ # Public assets
├── database/ # Migrations, seeds, factories
├── routes/ # Route definitions
├── config/ # Configuration files
├── .env.encrypted # Encrypted environment file
├── install.sh # Setup script
└── README.md # This file

````

## 🛠️ Development Guidelines

### Code Style

- Use ESLint untuk JavaScript
- Gunakan prettier untuk formatting

### Testing

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests (jika ada)
npm run test
````

### Database Migration

```bash
# Create migration
php artisan make:migration create_table_name

# Run migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback
```

## 🔧 Troubleshooting

### Permission Issues

```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Regenerate Autoload

```bash
composer dump-autoload
```

## 📞 Contact

Jika ada masalah atau pertanyaan:

- Create issue di GitHub
- Contact maintainer: @alfinpratamaa

**Happy Coding! 🎉**
