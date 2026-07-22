Part B: Hostinger Setup (You Do These — Out of My Control)
Enable automated backups — Hostinger Business plan includes free daily backups. Enable them in the hPanel.
Set APP_DEBUG=false in the actual .env file on the server — I can set it in .env.example, but the real .env on Hostinger needs to be manually edited.
Configure document root to /public — In Hostinger, set the domain's document root to the [/Users/fankarwanta/Desktop/archive_projects/Garage Master v4.6.5 Nulled/garage_master/garage/public/](cci:4://file:///Users/fankarwanta/Desktop/archive_projects/Garage Master v4.6.5 Nulled/garage_master/garage/public/:0:0-0:0) subdirectory so Laravel boots correctly.
Set proper [/Users/fankarwanta/Desktop/archive_projects/Garage Master v4.6.5 Nulled/garage_master/garage/storage/](cci:4://file:///Users/fankarwanta/Desktop/archive_projects/Garage Master v4.6.5 Nulled/garage_master/garage/storage/:0:0-0:0) and [/Users/fankarwanta/Desktop/archive_projects/Garage Master v4.6.5 Nulled/garage_master/garage/bootstrap/cache/](cci:4://file:///Users/fankarwanta/Desktop/archive_projects/Garage Master v4.6.5 Nulled/garage_master/garage/bootstrap/cache/:0:0-0:0) permissions — Run chmod -R 775 storage bootstrap/cache via SSH after deployment.
Import the SQL dump — Use phpMyAdmin to import garage_0305.sql, then run php artisan migrate for the payroll tables.
Set up SSL/HTTPS — Enable free Let's Encrypt SSL from Hostinger hPanel. The HTTPS redirect middleware I add will enforce it, but the cert needs to be active first.
Configure cron for Laravel scheduler — Add * * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1 in Hostinger's cron jobs section.
Set APP_URL in .env — Set it to your actual domain (e.g., https://yourdomain.com).
Run php artisan config:cache and php artisan route:cache after deployment for performance.
Create the sessions table — After I add the migration, run php artisan session:table && php artisan migrate on the server.