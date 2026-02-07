#!/bin/bash

# Car Service - Start Script
# This script starts both Laravel server and Vite dev server
# Uses Node.js v18.20.8 and port 8051 for Laravel

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}Car Service - Starting Application${NC}"
echo -e "${BLUE}========================================${NC}"

# Detect PHP version and use PHP 8.1+ if available
PHP_CMD="php"
if command -v php8.2 &> /dev/null; then
    PHP_CMD="php8.2"
    echo -e "${YELLOW}Using PHP 8.2${NC}"
elif command -v php8.1 &> /dev/null; then
    PHP_CMD="php8.1"
    echo -e "${YELLOW}Using PHP 8.1${NC}"
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo -e "${RED}✗ Error: Node.js is not installed${NC}"
    exit 1
fi

# Check Node.js version
NODE_VERSION=$(node --version)
echo -e "${YELLOW}Node.js version: ${NODE_VERSION}${NC}"

# Verify Node.js version (should be 18.20.8)
if [[ ! "$NODE_VERSION" =~ ^v18\.20\.8 ]]; then
    echo -e "${YELLOW}⚠ Warning: Expected Node.js v18.20.8, but found ${NODE_VERSION}${NC}"
    echo -e "${YELLOW}Continuing anyway...${NC}"
fi

# Check if PHP is installed
if ! command -v $PHP_CMD &> /dev/null; then
    echo -e "${RED}✗ Error: PHP is not installed${NC}"
    exit 1
fi

# Check if npm dependencies are installed
if [ ! -d "node_modules" ]; then
    echo -e "${YELLOW}Installing npm dependencies...${NC}"
    npm install
    if [ $? -ne 0 ]; then
        echo -e "${RED}✗ Error: Failed to install npm dependencies${NC}"
        echo -e "${YELLOW}You may need to run: sudo npm install${NC}"
        exit 1
    fi
    echo -e "${GREEN}✓ npm dependencies installed${NC}"
fi

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}Creating .env file from .env.example...${NC}"
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${YELLOW}⚠ Please configure your .env file (database, etc.) before continuing${NC}"
    else
        echo -e "${RED}✗ Error: .env.example file not found${NC}"
    fi
fi

# Function to cleanup on exit
cleanup() {
    echo -e "\n${YELLOW}Stopping servers...${NC}"
    if [ ! -z "$LARAVEL_PID" ]; then
        kill $LARAVEL_PID 2>/dev/null
    fi
    if [ ! -z "$VITE_PID" ]; then
        kill $VITE_PID 2>/dev/null
    fi
    echo -e "${GREEN}✓ Servers stopped${NC}"
    exit 0
}

# Trap Ctrl+C
trap cleanup SIGINT SIGTERM

# Start Laravel server on port 8051
echo -e "${GREEN}Starting Laravel server on port 8051...${NC}"
$PHP_CMD artisan serve --port=8051 --host=0.0.0.0 > /dev/null 2>&1 &
LARAVEL_PID=$!

# Wait a moment for Laravel to start
sleep 2

# Check if Laravel started successfully
if ps -p $LARAVEL_PID > /dev/null; then
    echo -e "${GREEN}✓ Laravel server running on http://localhost:8051 (PID: $LARAVEL_PID)${NC}"
else
    echo -e "${RED}✗ Error: Failed to start Laravel server${NC}"
    exit 1
fi

# Start Vite dev server
echo -e "${GREEN}Starting Vite dev server...${NC}"
npm run dev > /dev/null 2>&1 &
VITE_PID=$!

# Wait a moment for Vite to start
sleep 2

# Check if Vite started successfully
if ps -p $VITE_PID > /dev/null; then
    echo -e "${GREEN}✓ Vite dev server running (PID: $VITE_PID)${NC}"
else
    echo -e "${RED}✗ Error: Failed to start Vite dev server${NC}"
    kill $LARAVEL_PID 2>/dev/null
    exit 1
fi

echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}Application is running!${NC}"
echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}Laravel: http://localhost:8051${NC}"
echo -e "${GREEN}Vite Dev Server: http://localhost:5173${NC}"
echo -e "${YELLOW}Press Ctrl+C to stop both servers${NC}"
echo -e "${BLUE}========================================${NC}"

# Wait for both processes
wait $LARAVEL_PID $VITE_PID
