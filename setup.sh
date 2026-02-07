#!/bin/bash

# Car Service - Database and Environment Setup Script

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}Car Service - Database Setup${NC}"
echo -e "${BLUE}========================================${NC}"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}✗ Error: Please run this script from the Laravel project root directory${NC}"
    exit 1
fi

# Detect PHP version and use PHP 8.1+ if available
PHP_CMD="php"
if command -v php8.2 &> /dev/null; then
    PHP_CMD="php8.2"
    echo -e "${YELLOW}Using PHP 8.2 for setup${NC}"
elif command -v php8.1 &> /dev/null; then
    PHP_CMD="php8.1"
    echo -e "${YELLOW}Using PHP 8.1 for setup${NC}"
else
    PHP_VERSION=$($PHP_CMD -r "echo PHP_VERSION;" 2>/dev/null)
    if [[ $(echo "$PHP_VERSION 8.1" | tr " " "\n" | sort -V | head -n 1) != "8.1" ]]; then
        echo -e "${RED}✗ Error: PHP 8.1 or higher is required (found: $PHP_VERSION)${NC}"
        echo -e "${YELLOW}Please install PHP 8.1 or higher${NC}"
        exit 1
    fi
fi

# Check if Composer dependencies are installed
if [ ! -d "vendor" ]; then
    echo -e "${YELLOW}Installing Composer dependencies...${NC}"
    if command -v composer &> /dev/null; then
        $PHP_CMD $(which composer) install --no-interaction
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✓ Composer dependencies installed${NC}"
        else
            echo -e "${RED}✗ Error: Failed to install Composer dependencies${NC}"
            echo -e "${YELLOW}Please run: composer install${NC}"
            exit 1
        fi
    else
        echo -e "${RED}✗ Error: Composer is not installed${NC}"
        echo -e "${YELLOW}Please install Composer first: https://getcomposer.org/${NC}"
        exit 1
    fi
fi

# Step 1: Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}Step 1: Creating .env file from .env.example...${NC}"
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}✓ .env file created${NC}"
        
        # Fix DB_HOST if it has port in it (127.0.0.1:3310 -> 127.0.0.1)
        if grep -q "DB_HOST=127.0.0.1:3310" .env; then
            sed -i 's/DB_HOST=127.0.0.1:3310/DB_HOST=127.0.0.1/' .env
            echo -e "${GREEN}✓ Fixed DB_HOST configuration${NC}"
        fi
    else
        echo -e "${RED}✗ Error: .env.example file not found${NC}"
        exit 1
    fi
else
    echo -e "${YELLOW}Step 1: .env file already exists, skipping...${NC}"
fi

# Step 2: Generate application key if not set
echo -e "${YELLOW}Step 2: Checking application key...${NC}"
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}Generating application key...${NC}"
    $PHP_CMD artisan key:generate
    echo -e "${GREEN}✓ Application key generated${NC}"
else
    echo -e "${GREEN}✓ Application key already exists${NC}"
fi

# Step 3: Read database configuration from .env
echo -e "${YELLOW}Step 3: Reading database configuration...${NC}"
DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
DB_PORT=$(grep "^DB_PORT=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")

DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-car_service_db}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-root}

echo -e "${BLUE}Database Configuration:${NC}"
echo -e "  Host: ${DB_HOST}"
echo -e "  Port: ${DB_PORT}"
echo -e "  Database: ${DB_DATABASE}"
echo -e "  Username: ${DB_USERNAME}"
echo -e "  Password: ${DB_PASSWORD:0:1}***"

# Step 4: Test MySQL connection
echo -e "${YELLOW}Step 4: Testing MySQL connection...${NC}"
if mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" > /dev/null 2>&1; then
    echo -e "${GREEN}✓ MySQL connection successful${NC}"
else
    echo -e "${RED}✗ Error: Cannot connect to MySQL${NC}"
    echo -e "${YELLOW}Please check your database credentials in .env file${NC}"
    exit 1
fi

# Step 5: Create database if it doesn't exist
echo -e "${YELLOW}Step 5: Creating database if it doesn't exist...${NC}"
if mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "USE $DB_DATABASE;" > /dev/null 2>&1; then
    echo -e "${GREEN}✓ Database '$DB_DATABASE' already exists${NC}"
else
    echo -e "${YELLOW}Creating database '$DB_DATABASE'...${NC}"
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>&1
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Database '$DB_DATABASE' created successfully${NC}"
    else
        echo -e "${RED}✗ Error: Failed to create database${NC}"
        exit 1
    fi
fi

# Step 6: Run migrations
echo -e "${YELLOW}Step 6: Running database migrations...${NC}"
$PHP_CMD artisan migrate --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migrations completed successfully${NC}"
else
    echo -e "${RED}✗ Error: Migrations failed${NC}"
    exit 1
fi

# Step 7: Seed database (non-interactive - use --seed flag to seed)
SEED_DB=${1:-""}
if [ "$SEED_DB" = "--seed" ]; then
    echo -e "${YELLOW}Step 7: Seeding database with initial data...${NC}"
    $PHP_CMD artisan db:seed --force
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Database seeded successfully${NC}"
        echo -e "${BLUE}========================================${NC}"
        echo -e "${GREEN}Default Login Credentials:${NC}"
        echo -e "${BLUE}========================================${NC}"
        echo -e "${GREEN}Admin Account:${NC}"
        echo -e "  Email: admin@carservice.com"
        echo -e "  Password: password"
        echo -e ""
        echo -e "${GREEN}Manager Account:${NC}"
        echo -e "  Email: manager@carservice.com"
        echo -e "  Password: password"
        echo -e "${BLUE}========================================${NC}"
    else
        echo -e "${RED}✗ Error: Database seeding failed${NC}"
        exit 1
    fi
else
    echo -e "${YELLOW}Step 7: Skipping database seeding${NC}"
    echo -e "${YELLOW}  (Run './setup.sh --seed' to seed the database)${NC}"
fi

# Step 8: Clear cache
echo -e "${YELLOW}Step 8: Clearing application cache...${NC}"
$PHP_CMD artisan config:clear
$PHP_CMD artisan cache:clear
$PHP_CMD artisan view:clear
echo -e "${GREEN}✓ Cache cleared${NC}"

echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}Setup completed successfully!${NC}"
echo -e "${BLUE}========================================${NC}"
echo -e "${YELLOW}Next steps:${NC}"
echo -e "1. Review your .env file and update any settings if needed"
echo -e "2. Run './start.sh' to start the application"
echo -e "3. Access the application at http://localhost:8051"
echo -e "${BLUE}========================================${NC}"
