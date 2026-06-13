# 🚀 SmartBranch BI: For Nusantara Bites

## 1. Deskripsi Singkat Proyek
**SmartBranch BI** adalah sistem *Business Intelligence* yang dirancang untuk manajemen operasional F&B multi-cabang (Nusantara Bites). Sistem ini mengintegrasikan *dashboard* analitik dengan kecerdasan buatan (AI) untuk melakukan *Sales Forecasting* (prediksi penjualan bulan depan). Selain itu, sistem menggunakan logika *Demand-Driven MRP* (Material Requirements Planning) untuk membedah hasil tebakan AI menjadi proyeksi kebutuhan stok bahan baku secara otomatis dan akurat.

> **Catatan Arsitektur:** Proyek ini menggunakan arsitektur *Decoupled*. *Core system* (Laravel) berada di *branch* utama, sedangkan *AI Engine* (FastAPI) berada di *branch* `API`, dan model prediksi berada di *branch* `model`.

## 2. Petunjuk Setup Environment
Pastikan perangkat Anda sudah terinstal **PHP 8.x**, **Composer**, **Node.js & NPM**, **Python 3.x**, dan **MySQL**. Anda perlu menyiapkan dua folder berdampingan untuk menjalankan sistem ini.

**A. Setup Environment Backend (AI Engine - Python)**
1. Clone repositori ini di folder baru dan *checkout* ke *branch* `API`:
   ```bash
   git clone [https://github.com/MfuatH/CAPSTONE-PROJECT-SmartBranch-BI-For-Nusantara-Bites.git](https://github.com/MfuatH/CAPSTONE-PROJECT-SmartBranch-BI-For-Nusantara-Bites.git) ai-engine
   cd ai-engine
   git checkout API
   ```
2. Buat dan aktifkan *Virtual Environment* (opsional namun disarankan):
   ```bash
   python -m venv venv
   source venv/bin/activate  # Untuk Linux/Mac
   venv\Scripts\activate     # Untuk Windows
   ```
3. Install dependensi Python:
   ```bash
   pip install -r requirements.txt
   ```

**B. Setup Environment Frontend & Core API (Laravel)**
1. Clone kembali repositori ini di folder lain (sebelah folder `ai-engine`) untuk *branch* utama:
   ```bash
   git clone [https://github.com/MfuatH/CAPSTONE-PROJECT-SmartBranch-BI-For-Nusantara-Bites.git](https://github.com/MfuatH/CAPSTONE-PROJECT-SmartBranch-BI-For-Nusantara-Bites.git) core-system
   cd core-system
   ```
2. Install dependensi PHP dan Node.js:
   ```bash
   composer install
   npm install
   ```
3. Salin file konfigurasi dan *generate* kunci aplikasi:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Sesuaikan konfigurasi *database* Anda di file `.env`.

## 3. Tautan Model ML
Model Machine Learning untuk *Sales Forecasting* telah di-*training* dan dipisahkan penyimpanannya.

* **Tautan Unduh Model:** [https://github.com/MfuatH/CAPSTONE-PROJECT-SmartBranch-BI-For-Nusantara-Bites/tree/model](https://github.com/MfuatH/CAPSTONE-PROJECT-SmartBranch-BI-For-Nusantara-Bites/tree/model)
* **Cara Memuat (Load) Model:**
  Unduh file model `.pkl` dari tautan di atas. Kemudian, letakkan file tersebut ke dalam direktori `/models/` di dalam folder `ai-engine` (hasil *clone branch API* pada langkah 2A). API FastAPI akan otomatis membacanya.

## 4. Cara Menjalankan Aplikasi

**Langkah 1: Siapkan Database (Terminal Laravel)**
Di dalam folder `core-system`, jalankan migrasi tabel dan *seeder* untuk memuat 15.000 data transaksi simulasi dan skenario peringatan stok:
   ```bash
   php artisan migrate:fresh --seed
   ```

**Langkah 2: Compile Asset Frontend (Terminal Laravel)**
Lakukan *build* pada TailwindCSS dan aset *frontend* lainnya:
   ```bash
   npm run build
   ```
*(Catatan: Anda juga bisa menggunakan `npm run dev` jika ingin melakukan perubahan UI secara real-time).*

**Langkah 3: Jalankan Server AI (Terminal FastAPI)**
Di dalam folder `ai-engine`, jalankan server Uvicorn:
   ```bash
   uvicorn main:app --reload --port 8001
   ```

**Langkah 4: Jalankan Server Laravel (Terminal Laravel Baru)**
Di dalam folder `core-system`, jalankan server PHP:
   ```bash
   php artisan serve
   ```

**Langkah 5: Akses Aplikasi**
Buka browser dan kunjungi `http://127.0.0.1:8000`. 
* **Email:** admin@nusantara.com
* **Password:** password123