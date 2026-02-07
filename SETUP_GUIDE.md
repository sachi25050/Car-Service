# Car Service - Environment & Database Setup Guide

This guide will help you configure the `.env` file and set up the database for the Car Service application.

## Quick Setup (Automated)

Run the setup script to automatically configure everything:

```bash
cd /var/www/Car-Service
./setup.sh          # Setup without seeding
./setup.sh --seed    # Setup with database seeding (creates default users)
```

## Manual Setup

### Step 1: Create .env File

```bash
cd /var/www/Car-Service
cp .env.example .env
```

### Step 2: Configure Database Settings

Edit the `.env` file and update the following database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=car_service_db
DB_USERNAME=root
DB_PASSWORD=root
```

**Important Notes:**
- If your MySQL is on a different host, update `DB_HOST`
- If using a different port, update `DB_PORT`
- Change `DB_DATABASE` to your preferred database name
- Update `DB_USERNAME` and `DB_PASSWORD` with your MySQL credentials

### Step 3: Generate Application Key

```bash
php artisan key:generate
```

This will generate a unique `APP_KEY` in your `.env` file.

### Step 4: Create Database

Connect to MySQL and create the database:

```bash
mysql -u root -p
```

Then run:

```sql
CREATE DATABASE IF NOT EXISTS car_service_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Or use the command line:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS car_service_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 5: Run Migrations

This will create all the necessary database tables:

```bash
php artisan migrate
```

### Step 6: Seed Database (Optional)

This will create default users, roles, and services:

```bash
php artisan db:seed
```

**Default Login Credentials (after seeding):**

**Admin Account:**
- Email: `admin@carservice.com`
- Password: `password`

**Manager Account:**
- Email: `manager@carservice.com`
- Password: `password`

### Step 7: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Other Important .env Settings

### Application Settings

```env
APP_NAME="Car Service"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8051
```

### Mail Configuration (Optional)

If you need email functionality:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@carservice.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Troubleshooting

### Database Connection Issues

1. **Check MySQL is running:**
   ```bash
   sudo systemctl status mysql
   # or
   sudo service mysql status
   ```

2. **Test MySQL connection:**
   ```bash
   mysql -u root -p -e "SELECT 1;"
   ```

3. **Verify credentials in .env:**
   - Make sure there are no extra spaces
   - Check quotes are not needed (Laravel handles this)
   - Ensure the database exists

### Permission Issues

If you encounter permission errors:

```bash
# Fix storage permissions
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Migration Issues

If migrations fail:

```bash
# Reset and re-run migrations (WARNING: This will delete all data)
php artisan migrate:fresh
php artisan db:seed
```

## Verification

After setup, verify everything is working:

1. **Check database connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   >>> exit
   ```

2. **List all tables:**
   ```bash
   php artisan db:show
   ```

3. **Start the application:**
   ```bash
   ./start.sh
   ```

4. **Access the application:**
   - URL: http://localhost:8051
   - Login with the default admin credentials

## Next Steps

1. Review and customize your `.env` file
2. Update default passwords after first login
3. Configure mail settings if needed
4. Set up proper file permissions for production
5. Review security settings for production deployment
