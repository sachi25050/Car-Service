# Setup Guide - Car Wash & Vehicle Service Management System

## Quick Start

### 1. Prerequisites
- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM

### 2. Installation Steps

```bash
# 1. Install dependencies
composer install
npm install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=car_service_db
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Run migrations
php artisan migrate

# 6. Seed database
php artisan db:seed

# 7. Start server
php artisan serve
```

### 3. Default Login Credentials

**Admin Account:**
- Email: `admin@carservice.com`
- Password: `password`

**Manager Account:**
- Email: `manager@carservice.com`
- Password: `password`

### 4. Database Schema

The complete database schema is available in `database/schema.sql`. You can import it directly:

```bash
mysql -u root -p car_service_db < database/schema.sql
```

### 5. Key Features

✅ **Customer Management** - Complete CRUD operations
✅ **Vehicle Management** - Track customer vehicles
✅ **Service Management** - Services, categories, and packages
✅ **Appointment Booking** - With conflict detection
✅ **Job Cards** - Service order management
✅ **Invoicing** - Automatic invoice generation
✅ **Payments** - Multiple payment methods
✅ **Reports** - Revenue, appointments, services
✅ **Dashboard** - Real-time statistics
✅ **Role-Based Access** - Admin, Manager, Staff, Technician

### 6. Project Structure

```
app/
├── Http/
│   ├── Controllers/        # All controllers
│   │   ├── Admin/         # Admin controllers
│   │   └── Auth/          # Authentication
│   └── Requests/          # Form validations
└── Models/                # Eloquent models

database/
├── migrations/            # Database migrations
├── seeders/              # Database seeders
└── schema.sql            # Complete SQL schema

resources/
└── views/                # Blade templates
    ├── layouts/          # Layout files
    ├── auth/             # Login/Register
    ├── customers/        # Customer views
    ├── vehicles/         # Vehicle views
    ├── appointments/     # Appointment views
    ├── job-cards/        # Job card views
    ├── invoices/         # Invoice views
    └── payments/         # Payment views

routes/
└── web.php               # Web routes
```

### 7. Common Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run migrations
php artisan migrate
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName

# Create new controller
php artisan make:controller ControllerName --resource
```

### 8. Troubleshooting

**Issue: Migration errors**
- Ensure database exists and credentials are correct
- Run `php artisan migrate:fresh` to reset database

**Issue: Permission denied**
- Run `chmod -R 775 storage bootstrap/cache`
- Run `chown -R www-data:www-data storage bootstrap/cache` (Linux)

**Issue: Class not found**
- Run `composer dump-autoload`
- Clear cache: `php artisan optimize:clear`

### 9. Development Notes

- All models use Eloquent relationships
- Form requests handle validation
- Activity logs track user actions
- Soft deletes enabled for most models
- Bootstrap 5 for UI
- Responsive design

### 10. Next Steps

1. Customize roles and permissions
2. Add more services and packages
3. Configure email settings
4. Set up backup procedures
5. Customize reports as needed

---

For detailed documentation, see README.md
