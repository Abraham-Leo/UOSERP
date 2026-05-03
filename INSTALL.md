# 🏭 Cetec ERP — Complete Installation Guide

## System Requirements

| Component | Minimum |
|-----------|---------|
| PHP | 8.1+ |
| MySQL / MariaDB | 8.0+ |
| Composer | 2.x |
| Node.js | 18+ |
| Web Server | Apache / Nginx |

---

## 🚀 Quick Install (Development)

```bash
# 1. Clone / extract this project
cd /var/www
git clone <repo> cetec-erp
cd cetec-erp

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
DB_DATABASE=cetec_erp
DB_USERNAME=root
DB_PASSWORD=your_password

# 6. Create database
mysql -u root -p -e "CREATE DATABASE cetec_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Run migrations
php artisan migrate

# 8. Seed demo data (roles, users, sample records)
php artisan db:seed

# 9. Install Spatie permissions tables
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# 10. Link storage
php artisan storage:link

# 11. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 12. Start development server
php artisan serve
```

Open: **http://localhost:8000**

---

## 🌐 Production Hosting (Shared Hosting / cPanel)

### Option A: Shared Hosting

```bash
# 1. Upload files via FTP/SFTP to public_html/
# 2. Move public/ contents to public_html/ root
# 3. Move all other files to a folder OUTSIDE public_html (e.g. erp-app/)
# 4. Edit public_html/index.php — update paths:
require __DIR__.'/../erp-app/vendor/autoload.php';
$app = require_once __DIR__.'/../erp-app/bootstrap/app.php';

# 5. Create MySQL DB in cPanel
# 6. Configure .env
# 7. SSH into server, run migrations:
php artisan migrate --seed
```

### Option B: VPS (Ubuntu + Nginx)

```bash
# Install stack
apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
            php8.2-curl php8.2-zip php8.2-gd composer nginx mysql-server

# Nginx config: /etc/nginx/sites-available/erp
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/cetec-erp/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Enable & restart
ln -s /etc/nginx/sites-available/erp /etc/nginx/sites-enabled/
nginx -t && systemctl restart nginx

# SSL (Let's Encrypt)
apt install certbot python3-certbot-nginx
certbot --nginx -d yourdomain.com
```

### Option C: Docker

```dockerfile
# docker-compose.yml
version: '3.8'
services:
  app:
    build: .
    ports: ['8000:9000']
    environment:
      DB_HOST: mysql
      DB_DATABASE: cetec_erp
      DB_USERNAME: erp_user
      DB_PASSWORD: secret
    volumes: ['.:/var/www']

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: cetec_erp
      MYSQL_USER: erp_user
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: root_secret
    volumes: ['mysql_data:/var/lib/mysql']

  nginx:
    image: nginx:alpine
    ports: ['80:80']
    volumes:
      - '.:/var/www'
      - './docker/nginx.conf:/etc/nginx/conf.d/default.conf'

volumes:
  mysql_data:
```

```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate --seed
```

---

## 🔐 Default Login Credentials

After seeding, these accounts are available:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@erp.com | password |
| **Sales** | sales@erp.com | password |
| **Production** | prod@erp.com | password |
| **Finance** | finance@erp.com | password |
| **Quality** | quality@erp.com | password |
| **Warehouse** | wh@erp.com | password |
| **Purchasing** | purchase@erp.com | password |

> ⚠️ **Change all passwords immediately in production!**

---

## 📁 Module Structure

```
app/
├── Http/Controllers/
│   ├── Auth/LoginController.php
│   ├── DashboardController.php
│   └── Modules/
│       ├── CRM/          # Customers, Leads, Opportunities
│       ├── Sales/         # Quotes, Orders, Invoices
│       ├── Purchasing/    # Vendors, POs, Receiving
│       ├── Inventory/     # Parts, BOMs, Warehouse, Stock
│       ├── Production/    # Work Orders, MRP, Scheduling
│       ├── Finance/       # AP, AR, GL, Reports
│       ├── QMS/           # NCR, ECO, Inspections
│       ├── HR/            # Users, Roles
│       ├── Tools/         # Assets, Equipment
│       ├── Shipping/      # Shipments, RMAs
│       └── Documents/     # Document Control
├── Models/                # Eloquent models
└── Services/              # Business logic services

resources/views/
├── layouts/app.blade.php  # Main ERP layout
├── auth/login.blade.php   # Login page
├── dashboard.blade.php    # Main dashboard
└── modules/               # All module views
    ├── crm/
    ├── sales/
    ├── purchasing/
    ├── inventory/
    ├── production/
    ├── finance/
    ├── qms/
    └── ...

database/
├── migrations/            # All table schemas
└── seeders/               # Demo data
```

---

## ⚙️ Key Configuration (.env)

```env
APP_NAME="Your Company ERP"
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cetec_erp
DB_USERNAME=erp_user
DB_PASSWORD=strong_password_here

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=erp@yourcompany.com
MAIL_PASSWORD=mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=erp@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_DRIVER=database   # use database for multi-server
SESSION_LIFETIME=480      # 8 hours

# Cache
CACHE_DRIVER=redis        # recommended for production
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Queue (for background jobs like email, PDF generation)
QUEUE_CONNECTION=redis    # or database
```

---

## 🔧 Post-Install Checklist

- [ ] Change all default passwords
- [ ] Configure SMTP mail settings
- [ ] Set APP_DEBUG=false in production
- [ ] Set up nightly DB backups
- [ ] Configure queue worker: `php artisan queue:work`
- [ ] Set up scheduler: add to crontab:
      `* * * * * php /var/www/cetec-erp/artisan schedule:run`
- [ ] Review and customize roles & permissions
- [ ] Import your chart of accounts
- [ ] Configure your warehouse locations
- [ ] Import existing customers/vendors/parts (CSV import available)

---

## 📞 Module Permissions Matrix

| Module | Admin | Sales | Production | Finance | Purchasing | Quality |
|--------|-------|-------|-----------|---------|-----------|---------|
| CRM | ✅ | ✅ | 👁️ | 👁️ | 👁️ | 👁️ |
| Sales Orders | ✅ | ✅ | 👁️ | 👁️ | 👁️ | 👁️ |
| Purchase Orders | ✅ | ❌ | 👁️ | 👁️ | ✅ | ❌ |
| Inventory | ✅ | 👁️ | ✅ | 👁️ | ✅ | 👁️ |
| Production | ✅ | 👁️ | ✅ | ❌ | 👁️ | ✅ |
| Finance | ✅ | ❌ | ❌ | ✅ | 👁️ | ❌ |
| QMS | ✅ | ❌ | ✅ | ❌ | ❌ | ✅ |
| Admin | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

✅ = Full Access · 👁️ = Read Only · ❌ = No Access

---

## 🎯 Tech Stack

- **Framework**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0 / MariaDB
- **Auth**: Laravel Session + Spatie Permissions
- **UI**: Bootstrap 5.3 + Custom CSS
- **Charts**: Chart.js + ApexCharts
- **Tables**: DataTables + Yajra
- **PDF**: DomPDF (Laravel wrapper)
- **Excel**: Maatwebsite Excel
- **Fonts**: Google Fonts (DM Sans, Sora, DM Mono)
- **Icons**: Font Awesome 6

---

*Cetec ERP System — Built for Manufacturing & Distribution Companies*
