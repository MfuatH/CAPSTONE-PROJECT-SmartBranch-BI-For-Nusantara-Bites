# SmartBranch BI - Machine Learning API

Repository ini berisi layanan Machine Learning API (berbasis FastAPI) untuk proyek Capstone SmartBranch BI. Layanan ini dipisahkan menggunakan sistem *worktree* / *branch* khusus agar tidak bercampur dengan kode Back-End utama.

## 🚀 Panduan Instalasi dan Penggunaan

Ikuti langkah-langkah di bawah ini untuk mengatur dan menjalankan API secara lokal di mesin Anda.

### 1. Ambil Branch ke Lokal
Pastikan Anda sudah berada di dalam repository utama proyek, lalu ambil dan pindah ke branch `API` untuk mendapatkan kode terbaru:

```bash
git fetch origin
git checkout API
```

### 2. Buat dan Aktifkan Virtual Environment (Disarankan)
Untuk memastikan *library* yang diinstal tidak bentrok dengan proyek Python lain, sangat disarankan untuk menggunakan *virtual environment* (`venv`).

**Membuat venv:**
```bash
python -m venv venv
```

**Mengaktifkan venv:**
* **Windows (PowerShell):**
  ```powershell
  venv\Scripts\activate
  ```
* **Mac/Linux:**
  ```bash
  source venv/bin/activate
  ```
*(Catatan: Pastikan indikator `(venv)` sudah muncul di terminal Anda sebelum lanjut ke langkah berikutnya).*

### 3. Install Dependencies
Setelah *virtual environment* aktif, instal semua *library* yang dibutuhkan sesuai dengan daftar di `requirements.txt`:

```bash
pip install -r requirements.txt
```

### 4. Jalankan Server API
Terakhir, jalankan server lokal menggunakan Uvicorn. Server akan berjalan di port `8001` dan memiliki fitur *auto-reload* (server akan otomatis *restart* jika ada perubahan pada kode).

```bash
uvicorn main:app --port 8001 --reload
```

Jika berhasil, API akan berjalan. Anda dapat mengakses dokumentasi interaktif (Swagger UI) langsung dari browser melalui tautan berikut:
👉 **http://localhost:8001/docs**