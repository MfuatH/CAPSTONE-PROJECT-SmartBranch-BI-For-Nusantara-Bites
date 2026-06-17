# 🍜 SmartBranch BI for Nusantara Bites

SmartBranch BI adalah platform **Business Intelligence** berbasis web yang dikembangkan untuk membantu manajemen operasional cabang restoran Nusantara Bites. Sistem menyediakan dashboard analitik, monitoring inventaris, pengelolaan transaksi, serta fitur prediksi penjualan menggunakan Machine Learning.

Arsitektur sistem menggunakan pendekatan **Decoupled Architecture**, di mana aplikasi web dan layanan Machine Learning berjalan secara terpisah:

- **Laravel** → Dashboard, inventaris, transaksi, dan manajemen data.
- **FastAPI** → API Machine Learning untuk melakukan forecasting penjualan.

---

# 🌐 Live Demo

### Web Application

```text
https://nusantara-bites.infinityfree.io
```

### Machine Learning API

```text
https://api-ai-9g49.onrender.com
```

### API Documentation (Swagger)

```text
https://api-ai-9g49.onrender.com/docs
```

---

# 🛠 Teknologi yang Digunakan

## Web Application

- Laravel 12
- PHP 8+
- MySQL
- Tailwind CSS
- JavaScript

## Machine Learning Service

- FastAPI
- Python 3.10+
- Scikit-Learn
- Pandas
- NumPy

---

# 📁 Struktur Repository

```text
smartbranch-bi/
│
├── laravel/     # Web Application
├── fastapi/     # Machine Learning API
│
└── README.md
```

---

# 🔗 Model Machine Learning

Model dan artefak Machine Learning yang digunakan dalam proyek ini dapat diakses melalui:

```text
https://drive.google.com/drive/folders/15Hmj34tfWuvpDlfUgpI3pEySUIBMdU-3
```

---

# 💻 Setup Environment (Local Development)

## Prasyarat

Pastikan perangkat telah terinstal:

- PHP 8+
- Composer
- Node.js
- MySQL
- Python 3.10+

---

## 1. Clone Repository

```bash
git clone https://github.com/username/smartbranch-bi.git
cd smartbranch-bi
```

---

## 2. Setup Laravel Application

Masuk ke direktori Laravel:

```bash
cd laravel
```

Install dependency:

```bash
composer install
npm install
npm run build
```

Buat file environment:

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_DATABASE=smartbranch
DB_USERNAME=root
DB_PASSWORD=

AI_API_URL=http://127.0.0.1:8001
```

Jalankan migrasi database:

```bash
php artisan migrate --seed
```

---

## 3. Setup FastAPI Machine Learning Service

Buka terminal baru dan masuk ke direktori FastAPI:

```bash
cd fastapi
```

Buat virtual environment:

```bash
python -m venv venv
```

Aktifkan virtual environment:

### Windows

```bash
venv\Scripts\activate
```

### Linux / macOS

```bash
source venv/bin/activate
```

Install dependency:

```bash
pip install -r requirements.txt
```

---

# 🚀 Menjalankan Aplikasi

Karena menggunakan arsitektur terpisah, Laravel dan FastAPI harus dijalankan secara bersamaan.

## Terminal 1 — Laravel Web Application

```bash
cd laravel
php artisan serve
```

Aplikasi web dapat diakses melalui:

```text
http://localhost:8000
```

---

## Terminal 2 — FastAPI Machine Learning Service

Pastikan virtual environment aktif.

```bash
cd fastapi
uvicorn main:app --reload --port 8001
```

Machine Learning API dapat diakses melalui:

```text
http://localhost:8001
```

Swagger Documentation:

```text
http://localhost:8001/docs
```

---

# ☁️ Deployment

## Laravel Web Application

Platform deployment:

```text
InfinityFree
```

Konfigurasi production pada file `.env`:

```env
APP_ENV=production
APP_DEBUG=false

AI_API_URL=https://api-ai-9g49.onrender.com
```

Langkah umum deployment:

1. Upload source code Laravel ke hosting.
2. Import database MySQL ke server.
3. Sesuaikan konfigurasi `.env`.
4. Jalankan migrasi jika diperlukan.
5. Pastikan URL API mengarah ke server FastAPI production.

---

## FastAPI Machine Learning Service

Platform deployment:

```text
Render
```

Production Endpoint:

```text
https://api-ai-9g49.onrender.com
```

### Build Command

```bash
pip install -r requirements.txt
```

### Start Command

```bash
uvicorn main:app --host 0.0.0.0 --port 10000
```

> **Catatan:** Render Free Tier dapat memasuki mode standby setelah periode tidak aktif. Request pertama setelah standby mungkin membutuhkan waktu lebih lama karena proses cold start.

---

# 👥 Tim Pengembang

- Backend Development
- Machine Learning Development
- Frontend Development
- Project Management

---

© 2026 SmartBranch BI Team
