# Maarifa23 &middot; AK23 Academy &amp; Studio Portal

[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://www.php.net/)
[![Laravel 12.x](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![License: Proprietary](https://img.shields.io/badge/License-Proprietary-gold.svg)]()

**Maarifa23 &middot; AK23 Academy** is a modern music production e-learning and studio software licensing platform. The system delivers structured vocal mixing, beat making, and sound engineering courses bundled with matching licensed software packages, digital certificate verification, and a student management portal.

---

## Key Features

- **Course Catalog &amp; Series:** Organized course progressions with hardware rack-inspired UI, lesson counts, video durations, audio preview players, and categorized search.
- **Perpetual One-Time Licenses:** Automatic generation and verification of unique software license keys and tamper-resistant purchase certificates.
- **Student Portal:** Personalized dashboard displaying active courses, software suite downloads, verified certificates, and recent orders.
- **Identity &amp; Security:** Two-Factor Authentication (2FA), KYC verification document submission, and granular profile management.
- **Administrative CMS:** Comprehensive course management, sales analytics, student management, gateway configuration, and license audits.
- **Automated Deployment:** Production-ready CI/CD workflows and automated deployment script (`deploy/deploy.sh`).

---

## Architecture Overview

```
akcourse/
├── assets/                # Public static assets, icons, themes, audio previews
│   └── .htaccess          # Security lockdown: Disables script execution in assets
├── core/                  # Core Laravel 12 application
│   ├── app/               # Models, Controllers, Services, Middleware
│   ├── bootstrap/         # Framework initialization
│   ├── config/            # Application & service configurations
│   ├── database/          # Migrations, seeders, and MySQL schema definitions
│   ├── resources/         # Blade views, portal templates, and language files
│   ├── routes/            # Web, API, and Admin route definitions
│   └── storage/           # Caches, session data, and application logs
├── deploy/                # Deployment automation
│   ├── deploy.sh          # Production deployment shell script
│   └── nginx-site.conf.example # Hardened Nginx server block template
├── DEPLOYMENT.md          # Server prerequisites, CI/CD, and deployment instructions
├── index.php              # Front controller entry point
└── .htaccess              # Root web server routing and security headers
```

---

## Production Deployment

Refer to [`DEPLOYMENT.md`](file:///c:/wamp64/www/akcourse/DEPLOYMENT.md) for full server setup, permission guides, and automated CI/CD instructions.

### Quick Deploy:
```bash
bash deploy/deploy.sh
```

---

## Security & Hardening

- **Upload Sanitization:** Strict MIME type, file extension, and image verification across all upload handlers.
- **Execution Lockdown:** Script execution (`php`, `phtml`, `phar`, `exe`, etc.) is blocked inside public upload directories (`assets/`).
- **HTTP Security Headers:** Root `.htaccess` and Nginx templates enforce `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, and strict referrer policies.
- **Protected Internals:** Direct HTTP access to `core/`, `deploy/`, and sensitive files (`.env`, `.sql`, `.log`) is strictly denied.

---

&copy; Maarifa23 / AK23 Studio Kits. All Rights Reserved.
