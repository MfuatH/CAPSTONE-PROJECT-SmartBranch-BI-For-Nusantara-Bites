# Quick Start Guide ⚡

Get SmartBranch BI up and running in 5 minutes!

## Prerequisites Check

Before starting, verify you have:

```bash
# Check PHP
php --version
# Should show: PHP 8.3+

# Check Node.js
node --version
npm --version
# Should show: v18+

# Check Python
python3 --version
# Should show: Python 3.8+

# Check Composer
composer --version
# Should show: Composer 2.x

# Check MySQL
mysql --version
# Should show: MySQL 8.0+
```

---

## ⚡ Fast Setup (< 5 minutes)

### Option 1: All-in-One Setup Script

**For Windows:**

```bash
# Clone repository
git clone https://github.com/yourusername/smartbranch-bi.git
cd smartbranch-bi

# Run setup script
.\setup.bat
```

**For macOS/Linux:**

```bash
# Clone repository
git clone https://github.com/yourusername/smartbranch-bi.git
cd smartbranch-bi

# Make script executable
chmod +x setup.sh

# Run setup script
./setup.sh
```

### Option 2: Manual 5-Minute Setup

#### Step 1: Clone & Install Backend (2 minutes)

```bash
cd Laravel

# Install PHP dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Install Node dependencies
npm install --legacy-peer-deps

# Build frontend
npm run build
```

#### Step 2: Database Setup (1 minute)

**Configure .env:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartbranch_db
DB_USERNAME=root
DB_PASSWORD=
```

**Run migrations:**

```bash
php artisan migrate --seed
```

#### Step 3: Start Services (2 minutes)

```bash
# Terminal 1: Laravel App
php artisan serve

# Terminal 2: Frontend Build (optional in dev)
npm run dev

# Terminal 3: ML API
cd ../FastAPI
python -m venv venv

# Activate venv
# Windows:
venv\Scripts\activate
# macOS/Linux:
source venv/bin/activate

# Install and run ML API
pip install -r requirements.txt
uvicorn main:app --reload
```

**Done!** 🎉 Visit `http://localhost:8000`

---

## 🔐 First Login

### Default Credentials

| Field | Value |
|-------|-------|
| **Email** | admin@smartbranch.local |
| **Password** | password123 |

⚠️ **CHANGE THIS IMMEDIATELY IN PRODUCTION!**

### Create Admin User

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Your Name',
    'email' => 'your@email.com',
    'password' => bcrypt('secure_password_123'),
    'role' => 'admin',
    'is_active' => true
]);
```

---

## 📊 Verify Installation

### Checklist

After setup, verify everything works:

```bash
✅ Laravel application loads at http://localhost:8000
✅ Login works with admin credentials
✅ Dashboard displays without errors
✅ Database is populated with tables
✅ FastAPI ML service responds at http://localhost:8000/docs
✅ File upload works (test in Transaction page)
✅ Navigation menu is accessible
```

### Test API Endpoints

**Test Laravel routes:**

```bash
# Check dashboard
curl http://localhost:8000/dashboard

# Check forecast endpoint
curl -X POST http://localhost:8000/run-ai-forecast \
  -H "Content-Type: application/json" \
  -d '{"month": 7, "year": 2026}'
```

**Test ML API:**

```bash
curl -X POST "http://localhost:8000/api/forecast" \
  -H "Content-Type: application/json" \
  -d '{
    "store_id": "SURABAYA",
    "product_id": "PROD001",
    "category": "FOOD",
    "type": "MAIN_COURSE",
    "unit_price": 45000,
    "bulan": 7,
    "tahun": 2026,
    "lag_1": 156.0,
    "lag_3": 145.5,
    "lag_6": 140.2,
    "rolling_3": 150.3,
    "rolling_6": 148.5
  }'
```

---

## 🚀 Next Steps

1. **Explore Dashboard**
   - Visit http://localhost:8000/dashboard
   - Check sales metrics and visualizations

2. **Run Forecasting**
   - Go to Dashboard
   - Click "Generate Forecast"
   - View predictions for next month

3. **Upload Data**
   - Go to Sales History > Import Dataset
   - Upload an Excel file with transactions
   - System will process and display data

4. **Check Inventory**
   - Navigate to Stock Inventory
   - Review raw materials and stock levels
   - View AI-generated recommendations

5. **Customize Settings**
   - Go to Settings page
   - Configure system parameters
   - Setup user accounts

---

## 🐛 Common Issues & Quick Fixes

### Issue: "Class App\Models\User not found"

**Fix:**
```bash
composer dump-autoload
php artisan cache:clear
```

### Issue: "Database connection error"

**Fix:**
```bash
# Check MySQL is running
mysql -u root -p

