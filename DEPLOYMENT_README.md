# Garage Master - cPanel Deployment Guide

This guide explains how to deploy the Garage Master Laravel application on cPanel hosting.

## Prerequisites

- cPanel hosting with:
  - PHP 8.1 or higher
  - MySQL 5.7+ or MariaDB 10.3+
  - Composer access (or upload vendor folder)
  - SSH access (recommended) or File Manager

## Deployment Steps

### Step 1: Prepare Your Files

1. **Compress the project**: Create a ZIP file of the entire `garage` folder
2. **Export database**: If you have existing data, export your MySQL database

### Step 2: Upload Files to cPanel

1. Log in to your cPanel account
2. Open **File Manager**
3. Navigate to `public_html` (or your desired subdomain folder)
4. Upload the ZIP file and extract it
5. Move all files from `garage/public` to `public_html`
6. Move all other files to a folder **outside** `public_html` (e.g., `/home/username/garage_app`)

### Step 3: Configure Database

1. In cPanel, go to **MySQL Databases**
2. Create a new database (e.g., `garage_master`)
3. Create a new database user with a strong password
4. Add the user to the database with **ALL PRIVILEGES**
5. Import your database SQL file via **phpMyAdmin**

### Step 4: Configure Environment File

1. Copy `.env.example` to `.env` in your application root
2. Edit `.env` with your database credentials:

```env
APP_NAME="Garage Master"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 5: Update index.php Paths

Edit `public_html/index.php` to point to your application folder:

```php
// Change these lines to match your folder structure
require __DIR__.'/../garage_app/vendor/autoload.php';
$app = require_once __DIR__.'/../garage_app/bootstrap/app.php';
```

### Step 6: Generate Application Key

If you have SSH access:
```bash
cd /home/username/garage_app
php artisan key:generate
```

If no SSH access, generate a key online and add it to `.env`:
```env
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
```

### Step 7: Set Folder Permissions

Set proper permissions for Laravel folders:

```bash
chmod -R 755 /home/username/garage_app
chmod -R 775 /home/username/garage_app/storage
chmod -R 775 /home/username/garage_app/bootstrap/cache
```

Or via cPanel File Manager, right-click folders and set permissions.

### Step 8: Configure .htaccess

Ensure `public_html/.htaccess` contains:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Or if files are directly in public_html:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 9: Clear Caches (if SSH available)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 10: Set Up Cron Jobs (Optional)

In cPanel, go to **Cron Jobs** and add:

```
* * * * * cd /home/username/garage_app && php artisan schedule:run >> /dev/null 2>&1
```

## Folder Structure After Deployment

```
/home/username/
├── garage_app/           # Laravel application files
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── ...
└── public_html/          # Web-accessible files
    ├── build/
    ├── css/
    ├── js/
    ├── vendors/
    ├── index.php
    ├── .htaccess
    └── ...
```

## Troubleshooting

### 500 Internal Server Error
- Check `.env` file exists and has correct database credentials
- Verify folder permissions (storage and bootstrap/cache need 775)
- Check PHP version compatibility

### Blank Page
- Enable `APP_DEBUG=true` temporarily to see errors
- Check `storage/logs/laravel.log` for error details

### Database Connection Error
- Verify database credentials in `.env`
- Ensure database user has proper privileges
- Check if database host is `localhost` or `127.0.0.1`

### Assets Not Loading
- Verify `APP_URL` in `.env` matches your domain
- Check if `public` folder contents are in correct location
- Clear browser cache

## Security Notes

1. **Set APP_DEBUG=false** in production
2. **Use HTTPS** - Install SSL certificate via cPanel
3. **Protect .env file** - Ensure it's not web-accessible
4. **Regular backups** - Set up automated database backups

## Default Login Credentials

After fresh installation:
- **Admin Email**: admin@admin.com
- **Admin Password**: 12345678

**Important**: Change these credentials immediately after first login!

---

For additional support, refer to the Garage_Documentation folder included with this package.
