# Car Wash & Vehicle Service Management System

A comprehensive Laravel-based system for managing car wash services, vehicle maintenance, appointments, job cards, invoices, and payments.

## 🚀 Features

- **User Management**: Roles, permissions, and staff management
- **Customer Management**: Complete customer database with contact information
- **Vehicle Management**: Track customer vehicles with detailed information
- **Service Management**: Services, categories, and service packages
- **Appointment Booking**: Schedule and manage appointments with conflict detection
- **Job Cards**: Create and track service orders
- **Invoicing**: Generate invoices from job cards
- **Payment Processing**: Track payments and payment methods
- **Reports & Analytics**: Revenue reports, appointment reports, and service analytics
- **Dashboard**: Real-time statistics and overview

## 📋 Requirements

- PHP >= 8.1
- MySQL >= 5.7
- Composer
- Node.js & NPM (for assets)

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd car-service
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=car_service_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed database**
   ```bash
   php artisan db:seed
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - URL: http://localhost:8000
   - Default Admin Login:
     - Email: `admin@carservice.com`
     - Password: `password`

## 📁 Project Structure

```
car-service/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # All controllers
│   │   │   ├── Admin/          # Admin controllers
│   │   │   └── Auth/           # Authentication controllers
│   │   └── Requests/           # Form request validations
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── schema.sql              # Complete SQL schema
├── resources/
│   └── views/                  # Blade templates
│       ├── layouts/            # Layout files
│       ├── auth/               # Authentication views
│       ├── customers/          # Customer views
│       ├── appointments/       # Appointment views
│       └── ...
├── routes/
│   └── web.php                 # Web routes
└── public/                     # Public assets
```

## 🗄️ Database Schema

The system includes the following main tables:

- **users** - System users
- **roles** - User roles
- **permissions** - System permissions
- **staff** - Staff members
- **customers** - Customer information
- **vehicles** - Vehicle details
- **services** - Available services
- **service_categories** - Service categories
- **service_packages** - Service packages
- **appointments** - Appointment bookings
- **job_cards** - Service orders/job cards
- **job_card_services** - Services in job cards
- **invoices** - Customer invoices
- **payments** - Payment records
- **settings** - System settings
- **activity_logs** - Activity tracking

See `database/schema.sql` for the complete schema.

## 🔐 Authentication & Authorization

The system uses Laravel's built-in authentication with role-based access control:

- **Admin**: Full system access
- **Manager**: Management access
- **Staff**: Limited access
- **Technician**: Service-specific access

## 📝 Key Features Implementation

### Appointment Booking
- **Conflict detection for time slots** - See [Appointment Conflict Logic Documentation](docs/APPOINTMENT_CONFLICT_LOGIC.md) for detailed rules
  - 12-minute buffer between appointment start times
  - Maximum 5 concurrent appointments
- Status management (pending, confirmed, in_progress, completed, cancelled)
- Staff assignment

### Job Cards
- Service selection and pricing
- Discount and tax calculation
- Status tracking
- Automatic invoice generation

### Invoicing
- Automatic calculation from job cards
- Tax and discount handling
- Payment tracking
- Status management (draft, sent, paid, partial, overdue)

### Payments
- Multiple payment methods (cash, card, bank transfer, UPI, cheque)
- Automatic invoice balance updates
- Payment history tracking

## 🎨 UI/UX

- Bootstrap 5 for responsive design
- Bootstrap Icons for icons
- Clean, modern interface
- Mobile-friendly design
- Form validation with error messages
- Success/error notifications

## 🔧 Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📊 Reports

The system includes various reports:
- Revenue reports (daily, monthly, yearly)
- Appointment reports
- Service analytics
- Customer reports

## 🔒 Security Features

- CSRF protection
- Password hashing
- Role-based authorization
- Input validation
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)

## 📄 License

This project is proprietary software.

## 👥 Support

For support, please contact the development team.

---

**Built with Laravel 10** 🚀
