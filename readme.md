# 📚 Ngabaca

A Laravel-based web application for collaborative reading and book management.

## Table of Contents

-   [Quick Start](#-quick-start)
-   [Prerequisites](#prerequisites)
-   [Installation](#installation)
-   [Development](#development)
-   [Contributing](#-contributing)
-   [Project Structure](#-project-structure)
-   [Development Guidelines](#-development-guidelines)
-   [Troubleshooting](#-troubleshooting)
-   [Contact](#-contact)

## 🚀 Quick Start

> **⚠️ IMPORTANT: WSL (Windows Subsystem for Linux) is REQUIRED for this project!**

### For Linux/WSL Users (Recommended)

1. **Clone the repository:**

    ```bash
    git clone https://github.com/alfinpratamaa/ngabaca.git
    cd ngabaca
    ```

2. **Run the installation script:**

    ```bash
    chmod +x install.sh
    ./install.sh <ENCRYPTION_KEY>

    # Example:
    ./install.sh base64:aisdb1231gh20321ojb
    ```

    > The encryption key is available in the group description.

3. **Start the development server:**

    ```bash
    composer run dev
    ```

4. **Access the application:**
    ```
    http://localhost:8000
    ```

### For Windows Users

If you must use Windows (not recommended), follow the detailed installation steps below.

## Prerequisites

### For WSL/Linux Users

-   WSL 2 (Windows Subsystem for Linux)
-   PHP 8.3 or higher
-   Composer
-   Node.js & npm
-   OpenSSL

### For Windows Users

Ensure the following software is installed and accessible from Command Prompt/PowerShell:

#### PHP 8.3 or Higher

-   Download from [windows.php.net/download](https://windows.php.net/download)
-   Add PHP to your system's PATH
-   Verify: `php -v`

#### Composer

-   Download from [getcomposer.org/download](https://getcomposer.org/download/)
-   Verify: `composer -v`

#### Node.js

-   Download from [nodejs.org](https://nodejs.org/en/download)
-   Verify: `node -v` and `npm -v`

#### OpenSSL

-   Download from [slproweb.com](https://slproweb.com/products/Win32OpenSSL.html)
-   Add to PATH
-   Verify: `openssl version`

## Installation

### Automated Installation (Linux/WSL)

The `install.sh` script automatically handles:

-   Decrypting `.env.encrypted` to `.env`
-   Installing Laravel dependencies (`composer install`)
-   Installing Node.js dependencies (`npm install`)
-   Generating application key
-   Setting up database (migration & seed)

### Manual Installation (Windows)

1. **Clone and navigate to project:**

    ```bash
    git clone https://github.com/alfinpratamaa/ngabaca.git
    cd ngabaca
    ```

2. **Set encryption key environment variable:**

    **Command Prompt:**

    ```cmd
    set LARAVEL_ENV_ENCRYPTION_KEY=YOUR_ENCRYPTION_KEY
    ```

    **PowerShell:**

    ```powershell
    $env:LARAVEL_ENV_ENCRYPTION_KEY="YOUR_ENCRYPTION_KEY"
    ```

3. **Install PHP dependencies:**

    ```bash
    composer install --no-interaction --prefer-dist --optimize-autoloader
    ```

4. **Decrypt environment file:**

    ```bash
    # Remove old .env file
    del .env 2>NUL

    # Decrypt .env.encrypted
    openssl enc -aes-256-cbc -d -in .env.encrypted -out .env -k "%LARAVEL_ENV_ENCRYPTION_KEY%"
    ```

5. **Install Node.js dependencies:**

    ```bash
    npm install
    ```

6. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

7. **Setup database:**

    ```bash
    php artisan migrate --force
    php artisan db:seed --force
    ```

8. **Create storage link:**

    ```bash
    php artisan storage:link
    ```

9. **Clear caches:**
    ```bash
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear
    ```

## Development

### Start Development Server

```bash
composer run dev
```

This command starts both the Laravel development server and frontend asset compilation.

### Testing

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests (if available)
npm run test
```

### Database Operations

```bash
# Create new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### How to Contribute

1. **Fork** the repository
2. **Create** a feature branch
3. **Commit** your changes
4. **Push** to your branch
5. **Create** a Pull Request

### Git Workflow

#### Branch Naming Convention

```
feature/nama-fitur          # New features
bugfix/nama-bug            # Bug fixes
hotfix/nama-hotfix         # Critical fixes
docs/update-readme         # Documentation updates
```

#### Workflow Steps

1. **Create branch:**

    ```bash
    git checkout -b feature/nama-fitur
    ```

2. **Make changes and commit:**

    ```bash
    git add .
    git commit -m "feat: menambahkan fitur X"
    ```

3. **Push branch:**

    ```bash
    git push origin feature/nama-fitur
    ```

4. **Create Pull Request:**
    - Open GitHub repository
    - Click "New Pull Request"
    - Select your branch
    - Add clear description

#### Commit Message Format

```
type: deskripsi singkat

Types:
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
├── app/                    # Laravel application logic
│   ├── Http/Controllers/   # Controllers
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic
├── resources/             # Views, assets, language files
│   ├── views/            # Blade templates
│   ├── js/               # JavaScript files
│   └── css/              # Stylesheets
├── public/               # Public assets
├── database/             # Database files
│   ├── migrations/       # Database migrations
│   ├── seeders/          # Database seeders
│   └── factories/        # Model factories
├── routes/               # Route definitions
│   ├── web.php          # Web routes
│   └── api.php          # API routes
├── config/               # Configuration files
├── .env.encrypted        # Encrypted environment file
├── install.sh           # Automated setup script
└── README.md            # This file
```

## 🛠️ Development Guidelines

### Code Style

-   **PHP**: Follow PSR-12 coding standards
-   **JavaScript**: Use ESLint for linting
-   **Formatting**: Use Prettier for consistent formatting
-   **Laravel**: Follow Laravel best practices and conventions

### Code Quality

```bash
# PHP Code Style Fixer
./vendor/bin/php-cs-fixer fix

# PHPStan Static Analysis
./vendor/bin/phpstan analyse

# ESLint for JavaScript
npm run lint

# Prettier formatting
npm run format
```

### Environment Configuration

Key environment variables in `.env`:

```env
APP_NAME=Ngabaca
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ngabaca
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 🔧 Troubleshooting

### Common Issues

#### Permission Issues

```bash
# Fix storage and cache permissions
chmod -R 775 storage bootstrap/cache
```

#### Clear All Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

#### Regenerate Autoload

```bash
composer dump-autoload
```

#### Database Issues

```bash
# Reset database
php artisan migrate:fresh --seed

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

#### Node.js Issues

```bash
# Clear npm cache
npm cache clean --force

# Reinstall node modules
rm -rf node_modules package-lock.json
npm install
```

### WSL-Specific Issues

#### File Permission Problems

```bash
# If you encounter permission issues in WSL
sudo chown -R $USER:$USER /path/to/ngabaca
```

#### Windows/WSL File Sync

-   Always work within the WSL filesystem (`/home/username/`)
-   Avoid working in `/mnt/c/` for better performance

### Environment Setup Issues

#### Encryption Key Problems

-   Ensure the encryption key is exactly as provided
-   Check for extra spaces or characters
-   Verify the key format (should start with `base64:`)

#### OpenSSL Issues

```bash
# Test OpenSSL installation
openssl version

# Test decryption manually
openssl enc -aes-256-cbc -d -in .env.encrypted -out test.env -k "your-key-here"
```

## 📞 Contact

If you encounter any issues or have questions:

-   **Create an issue** on GitHub
-   **Contact maintainer**: [@alfinpratamaa](https://github.com/alfinpratamaa)
-   **Project repository**: [https://github.com/alfinpratamaa/ngabaca](https://github.com/alfinpratamaa/ngabaca)

## License

This project is licensed under the [MIT License](LICENSE).

---

**Happy Coding! 🎉**
