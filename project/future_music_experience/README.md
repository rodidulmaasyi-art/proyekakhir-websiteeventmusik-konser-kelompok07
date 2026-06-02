# Future Music Experience

Website fullstack festival musik futuristik berbasis **PHP + MySQL + JavaScript + Tailwind CSS**.

> Catatan: pada brief tertulis React/Node, tetapi di akhir instruksi diminta logika menggunakan JavaScript dan PHP. Project ini dibuat menggunakan PHP untuk backend dan Vanilla JavaScript untuk interaksi agar mudah dijalankan di XAMPP.

## Fitur Utama

### Halaman Publik
1. Home
2. Events
3. Artists
4. Schedule
5. Gallery
6. Membership
7. Contact

### Hero
- Video background konser dengan fallback image
- Animasi parallax
- CTA Buy Ticket
- CTA Explore Events
- Event countdown

### Event
- Upcoming events
- Popular events
- Featured festival
- Event countdown
- Live seat availability
- Wishlist event

### Artist
- Artist profile
- Genre musik
- Social media
- Jadwal tampil via schedule

### Sistem Tiket
- Regular
- VIP
- VVIP
- Backstage Pass
- Booking tiket
- Demo payment Midtrans
- Download / print E-Ticket PDF
- QR code string untuk scanner

### User
- Login/register
- Dashboard user
- Booking tiket
- Riwayat pembelian
- Download e-ticket PDF
- Membership
- Wishlist event
- CRUD pembelian ticket: read dan delete riwayat pembelian, create lewat booking

### Admin
- Dashboard analytics
- CRUD Event
- CRUD Artist
- CRUD User
- CRUD Ticket
- CRUD Venue
- Laporan penjualan
- QR ticket scanner

### Premium / Demo
- AI event recommendation
- AI chat assistant
- QR ticket scanner
- Live seat availability
- Payment Gateway Midtrans demo
- Multi language toggle ID/EN demo
- SEO meta tag
- PWA manifest + service worker
- Storage menggunakan URL gambar / Cloudinary-ready field

## Cara Install di XAMPP

1. Extract folder ke:
   `C:/xampp/htdocs/future_music_experience`

2. Buka phpMyAdmin.

3. Import file:
   `database.sql`

4. Buka:
   `http://localhost/future_music_experience`

## Akun Demo

Admin:
- Email: `admin@futuremusic.test`
- Password: `admin123`

User:
- Email: `user@futuremusic.test`
- Password: `123456`

## Database

Database: `future_music_db`

Tabel:
- users
- venues
- events
- artists
- schedules
- ticket_types
- purchases
- payments
- wishlist
- ticket_scans

Seluruh tabel saling berelasi dengan foreign key InnoDB.



## Update Tambahan

### Mode Gelap / Terang
- Tombol toggle mode gelap/terang tersedia di navbar utama.
- Pilihan mode disimpan di `localStorage`, jadi saat halaman dibuka lagi mode tetap mengikuti pilihan terakhir user.
- Styling light mode ditambahkan di `assets/css/style.css`.
- Logic toggle mode berada di `assets/js/app.js`.

### Form Validation Tailwind/Cyber
- Validasi tetap dilakukan di server-side PHP.
- Tampilan error form dibuat lebih modern dengan utility-style Tailwind: rounded card, neon border, pink alert, dan invalid ring.
- Ditambahkan validasi interaktif JavaScript untuk:
  - login,
  - register,
  - contact,
  - booking ticket,
  - form CRUD admin.
- Helper PHP validasi berada di `config/database.php`:
  - `validation_box()`
  - `field_error()`
  - `control_class()`



## Perbaikan Tampilan Polos / CSS Tidak Terbaca

Jika halaman login tampil polos seperti HTML biasa, penyebabnya adalah path asset dari folder `auth/`, `admin/`, atau `user` tidak mengarah ke root project.

Sudah diperbaiki di:
`config/database.php`

Fungsi `url()` sekarang otomatis mengarah ke root project, jadi CSS/JS akan terbaca dari:
`assets/css/style.css`
dan
`assets/js/app.js`

Untuk cek cepat, buka:
`http://localhost/nama_folder_project/cek_asset.php`
