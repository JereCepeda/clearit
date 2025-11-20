# ClearIT Docker Setup - Complete Working Configuration

## 🐳 Docker Configuration Files

You now have a complete Docker setup for your ClearIT Laravel application. Here are the key files:

### 1. **docker-compose.yml** (Main Production Setup)
```yaml
services:
  # MySQL Database
  mysql:
    image: mysql:8.0
    container_name: clearit_mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: clearit_db
      MYSQL_USER: clearit_user
      MYSQL_PASSWORD: clearit_password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3307:3306"  # Changed to avoid conflict with XAMPP
    networks:
      - clearit_network

  # PHP-FPM Service  
  php:
    build:
      context: .
      dockerfile: ./docker/php/Dockerfile
    container_name: clearit_php
    restart: unless-stopped
    volumes:
      - .:/var/www/html
    environment:
      - DB_CONNECTION=mysql
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=clearit_db
      - DB_USERNAME=clearit_user
      - DB_PASSWORD=clearit_password
    depends_on:
      - mysql
    networks:
      - clearit_network

  # Nginx Web Server
  nginx:
    image: nginx:alpine
    container_name: clearit_nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html:ro
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - php
    networks:
      - clearit_network

volumes:
  mysql_data:
    driver: local

networks:
  clearit_network:
    driver: bridge
```

### 2. **docker-compose.simple.yml** (Quick Development Setup)
This version uses pre-built images for faster startup during development.

### 3. **docker/php/Dockerfile** (PHP Container Build)
Optimized Dockerfile that installs all necessary PHP extensions and dependencies.

### 4. **docker/nginx/default.conf** (Nginx Configuration)
Properly configured for Laravel with PHP-FPM.

### 5. **docker/setup-laravel.sh** (Laravel Setup Script)
Automated script that configures the Laravel application inside the container.

## 📋 Usage Instructions

### Starting the Application

#### Option 1: Simple Development Setup (Recommended for testing)
```bash
# Start with pre-built images (faster)
docker compose -f docker-compose.simple.yml up -d

# Check status
docker compose -f docker-compose.simple.yml ps

# View logs
docker compose -f docker-compose.simple.yml logs -f
```

#### Option 2: Full Production Setup
```bash
# Build and start (slower first time)
docker compose up --build -d

# Check status
docker compose ps

# View logs
docker compose logs -f
```

### Managing the Application

#### Setup Laravel (after containers are running)
```bash
# Install dependencies
docker compose exec php composer install

# Generate application key
docker compose exec php php artisan key:generate

# Run migrations and seeders
docker compose exec php php artisan migrate:fresh --seed

# Set permissions
docker compose exec php chown -R www-data:www-data storage bootstrap/cache
docker compose exec php chmod -R 775 storage bootstrap/cache
```

#### Daily Operations
```bash
# Stop containers
docker compose down

# Start containers
docker compose up -d

# Restart specific service
docker compose restart php

# View logs for specific service
docker compose logs php

# Execute commands in PHP container
docker compose exec php php artisan route:list
docker compose exec php php artisan tinker
```

## 🌐 Access Points

- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081 (if included)
- **MySQL Database**: localhost:3307
  - Database: clearit_db
  - Username: clearit_user
  - Password: clearit_password
  - Root Password: root_password

## 🛠️ Troubleshooting

### Common Issues

1. **Port Conflicts**
   - If ports are in use, modify the `ports` section in docker-compose.yml
   - MySQL changed to 3307 to avoid XAMPP conflict

2. **Permission Issues**
   ```bash
   docker compose exec php chown -R www-data:www-data storage bootstrap/cache
   docker compose exec php chmod -R 775 storage bootstrap/cache
   ```

3. **Database Connection**
   - Ensure `.env` file has correct database settings
   - Check MySQL container is running: `docker compose ps`

4. **502 Bad Gateway**
   - Usually means PHP-FPM is not running
   - Check PHP logs: `docker compose logs php`

5. **Building Issues**
   - Clean build: `docker compose build --no-cache`
   - Remove old images: `docker system prune -a`

### Environment Configuration

Make sure your `.env` file has these database settings:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=clearit_db
DB_USERNAME=clearit_user
DB_PASSWORD=clearit_password
```

## 🚀 Production Deployment

For production deployment:

1. **Security**: Change default passwords
2. **SSL**: Add SSL certificates and HTTPS configuration
3. **Performance**: Use production PHP configuration
4. **Backup**: Set up automated database backups
5. **Monitoring**: Add logging and monitoring tools

## 📁 File Structure

```
ClearitExam/
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   ├── php/
│   │   └── Dockerfile
│   └── setup-laravel.sh
├── docker-compose.yml
├── docker-compose.simple.yml
├── .dockerignore
└── .env.docker
```

This setup provides a complete containerized environment for your ClearIT Laravel application with proper isolation, scalability, and maintainability.