# Arsitektur & Tech Stack

## Arsitektur Sistem
ShortLink mengikuti arsitektur layanan terpisah (decoupled service) yang dirancang untuk ketersediaan tinggi dan performa optimal.

### 🔌 Tech Stack
-   **Backend**: PHP 8.2+ dengan framework Laravel 11 (LTS-ready).
-   **Database**: MySQL 8.0/MariaDB menggunakan pengindeksan optimal untuk pengambilan tautan yang cepat.
-   **Caching**: Redis 6.2+ untuk penyimpanan sesi dan caching pengalihan kecepatan tinggi.
-   **Pemrosesan**: Pemrosesan antrean asinkron (Queue) melalui Redis untuk pencatatan data analitik tanpa memengaruhi kecepatan pengalihan pengguna.
-   **Frontend**: Sistem Vanilla CSS kustom dengan bundling Vite, memastikan pengalaman pengguna yang ringan namun tetap premium.

## Optimasi Performa
-   **Strategi Caching**: Setiap tautan singkat yang aktif disimpan dalam cache Redis pada permintaan pertama. Pengalihan berikutnya melewati database utama sepenuhnya.
-   **Antrean (Queueing)**: Pengumpulan data analitik (GeoIP, Browser fingerprinting) ditangani di latar belakang oleh worker khusus.
-   **Manajemen Sumber Daya**: Pembersihan otomatis (Garbage collection) untuk log yang kadaluwarsa dan aset media yang tidak digunakan.

## Keamanan & Privasi
-   **Perlindungan**: Perlindungan bawaan terhadap serangan brute-force melalui pembatasan laju (rate limiting).
-   **Privasi**: Analitik yang patuh terhadap privasi (Tidak ada penyimpanan alamat IP lengkap; hanya geolokasi yang dianonymize).
-   **Redundansi**: Operasi database transaksional untuk memastikan integritas data selama modifikasi workspace tim.
