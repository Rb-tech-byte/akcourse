# AK23 Course Portal — CI/CD & Deployment Guide

This repository contains automated CI/CD pipelines powered by **GitHub Actions** for testing, building, and deploying the AK23 Multi-Vendor Course & Student Portal to staging/production environments.

---

## 1. Git Repository Configuration

- **Remote URL:** `git@github.com:Rb-tech-byte/akcourse.git`
- **Default Branch:** `main`

---

## 2. CI/CD Pipelines Overview

### A. Continuous Integration (`.github/workflows/ci.yml`)
Runs automatically on every `push` and `pull_request` to `main`, `master`, and `develop`:
1. Sets up PHP 8.3 with all required production extensions (`pdo_mysql`, `mbstring`, `zip`, `gd`, `bcmath`, `curl`, etc.).
2. Validates `composer.json` syntax and dependencies.
3. Installs dependencies using Composer cache.
4. Performs PHP syntax linting across all application controllers, models, and traits.
5. Verifies configuration, route, and view compilation.

### B. Continuous Deployment (`.github/workflows/cd.yml`)
Runs automatically on every push to `main` (or via manual trigger `workflow_dispatch`):
1. Connects to the target server securely via SSH.
2. Activates maintenance mode (`php artisan down`).
3. Fetches the latest code from `main`.
4. Executes `composer install --no-dev --optimize-autoloader`.
5. Runs pending database migrations (`php artisan migrate --force`).
6. Rebuilds optimized caches (`config:cache`, `route:cache`, `view:cache`).
7. Restores file permissions on `storage` and `bootstrap/cache`.
8. Re-enables the live application (`php artisan up`).

---

## 3. GitHub Secrets Configuration

To enable automated CD deployment, configure the following secrets in your GitHub repository (**Settings > Secrets and variables > Actions > New repository secret**):

| Secret Name | Description | Example |
|---|---|---|
| `SSH_HOST` | Production server IP or hostname | `203.0.113.50` or `portal.akcourse.com` |
| `SSH_USER` | SSH user with deploy permissions | `deployer` or `ubuntu` |
| `SSH_KEY` | Private SSH key (PEM / OpenSSH format) | `-----BEGIN OPENSSH PRIVATE KEY----- ...` |
| `SSH_PORT` | SSH Port (default is 22) | `22` |
| `DEPLOY_PATH` | Absolute path to application root on server | `/var/www/akcourse` |

> **Security Note:** Make sure the public key corresponding to `SSH_KEY` is added to `~/.ssh/authorized_keys` on the target server.

---

## 4. Server Prerequisites

* **Operating System:** Ubuntu 22.04+ / Debian 12 / AlmaLinux / CentOS
* **Web Server:** Nginx or Apache with `mod_rewrite` enabled
* **PHP:** `8.3` or higher with extensions:
  * `php8.3-fpm`, `php8.3-mysql`, `php8.3-curl`, `php8.3-mbstring`, `php8.3-xml`, `php8.3-zip`, `php8.3-gd`, `php8.3-bcmath`
* **Database:** MySQL 8.0+ or MariaDB 10.6+
* **Composer:** 2.x

---

## 5. Web Server Configuration & Hardening

### Apache
The repository includes pre-configured, hardened `.htaccess` files:
- Root `.htaccess`: Enforces security headers, blocks direct access to `core/`, `deploy/`, `scratch/`, `.git/`, and sensitive files (`.env`, `.sql`, `.log`, `.yml`).
- `assets/.htaccess`: Completely disables the PHP engine and denies execution of all uploaded scripts.
- `core/.htaccess`: Denies all direct HTTP requests to backend source files.

### Nginx
A production-ready, hardened Nginx server block template is provided in [`deploy/nginx-site.conf.example`](file:///c:/wamp64/www/akcourse/deploy/nginx-site.conf.example).
Copy it to your Nginx sites configuration:
```bash
cp deploy/nginx-site.conf.example /etc/nginx/sites-available/akcourse.conf
ln -s /etc/nginx/sites-available/akcourse.conf /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

---

## 6. Directory Permissions & Environment Setup

1. Copy `.env.example` to `.env` inside `core/`:
```bash
cp core/.env.example core/.env
cd core && php artisan key:generate
```
2. Update `core/.env` for production:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://your-domain.com`
   - `SESSION_SECURE_COOKIE=true`
3. Enforce secure permissions:
```bash
cd /path/to/akcourse
chown -R www-data:www-data assets core/storage core/bootstrap/cache
chmod -R 775 core/storage core/bootstrap/cache assets
chmod 600 core/.env
```

---

## 7. Performance Optimization & Caching

During deployment (automated via [`deploy/deploy.sh`](file:///c:/wamp64/www/akcourse/deploy/deploy.sh)):
```bash
cd core
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 8. Scheduled Tasks (Cron Jobs)

Add the Laravel scheduler or system cron to your server's crontab:
```bash
crontab -e
```
Add the following entry:
```bash
* * * * * cd /path/to/akcourse/core && php artisan schedule:run >> /dev/null 2>&1
```
Or for custom system cron:
```bash
* * * * * curl -s https://your-domain.com/cron >> /dev/null 2>&1
```
