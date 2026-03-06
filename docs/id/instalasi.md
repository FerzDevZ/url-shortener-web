# Instalasi & Spesifikasi

## Persyaratan Sistem
Untuk memastikan performa optimal, terutama saat menangani beberapa pixel pelacakan secara bersamaan dan pencatatan analitik, spesifikasi berikut sangat direkomendasikan.

### 💻 Spesifikasi Minimal
-   **Prosesor**: 1 vCPU (Dual-core atau lebih tinggi direkomendasikan).
-   **Memori (RAM)**: 2GB (Dibutuhkan agar Redis dan PHP-FPM berjalan lancar).
-   **Ruang Disk**: 500MB untuk file aplikasi (SSD direkomendasikan).
-   **Sistem Operasi**: Berbasis Linux (Ubuntu 22.04 LTS, Debian, atau RHEL) atau macOS.

### 🛠 Dependensi Teknis
-   **PHP**: Versi 8.2 atau 8.3.
-   **Web Server**: Nginx (Pilihan Utama) atau Apache.
-   **Database**: MySQL 8.0 atau MariaDB 10.6+.
-   **Cache/Queue**: Redis 6.0+.
-   **Node.js**: Versi 18+ (Untuk kompilasi aset frontend).

## Langkah Instalasi

1.  **Kloning Kode Sumber**
    ```bash
    git clone <url-repositori-anda>
    cd shortlink-project
    ```

2.  **Konfigurasi Lingkungan**
    Salin template dan sesuaikan variabel sesuai lingkungan Anda.
    ```bash
    cp .env.example .env
    ```
    Pastikan `REDIS_HOST`, `DB_DATABASE`, dan `APP_URL` terkonfigurasi dengan benar.

3.  **Instalasi Dependensi**
    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run build
    ```

4.  **Inisialisasi Database & Keamanan**
    ```bash
    php artisan key:generate
    php artisan migrate --force --seed
    php artisan storage:link
    ```

5.  **Setup Layanan**
    Pastikan antrean (queue worker) berjalan untuk menangani analitik:
    ```bash
    php artisan queue:work --queue=default --tries=3
    ```
