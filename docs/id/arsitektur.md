# 2. Arsitektur dan Desain Teknis

## 2.1 Arsitektur Sistem
ShortLink dibangun menggunakan arsitektur berlapis yang memprioritaskan kecepatan mesin pengalihan di atas beban administratif lainnya.

### 2.1.1 Komponen Inti
*   **Kerangka Kerja (Framework)**: Laravel 11.x (PHP 8.2+), menyediakan sistem ORM yang skalabel dan middleware keamanan bawaan.
*   **Lapisan Persistensi**: MySQL/MariaDB untuk penyimpanan data relasional (Tautan, Pengguna, Ruang Kerja).
*   **Caching Berkecepatan Tinggi**: Redis 6.2+ digunakan sebagai perantara untuk menyimpan metadata tautan, yang secara signifikan mengurangi beban database pada saat trafik puncak.
*   **Pemrosesan Asinkron**: Background worker menangani tugas-tugas berat seperti pencatatan analitik klik dan pencarian data GeoIP.

## 2.2 Modul Fungsional

### 2.2.1 Mesin Pengalihan (Redirection Engine)
Logika pengalihan dipisahkan dari database analitik utama. Saat tautan singkat diakses:
1.  Sistem melakukan kueri ke Redis untuk mencari URL tujuan.
2.  Jika ditemukan, pengguna segera dialihkan.
3.  Tugas asinkron (job) dikirim ke antrean untuk mencatat detail kunjungan tanpa menghambat pengguna.

### 2.2.2 Sinkronisasi Ruang Kerja (Workspace)
Fitur ruang kerja multi-pengguna menggunakan relasi *many-to-many* yang dinormalisasi. Hal ini memungkinkan struktur organisasi yang kompleks di mana pengguna dapat berpindah antara lingkungan kerja pribadi dan kolaboratif dengan lancar.

## 2.3 Protokol Keamanan
*   **Token Akses**: Akses API dikelola melalui token yang dienkripsi secara kriptografis (Laravel Sanctum).
*   **Integritas Data**: Aturan *foreign key* dengan kebijakan *cascade/null-on-delete* menjamin konsistensi status database.
*   **Mitigasi Bot**: Alur pengalihan menyertakan mekanisme pengecekan bot pasif untuk menyaring trafik buatan dari laporan analitik.
