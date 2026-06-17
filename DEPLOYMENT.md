# Deployment Guide 🚀

Comprehensive guide for deploying SmartBranch BI to production environments.

## Table of Contents

- [Pre-deployment Checklist](#pre-deployment-checklist)
- [InfinityFree Deployment](#infinityfree-deployment)
- [Self-hosted Server Deployment](#self-hosted-server-deployment)
- [Docker Deployment](#docker-deployment)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [ML Model Deployment](#ml-model-deployment)
- [SSL/HTTPS Setup](#sslhttps-setup)
- [Monitoring & Maintenance](#monitoring--maintenance)
- [Troubleshooting](#troubleshooting)

## Pre-deployment Checklist

Before deploying, ensure all items are completed:

- [ ] Code is tested and all tests pass
- [ ] No sensitive information in codebase
- [ ] Environment variables are configured
- [ ] Database is backed up
- [ ] SSL certificate is ready
- [ ] Domain name is registered and configured
- [ ] File permissions are correct (755 for dirs, 644 for files)
- [ ] Database migrations are tested
- [ ] ML models are trained and validated
- [ ] All dependencies are specified in requirements/composer files
- [ ] Documentation is updated
- [ ] Performance is optimized (minified assets, caching)

## InfinityFree Deployment

### Account Setup

1. **Create InfinityFree Account**
   - Visit https://www.infinityfree.net
   - Register free account
   - Verify email

2. **Create New Website**
   - Go to Dashboard
   - Click "Create New"
   - Choose domain name or use provided subdomain
   - Select PHP 8.3+

3. **Access File Manager**
   - Login to control panel
   - Navigate to File Manager
   - Access public_html directory

### Deployment Steps

#### Step 1: Prepare Application

```bash
# From your local machine
cd Laravel

# Install dependencies
composer install --no-dev --optimize-autoloader

# Build frontend assets
npm install --legacy-peer-deps
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 2: Upload via File Manager

1. **Create directory structure**
   ```
   public_html/
   ├── public/          (application public files)
   └── storage/         (file uploads)
   
   parent_directory/    (above public_html)
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── resources/
   ├── routes/
   ├── vendor/
   ├── .env
   ├── artisan
   └── composer.lock
   ```

2. **Upload files using File Manager**
   - Upload `public/` contents to `public_html/`
   - Upload application files to parent directory
   - Use "Create Folder" for necessary directories

3. **Set File Permissions**
   - Right-click on `storage/` → Permissions → 755
   - Right-click on `bootstrap/cache/` → Permissions → 755

#### Step 3: Configure Environment

1. **Create .env file**
   - In parent directory, create new file `.env`
   - Copy from `.env.example`
   - Update database credentials:

```env
APP_NAME="SmartBranch BI"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=mysql.infinityfree.com
DB_DATABASE=epiz_xxxxx_smartbranch
DB_USERNAME=epiz_xxxxx
DB_PASSWORD=your_db_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

ML_API_URL=http://ml-api.yourdomain.com:8000
ML_API_FORECAST_ENDPOINT=/api/forecast
```

#### Step 4: Database Setup

1. **Access MySQL Admin**
   - Go to InfinityFree Dashboard
   - Navigate to MySQL Manager
   - Create database (e.g., `epiz_xxxxx_smartbranch`)

2. **Run Migrations**
   - Connect via SSH or use Web Terminal
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

3. **Verify Database**
   - Check tables created in MySQL Manager
   - Verify data integrity

#### Step 5: Test Application

1. Visit your domain: `https://your-domain.com`
2. Test login functionality
3. Verify all pages load correctly
4. Check database connectivity

### Using FTP Client (Alternative)

If using FTP instead of File Manager:

```bash
# Using FileZilla or WinSCP
# FTP Host: files.infinityfree.com
# Username: Your InfinityFree username
# Password: Your FTP password
# Port: 21 (standard)

# Upload to public_html and parent directory accordingly
```

## Self-hosted Server Deployment

### Server Requirements

- **OS**: Ubuntu 22.04 LTS (recommended)
- **RAM**: 4GB minimum
- **Storage**: 50GB SSD
- **PHP**: 8.3+
- **MySQL**: 5.7+
- **Node.js**: 18+
- **Python**: 3.8+

### Installation Steps

#### Step 1: Update System

```bash
sudo apt update
sudo apt upgrade -y
```

#### Step 2: Install Dependencies

```bash
# Install PHP and extensions
sudo apt install -y php8.3-cli php8.3-fpm php8.3-mysql php8.3-mbstring \
    php8.3-xml php8.3-bcmath php8.3-json php8.3-zip php8.3-curl

# Install Node.js
curl -sL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Python
sudo apt install -y python3.10 python3.10-venv python3-pip

# Install MySQL
sudo apt install -y mysql-server

# Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

#### Step 3: Setup Application Directory

```bash
# Create application directory
sudo mkdir -p /var/www/smartbranch
cd /var/www/smartbranch

# Clone repository
git clone https://github.com/yourusername/smartbranch-bi.git .

# Set permissions
sudo chown -R www-data:www-data /var/www/smartbranch
sudo chmod -R 755 /var/www/smartbranch
sudo chmod -R 755 storage bootstrap/cache
```

#### Step 4: Install Application

```bash
cd /var/www/smartbranch/Laravel

# Install PHP dependencies
composer install --optimize-autoloader

# Install Node dependencies
npm install --legacy-peer-deps

# Build assets
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database credentials in .env
nano .env

# Run migrations
php artisan migrate --force
php artisan db:seed --force
```

#### Step 5: Configure Nginx

Create `/etc/nginx/sites-available/smartbranch`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    root /var/www/smartbranch/Laravel/public;
    index index.php index.html;

    # Log files
    access_log /var/log/nginx/smartbranch_access.log;
    error_log /var/log/nginx/smartbranch_error.log;

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Performance optimizations
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }

    # Hide sensitive files
    location ~ /\.env {
        deny all;
    }
    location ~ /\.git {
        deny all;
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/smartbranch /etc/nginx/sites-enabled/
sudo systemctl reload nginx
```

#### Step 6: Setup SSL (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot certonly --nginx -d your-domain.com -d www.your-domain.com
```

#### Step 7: Setup Cron Jobs

```bash
# Edit crontab
sudo crontab -e

# Add Laravel scheduler
* * * * * cd /var/www/smartbranch/Laravel && php artisan schedule:run >> /dev/null 2>&1

# Add backup task
0 2 * * * cd /var/www/smartbranch/Laravel && php artisan backup:run >> /dev/null 2>&1
```

## Docker Deployment

### Dockerfile

Create `Dockerfile` in project root:

```dockerfile
# Build stage
FROM php:8.3-fpm AS builder

WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    libmysqlclient-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql gd mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY Laravel /app

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install --legacy-peer-deps \
    && npm run build

# Production stage
FROM php:8.3-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libmysqlclient-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd mbstring

COPY --from=builder /app /app
COPY --from=builder /usr/bin/composer /usr/bin/composer

# Set permissions
RUN chown -R www-data:www-data /app && chmod -R 755 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "9000:9000"
    volumes:
      - ./Laravel:/app
      - ./Laravel/storage:/app/storage
    environment:
      - DB_HOST=mysql
      - DB_DATABASE=smartbranch
      - DB_USERNAME=smartbranch
      - DB_PASSWORD=secret
    depends_on:
      - mysql
      - redis
    networks:
      - smartbranch

  mysql:
    image: mysql:8.0
    environment:
      - MYSQL_DATABASE=smartbranch
      - MYSQL_USER=smartbranch
      - MYSQL_PASSWORD=secret
      - MYSQL_ROOT_PASSWORD=rootpassword
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - smartbranch

  redis:
    image: redis:7-alpine
    networks:
      - smartbranch

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
      - ./Laravel/public:/app/public:ro
      - ./certs:/etc/nginx/certs:ro
    depends_on:
      - app
    networks:
      - smartbranch

  ml-api:
    build:
      context: ./FastAPI
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - ./FastAPI:/app
    networks:
      - smartbranch

volumes:
  mysql_data:

networks:
  smartbranch:
    driver: bridge
```

### Deploy with Docker

```bash
# Build images
docker-compose build

# Start services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# View logs
docker-compose logs -f
```

## Environment Configuration

### Essential Variables

```env
# Application
APP_NAME="SmartBranch BI"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_KEY=base64:your-generated-key

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=smartbranch_db
DB_USERNAME=db_user
DB_PASSWORD=strong_password

# Mail (Optional)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=cookie
QUEUE_CONNECTION=database

# ML API
ML_API_URL=http://ml-api.yourdomain.com:8000
ML_API_FORECAST_ENDPOINT=/api/forecast
```

## Database Setup

### MySQL Configuration

```bash
# Create database
CREATE DATABASE smartbranch_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user
CREATE USER 'smartbranch'@'localhost' IDENTIFIED BY 'strong_password';

# Grant privileges
GRANT ALL PRIVILEGES ON smartbranch_db.* TO 'smartbranch'@'localhost';
FLUSH PRIVILEGES;
```

### Run Migrations

```bash
php artisan migrate --force

# With seeding (optional)
php artisan migrate --seed --force
```

## ML Model Deployment

### Option 1: Separate FastAPI Server

```bash
cd FastAPI

# Create virtual environment
python3 -m venv venv
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt

# Run with Gunicorn
pip install gunicorn
gunicorn -w 4 -b 0.0.0.0:8000 main:app
```

### Option 2: Using systemd

Create `/etc/systemd/system/smartbranch-ml.service`:

```ini
[Unit]
Description=SmartBranch ML API
After=network.target

[Service]
Type=notify
User=www-data
WorkingDirectory=/var/www/smartbranch/FastAPI
ExecStart=/var/www/smartbranch/FastAPI/venv/bin/gunicorn -w 4 -b 0.0.0.0:8000 main:app
Restart=always

[Install]
WantedBy=multi-user.target
```

Enable service:

```bash
sudo systemctl daemon-reload
sudo systemctl enable smartbranch-ml
sudo systemctl start smartbranch-ml
```

## SSL/HTTPS Setup

### Using Let's Encrypt (Free)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Request certificate
sudo certbot certonly --nginx -d your-domain.com

# Auto-renewal
sudo certbot renew --dry-run
```

### Manual SSL Certificate

If using provided SSL certificate:

```bash
# Copy certificate files
sudo cp your-cert.crt /etc/ssl/certs/
sudo cp your-key.key /etc/ssl/private/

# Update Nginx configuration
# Update ssl_certificate and ssl_certificate_key paths
sudo systemctl reload nginx
```

## Monitoring & Maintenance

### Health Checks

```bash
# Check application health
curl https://your-domain.com/health

# Check ML API
curl http://ml-api-server:8000/docs

# Check database
php artisan db:monitor
```

### Backup Strategy

```bash
# Manual backup
php artisan backup:run

# Automated backup (daily at 2 AM)
0 2 * * * cd /var/www/smartbranch/Laravel && php artisan backup:run
```

### Log Management

```bash
# View application logs
tail -f storage/logs/laravel.log

# View system logs
journalctl -u smartbranch-ml -f
```

### Performance Monitoring

```bash
# Enable query logging
php artisan tinker
> \DB::enableQueryLog();

# View slow queries
php artisan queue:work --tries=3
```

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error

**Solution:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

#### 2. Database Connection Error

**Solution:**
```bash
# Verify credentials in .env
# Test connection
mysql -h DB_HOST -u DB_USERNAME -p DB_DATABASE

# Check MySQL service
sudo systemctl status mysql
```

#### 3. ML API Connection Failed

**Solution:**
```bash
# Check if FastAPI is running
curl http://ml-api-server:8000/docs

# Check firewall
sudo ufw allow 8000/tcp

# Check network
ping ml-api-server
```

#### 4. High Memory Usage

**Solution:**
```bash
# Optimize autoloader
composer dump-autoload --optimize

# Clear cache
php artisan cache:clear
php artisan view:clear

# Check memory limit
php -i | grep memory_limit
```

---

**Need Help?** Open an issue or check our documentation.
