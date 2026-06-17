# 🧠 SmartBranch BI – Machine Learning API

Layanan Machine Learning berbasis **FastAPI** yang digunakan dalam proyek Capstone **SmartBranch BI – Nusantara Bites**.

API ini bertugas melakukan proses *forecasting* penjualan berdasarkan data historis dan menyediakan hasil prediksi yang dapat digunakan oleh aplikasi utama (Laravel) untuk mendukung pengambilan keputusan bisnis.

Repository ini dikelola pada branch terpisah (`API`) guna menerapkan arsitektur **Decoupled System**, sehingga pengembangan layanan AI dapat dilakukan secara independen dari aplikasi web utama.

---

## 📋 Daftar Isi

- [Arsitektur Sistem](#-arsitektur-sistem)
- [Development Environment](#-development-environment)
- [Production Deployment](#-production-deployment)
- [API Documentation](#-api-documentation)
- [Integrasi dengan Laravel](#-integrasi-dengan-laravel)
- [Server Requirements](#-server-requirements)

---

# 🏗️ Arsitektur Sistem

```text
Laravel Web Application
        │
        │ HTTP Request
        ▼
FastAPI Machine Learning Service
        │
        ▼
Scikit-Learn Models (.pkl)
```

### Komponen Utama

- **Laravel** → Frontend dan Business Logic.
- **FastAPI** → Machine Learning Service.
- **Scikit-Learn** → Engine prediksi penjualan.
- **Render** → Hosting API Production.
- **MySQL** → Penyimpanan data transaksi dan operasional.

---

# 💻 Development Environment

## 1. Ambil Branch API

Pastikan repository utama telah tersedia di komputer lokal.

```bash
git fetch origin
git checkout API
```

---

## 2. Membuat Virtual Environment

Disarankan menggunakan virtual environment agar dependensi proyek terisolasi.

```bash
python -m venv venv
```

### Aktivasi Virtual Environment

#### Windows (PowerShell)

```powershell
venv\Scripts\activate
```

#### Linux / macOS

```bash
source venv/bin/activate
```

---

## 3. Install Dependencies

```bash
pip install -r requirements.txt
```

---

## 4. Menjalankan API

```bash
uvicorn main:app --reload --port 8001
```

Server akan berjalan pada:

```text
http://localhost:8001
```

Dokumentasi Swagger UI:

```text
http://localhost:8001/docs
```

---

# ☁️ Production Deployment

API telah di-deploy menggunakan layanan cloud Render.

### Production URL

```text
https://api-ai-9g49.onrender.com
```

### Deployment Method

- GitHub Repository Integration
- Automatic Deployment (CI/CD)
- Branch: `API`

---

## ⚠️ Catatan Free Tier Render

Karena menggunakan paket gratis Render, terdapat beberapa keterbatasan:

### Sleep Mode

Server akan memasuki mode tidur apabila tidak menerima request dalam periode tertentu.

### Cold Start

Request pertama setelah server tidur dapat memerlukan waktu sekitar:

```text
30 – 50 detik
```

untuk memuat:

- Library Python
- FastAPI Service
- Machine Learning Models

Aplikasi Laravel telah dikonfigurasi untuk menangani kondisi ini melalui mekanisme timeout dan pesan notifikasi kepada pengguna.

---

# 📡 API Documentation

## Base URL

### Production

```text
https://api-ai-9g49.onrender.com
```

### Development

```text
http://127.0.0.1:8001
```

---

## Forecast Endpoint

### Request

```http
POST /api/forecast
```

### Headers

```http
Content-Type: application/json
```

### Request Body

```json
{
  "store_id": "SURABAYA",
  "product_id": "Nasi Goreng Spesial",
  "category": "FOOD",
  "type": "MAIN_COURSE",
  "unit_price": 45000.0,
  "bulan": 7,
  "tahun": 2026,
  "lag_1": 156.0,
  "lag_3": 145.5,
  "lag_6": 140.2,
  "rolling_3": 150.33,
  "rolling_6": 148.5
}
```

### Success Response

```json
{
  "status": "success",
  "prediction": 165
}
```

### Response Description

| Field | Type | Description |
|---------|---------|---------|
| status | string | Status proses prediksi |
| prediction | integer | Hasil prediksi kuantitas penjualan |

---

# 🔌 Integrasi dengan Laravel

Contoh pemanggilan API menggunakan Laravel HTTP Client.

```php
use Illuminate\Support\Facades\Http;

try {

    $apiUrl = env('AI_API_URL') . '/api/forecast';

    $response = Http::timeout(30)
        ->post($apiUrl, $payload);

    if ($response->successful()) {

        $result = $response->json();

        if ($result['status'] === 'success') {
            $prediction = $result['prediction'];
        }
    }

} catch (\Exception $e) {

    return back()->with(
        'error',
        'AI Service sedang melakukan inisialisasi. Silakan coba kembali beberapa saat lagi.'
    );
}
```

---

# 📦 Server Requirements

| Software | Version |
|-----------|-----------|
| Python | >= 3.10 |
| FastAPI | >= 0.100 |
| Uvicorn | >= 0.22 |
| Pandas | >= 2.0 |
| NumPy | Latest Stable |
| Scikit-Learn | 1.6.1 |

---

# 📂 Branch Information

| Branch | Deskripsi |
|----------|----------|
| main | Laravel Web Application |
| API | FastAPI Machine Learning Service |

---

# 📄 License

Dikembangkan sebagai bagian dari proyek Capstone **SmartBranch BI – Nusantara Bites** untuk tujuan pembelajaran, penelitian, dan demonstrasi implementasi Business Intelligence serta Machine Learning pada industri F&B.

---

© 2026 SmartBranch BI Team