# Verify .env credentials
cat .env | grep DB_

# Re-run migrations
php artisan migrate --force
```

### Issue: "No such file or directory" for .env

**Fix:**
```bash
cd Laravel
cp .env.example .env
php artisan key:generate
```

### Issue: "Port 8000 already in use"

**Fix:**
```bash
# Use different port
php artisan serve --port=8001

# Or kill process
# Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F

# macOS/Linux:
lsof -i :8000
kill -9 <PID>
```

### Issue: "ML API connection refused"

**Fix:**
```bash
# Verify FastAPI is running
curl http://localhost:8000/docs

# Check .env ML_API_URL
cat .env | grep ML_API

# Make sure port 8000 (or configured) is open
```

---

## 📚 Documentation Files

- **[README.md](../README.md)** - Complete project documentation
- **[DEPLOYMENT.md](../DEPLOYMENT.md)** - Production deployment guide
- **[CONTRIBUTING.md](../CONTRIBUTING.md)** - Contribution guidelines
- **[API_DOCUMENTATION.md](../API_DOCUMENTATION.md)** - API endpoints reference
- **[ML_PIPELINE_DOCUMENTATION.md](../ML_PIPELINE_DOCUMENTATION.md)** - ML model details

---

## 💡 Tips for Development

### Enable Debug Mode

```env
APP_DEBUG=true
APP_ENV=local
```

### Watch Asset Changes

```bash
npm run dev
```

### Run Tests

```bash
php artisan test
```

### Database Seeders

```bash
# Run seeder
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=ProductSeeder
```

### Clear Cache

```bash
# Clear all caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Or one command
php artisan optimize:clear
```

---

## 📁 Project Structure Overview

```
smartbranch-bi/
├── Laravel/                    # Web application
│   ├── app/                   # Application code
│   ├── resources/views/       # Blade templates
│   ├── routes/web.php         # Web routes
│   ├── database/              # Migrations & seeders
│   ├── public/                # Public assets
│   ├── .env                   # Configuration (create from .env.example)
│   └── artisan                # CLI tool
├── FastAPI/                   # ML API
│   ├── main.py               # FastAPI application
│   ├── models/               # Trained ML models
│   └── requirements.txt       # Python dependencies
└── README.md                  # Full documentation
```

---

## 🎯 What's Next?

### Short-term
- [ ] Customize dashboard with your brand colors
- [ ] Upload your actual sales data
- [ ] Run forecasting to see predictions
- [ ] Test inventory recommendation feature
- [ ] Invite team members

### Medium-term
- [ ] Setup SSL/HTTPS for security
- [ ] Deploy to production server
- [ ] Configure email notifications
- [ ] Setup automated backups
- [ ] Monitor application performance

### Long-term
- [ ] Integrate with POS system
- [ ] Add mobile app support
- [ ] Setup data warehouse
- [ ] Implement advanced analytics
- [ ] Custom report generation

---

## 📞 Getting Help

- 📖 **Documentation**: Check [README.md](../README.md)
- 🐛 **Issues**: [Report bug or feature request](https://github.com/yourusername/smartbranch-bi/issues)
- 💬 **Discussions**: [Community discussions](https://github.com/yourusername/smartbranch-bi/discussions)
- 📧 **Email**: smartbranch.support@example.com

---

## ✅ Checklist Before Going to Production

- [ ] Changed default admin password
- [ ] Configured proper database credentials
- [ ] Setup SSL/HTTPS certificate
- [ ] Enabled backups and disaster recovery
- [ ] Configured email service
- [ ] Set environment to `APP_ENV=production`
- [ ] Disabled debug mode (`APP_DEBUG=false`)
- [ ] Tested all features on staging
- [ ] Documented deployment procedure
- [ ] Setup monitoring and alerting

---

## 🎉 Congratulations!

You now have a fully functional SmartBranch BI installation!

**Next**: Check out the [README.md](../README.md) for detailed documentation on all features.

---

**Happy analyzing! 📊**

*Last Updated: June 2026*
