# Dataset Transformasi: Nusantara Coffee Shop Sales

## Sumber Dataset (Original Source)
Dataset ini merupakan hasil modifikasi dan transformasi dari dataset publik:
* **Judul Asli:** [Coffee Shop Sales](https://www.kaggle.com/datasets/ahmedabbas757/coffee-sales)
* **Platform:** Kaggle
* **Pemilik Data Asli:** Maven Analytics (diunggah oleh Ahmed Abbas)

## Deskripsi Branch
Proyek di *branch* ini berfokus murni pada tahapan **Data Preparation & Engineering**. Tujuan utamanya adalah melakukan proses **ETL (Extract, Transform, Load)** untuk menyulap dataset mentah bergaya *western*/global tersebut menjadi dataset lokal bergaya "Nusantara", lengkap dengan ekstraksi fitur yang siap dikonsumsi langsung oleh algoritma *Time-Series Forecasting*.

---

## Proses Transformasi Data
Data mentah dari Kaggle diproses menggunakan Python (Pandas & NumPy) dengan rincian perubahan sebagai berikut:

### 1. Penyesuaian Lokasi Cabang (Store Locations)
* **Apa yang diubah:** Dataset asli menggunakan ID lokasi yang terbatas.
* **Bagaimana:** Melakukan *random assignment* menggunakan `numpy.random.choice` untuk mendistribusikan seluruh baris transaksi secara acak namun merata ke dalam 5 kota di Indonesia: **Jakarta Pusat, Bandung, Semarang, Yogyakarta,** dan **Surabaya**.

### 2. Modifikasi Master Menu & Konversi Harga
* **Apa yang diubah:** Mengganti puluhan menu Barat (seperti *Scone*, *Biscotti*, *Premium Drip*) beserta harganya menjadi menu jajanan dan kopi khas Indonesia.
* **Bagaimana:** Menggunakan teknik *Dictionary Mapping*. Sekitar 80 nama produk lama dipetakan menjadi 40+ produk Nusantara. Produk asli yang tidak masuk dalam *mapping* secara otomatis di-*drop* (dihapus transaksinya). Harga dasar yang awalnya menggunakan Dollar diubah sepenuhnya menjadi taksiran harga Rupiah (IDR) standar kafe lokal.

### 3. Ekstraksi Waktu (Feature Engineering)
* **Apa yang diubah:** Mengoptimalkan format waktu agar model *Machine Learning* bisa memahami pola penjualan temporal.
* **Bagaimana:** * Menggabungkan `transaction_date` dan `transaction_time` menjadi satu kolom utuh `transaction_datetime`.
    * Mengekstrak nilai numerik menjadi kolom baru: `bulan`, `hari`, `jam` (untuk melihat pola *rush hour*), dan `is_weekend` (1 untuk Sabtu/Minggu, 0 untuk hari biasa).

### 4. Standarisasi Format ML (Encoding & Splitting)
* **Apa yang diubah:** Memastikan dataset 100% bisa dibaca oleh algoritma prediktif.
* **Bagaimana:**
    * Membuang nilai *Null* dan kolom *identifier* berupa *string* (teks mentah).
    * Melakukan *One-Hot Encoding* (`pd.get_dummies`) pada kolom lokasi cabang dan kategori produk sehingga berubah menjadi variabel biner (0/1).
    * Memecah data menjadi data latih dan data uji menggunakan *Chronological Splitting* (`shuffle=False`) untuk menghindari *data leakage* (kebocoran data masa depan ke masa lalu).

---

## Gambaran Isi Menu Nusantara

Menu telah disederhanakan ke dalam beberapa kategori utama dengan kisaran harga (IDR) sebagai berikut:

* **Makanan Utama Nusantara (Rp 38.000 - Rp 55.000)**
    * *Contoh:* Nasi Goreng Kampung, Nasi Bakar Ayam Kemangi, Mie Goreng Jawa Spesial, Soto Ayam Ambengan, Rawon Daging Sapi.
* **Makanan Ringan & Jajanan Pasar (Rp 8.000 - Rp 35.000)**
    * *Contoh:* Lemper Ayam Bakar, Pastel Renyah, Pisang Bolen Coklat Keju, Roti Bakar Bandung Spesial, Singkong Keju Merekah.
* **Kopi Lokal & Espresso (Rp 15.000 - Rp 35.000)**
    * *Contoh:* Es Kopi Susu Gula Aren, Kopi Sanger, Kopi Tubruk, Kopi Tarik, Cappuccino, Aceh Gayo Roast.
* **Biji Kopi Nusantara / Whole Beans (Rp 95.000 - Rp 145.000)**
    * *Contoh:* Sumatra Mandheling, Flores Bajawa, Papua Wamena.
* **Minuman Tradisional, Cokelat & Teh (Rp 10.000 - Rp 35.000)**
    * *Contoh:* Wedang Uwuh, Bajigur, Cokelat Panas Jawa, Teh Tarik, Teh Kampul Solo, Es Teh Manis.

---

## Format Output (Dataset Final)

Hasil dari seluruh proses di atas tersimpan di dalam folder `Dataset/` dan telah dipecah menjadi:
1. `X_train.csv` (Fitur Latih - Masa lalu)
2. `X_test.csv` (Fitur Uji - Masa depan)
3. `y_train.csv` (Target Latih)
4. `y_test.csv` (Target Uji)

**Catatan untuk Pemodelan:** Di dalam file `X_train` dan `X_test`, terdapat kolom `transaction_datetime`. Harap gunakan kolom ini sebagai **Index** atau parameter tanggal referensi saat melatih algoritma *forecasting* Anda. Target prediksi (`y`) pada proyek ini adalah total pesanan atau `transaction_qty`.

Silahkan sesuaikan berdasarkan kebutuhan👍