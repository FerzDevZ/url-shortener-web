# 3. Panduan Instalasi dan Deployment

## 3.1 Spesifikasi Perangkat Keras Minimal
Untuk menjamin operasional yang stabil di lingkungan produksi, khususnya dalam menangani beberapa pixel pelacakan secara bersamaan dan pencatatan analitik, diperlukan spesifikasi berikut:
*   **CPU**: 1 vCPU (Dual-core direkomendasikan untuk background worker).
*   **Memori**: 2GB RAM minimum (Untuk mengakomodasi overhead PHP-FPM, Redis, dan MySQL).
*   **Penyimpanan**: 500MB ruang disk yang tersedia (Tidak termasuk log aplikasi dan aset yang diunggah pengguna).

## 3.2 Daftar Periksa Deployment
1.  **Persiapan Lingkungan**: Pastikan PHP 8.2+, MySQL 8.0+, dan Redis 6.0+ telah terinstal.
2.  **Akuisisi Kode Sumber**:
    ```bash
    git clone <repository-url>
    cd project-root
    ```
3.  **Resolusi Dependensi**:
    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run build
    ```
4.  **Inisialisasi Sistem**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --force --seed
    php artisan storage:link
    ```
5.  **Konfigurasi Layanan**:
    *   Konfigurasikan pengelola proses (seperti Supervisor) untuk menjalankan `php artisan queue:work` secara berkelanjutan untuk pemrosesan analitik.
    *   Pastikan konfigurasi Nginx/Apache mengarah ke direktori `public/`.
