# SaaS Pembuatan Website Berbasis Template

## Gambaran Umum Sistem
Sistem ini adalah **Software as a Service (SaaS)** yang memungkinkan pengguna untuk membuat website dengan mudah menggunakan template yang tersedia. Platform ini dibangun dengan **Laravel 12** sebagai framework utama dan berfokus pada content management dinamis serta templating yang fleksibel.

---

## Konsep Dasar
Platform ini memungkinkan user terdaftar untuk:

- Memilih template website dari katalog yang tersedia
- Mengisi konten dinamis (nama perusahaan, slogan, galeri foto, dll)
- Melihat preview hasil website sebelum publikasi
- Melakukan pembayaran melalui integrasi Payment Gateway
- Menunggu proses aktivasi dari Admin
- Menerima notifikasi email ketika website sudah aktif dan bisa diakses

---

## Arsitektur Sistem

### 1. Template Management (Inti Sistem)
- Setiap template disimpan dalam struktur folder terpisah di  
  `resources/views/templates/{template_slug}/`
- Terdapat file `config.json` yang mendefinisikan field konten yang dibutuhkan oleh template tersebut.

### 2. Content Management Dinamis
- Sistem membaca `config.json` dari template yang dipilih dan secara otomatis men-generate form dinamis sesuai kebutuhan template.
- Data konten disimpan dalam format JSON di database pada tabel `website_contents`.
- Pendekatan ini memungkinkan fleksibilitas untuk berbagai jenis konten tanpa perlu mengubah struktur database.

### 3. Preview Engine & Payment Integration
- Preview engine memungkinkan user melihat hasil website sebelum publikasi melalui route:  
  `/preview/{website_content_id}`
- Setelah user puas dengan preview, mereka dapat melakukan pembayaran melalui integrasi Payment Gateway.

### 4. Activation Queue & Background Jobs
- Setelah pembayaran berhasil, sistem menggunakan **Laravel Queue** untuk menjalankan `ActivateWebsiteJob` dengan delay 6 jam.
- Task ini akan:
  - Mengaktifkan website
  - Mengirim notifikasi email kepada user

---

## Workflow Pengguna

1. **Register + Login & Template Selection**  
   User register & login dan memilih template dari katalog.

2. **Content Input**  
   Mengisi form konten dinamis yang di-generate sesuai template.

3. **Preview & Payment**  
   Melihat preview, jika sudah sesuai melakukan checkout dan pembayaran.

4. **Processing**  
   Sistem menerima notifikasi pembayaran dan mengubah status menjadi *paid*.

5. **Activation**  
   Background job dijalankan setelah 6 jam untuk mengaktifkan website.

6. **Notification**  
   User menerima email notifikasi dan dapat mengakses website di URL yang diberikan.

---

## Keunggulan Sistem

- **Scalable**  
  Mudah menambah template baru hanya dengan membuat folder dan `config.json`.

- **Dinamis**  
  Content management yang fleksibel sesuai kebutuhan setiap template.

- **Terstruktur**  
  Proses pembayaran, aktivasi, dan notifikasi yang terkelola dengan baik.

- **Laravel 12**  
  Menggunakan framework yang solid untuk pengembangan berkelanjutan.

---

## Ringkasan
Sistem ini dirancang untuk memberikan solusi yang simpel namun powerful bagi user yang ingin memiliki website profesional tanpa perlu memahami coding, dengan model bisnis SaaS yang sustainable.