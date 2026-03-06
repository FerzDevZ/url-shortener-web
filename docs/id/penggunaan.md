# Panduan Penggunaan

Bab ini membahas alur kerja esensial untuk mengelola aset digital Anda di dalam dasbor ShortLink.

## 🔗 Membuat Tautan Pertama Anda
Navigasikan ke bagian "Links" dan klik "Create New Link".
-   **URL Tujuan**: URL panjang yang ingin Anda singkat.
-   **Alias Kustom**: Opsional. Jika dikosongkan, kode alfanumerik yang aman akan dibuat secara otomatis.
-   **Perlindungan**: Anda dapat mengaktifkan perlindungan kata sandi dan mengatur tanggal kadaluwarsa untuk kampanye sementara.
-   **Pixel Pelacakan**: Masukkan ID GTM atau Facebook Pixel Anda untuk melacak peristiwa konversi pada halaman pengalihan.

## 👥 Ruang Kerja Kolaborasi (Workspaces)
Workspace memungkinkan beberapa pengguna untuk mengelola sekumpulan tautan secara bersama-sama.
1.  **Buat Workspace**: Buka menu "Tim" dan buat lingkungan baru (misal: "Divisi Marketing").
2.  **Tambah Anggota**: Undang pengguna melalui email. Mereka harus sudah terdaftar di platform.
3.  **Tentukan Peran**: Admin dapat mengelola anggota dan pengaturan workspace; Member dapat membuat dan mengelola tautan.
4.  **Visibilitas Bersama**: Tautan yang dibuat di dalam workspace dapat dilihat oleh semua anggota grup tersebut.

## 📊 Analitik & Pelaporan
ShortLink menyediakan wawasan real-time terhadap performa tautan Anda.
-   **Ringkasan Dasbor**: Lihat akumulasi data klik, tautan dengan performa terbaik, dan distribusi geografis pengunjung.
-   **Ekspor CSV**: Untuk analisis data yang mendalam, gunakan tombol "Export CSV" pada tampilan detail tautan tertentu. Ini menyediakan data mentah dari semua pengunjung unik.

## 🔌 Integrasi API
Sistem menyediakan API yang kuat untuk pembuatan tautan otomatis.
-   **Personal Access Tokens**: Buat token dari halaman "Settings".
-   **Endpoint**: Endpoint RESTful standar tersedia di `/api/v1/links`. Lihat dokumentasi pengembang untuk skema permintaan/tanggapan.
