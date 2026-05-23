# Data Engineering Pipeline: Nusantara Coffee Shop Sales

## Deskripsi Branch
Proyek ini merupakan bagian tahap awal (Data Preparation & Engineering) dari sistem *Machine Learning* untuk *Forecasting* Penjualan Restoran/Kafe. 

Dataset mentah awal (Global Coffee Shop Sales) ditransformasi melalui proses **ETL (Extract, Transform, Load)** menjadi dataset lokal bergaya "Nusantara". Proyek ini mencakup *Data Cleaning*, *Feature Engineering*, dan *Chronological Data Splitting* agar siap dikonsumsi langsung oleh algoritma *Time-Series Forecasting*.

---

## Pembagian Peran (MLOps Approach)
Proyek ini mengadopsi pemisahan tugas agar *pipeline* berjalan efisien:
- **Data Engineer (Tahap 1):** Menangani ETL, standarisasi data, ekstraksi fitur waktu, dan pembagian data train/test.
- **ML & Deployment Engineer (Tahap 2):** Menerima data bersih, melatih model peramalan, dan membungkusnya ke dalam API/Backend.

---

## Gambaran Isi Dataset (Nusantara Version)

Dataset yang telah dibersihkan ini mensimulasikan riwayat transaksi operasional sebuah kafe/restoran lokal yang beroperasi di Indonesia. 

### Lokasi Cabang (Store Locations)
Data transaksi telah didistribusikan secara acak dan merata ke dalam 5 cabang operasional:
1. **Jakarta Pusat**
2. **Bandung**
3. **Semarang**
4. **Yogyakarta**
5. **Surabaya**

### Struktur Menu & Harga
Terdapat sekitar 40+ varian menu yang dibagi ke dalam beberapa kategori utama, disesuaikan dengan selera pasar Nusantara. Berikut adalah ringkasan kategori beserta kisaran harga (dalam Rupiah):

*   **Makanan Utama Nusantara (Rp 38.000 - Rp 55.000)**
    *   *Contoh:* Nasi Goreng Kampung, Nasi Bakar Ayam Kemangi, Mie Goreng Jawa Spesial, Soto Ayam Ambengan, Rawon Daging Sapi.
*   **Makanan Ringan & Jajanan Pasar (Rp 8.000 - Rp 35.000)**
    *   *Contoh:* Lemper Ayam Bakar, Pastel Renyah, Pisang Bolen Coklat Keju, Roti Bakar Bandung Spesial, Singkong Keju Merekah.
*   **Kopi Lokal & Espresso (Rp 15.000 - Rp 35.000)**
    *   *Contoh:* Es Kopi Susu Gula Aren, Kopi Sanger, Kopi Tubruk, Kopi Tarik, Cappuccino, Aceh Gayo Roast.
*   **Biji Kopi Nusantara / Whole Beans (Rp 95.000 - Rp 145.000)**
    *   *Contoh:* Sumatra Mandheling, Flores Bajawa, Papua Wamena.
*   **Minuman Tradisional, Cokelat & Teh (Rp 10.000 - Rp 35.000)**
    *   *Contoh:* Wedang Uwuh, Bajigur, Cokelat Panas Jawa, Teh Tarik, Teh Kampul Solo, Es Teh Manis.

---

## Fitur Tambahan untuk ML (Feature Engineering)
Untuk mendukung performa model *Time-Series Forecasting*, dataset ini telah dilengkapi dengan fitur-fitur ekstra yang diekstrak dari waktu transaksi:
- `transaction_datetime`: Penggabungan tanggal dan jam transaksi untuk presisi urutan waktu.
- `bulan` & `hari`: Ekstraksi numerik bulan dan hari.
- `jam`: Ekstraksi jam sibuk operasional restoran.
- `is_weekend`: Indikator biner (1 untuk Sabtu/Minggu, 0 untuk hari biasa).
- Seluruh data kategorikal (Cabang, Kategori Produk) telah diubah menjadi numerik menggunakan teknik *One-Hot Encoding*.

---

## Panduan untuk ML Engineer (Tahap Selanjutnya)

Data sudah siap latih dan berada di folder `Dataset/`. Berikut hal yang perlu diperhatikan saat melakukan *load* data:

1. Data sudah dipisah menjadi `X_train`, `X_test`, `y_train`, dan `y_test` menggunakan *Chronological Splitting* (`shuffle=False`) untuk menghindari kebocoran data dari masa depan ke masa lalu.
2. **Kolom Waktu:** Di dalam file `X_train` dan `X_test`, terdapat kolom `transaction_datetime`. Harap gunakan kolom ini sebagai **Index** atau parameter tanggal (misal kolom `ds` di algoritma Prophet) saat melatih model *forecasting*.
3. Data **tidak memiliki nilai NULL**. Target prediksi (`y`) adalah kolom `transaction_qty`.

Silahkan sesuaikan berdasarkan kebutuhan👍