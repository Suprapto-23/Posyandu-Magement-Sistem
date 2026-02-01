# 🏥 Posyandu Management System

<div align="center">

![Posyandu Banner](https://img.shields.io/badge/Posyandu-Management%20System-blue?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**Sistem Informasi Manajemen Posyandu Berbasis Web**

[📖 Documentation](#dokumentasi) • [✨ Features](#fitur-utama) • [🚀 Installation](#instalasi) • [📸 Screenshots](#screenshot)

</div>

---

## 📋 Tentang Project

**Posyandu Management System** adalah aplikasi berbasis web yang dirancang untuk memudahkan pengelolaan data dan administrasi Posyandu (Pos Pelayanan Terpadu). Sistem ini membantu petugas kesehatan dalam mencatat, memantau, dan mengelola data ibu hamil, balita, serta kegiatan imunisasi dan pemeriksaan kesehatan secara efisien.

### 🎯 Tujuan
- Digitalisasi sistem pencatatan Posyandu
- Meningkatkan efisiensi administrasi kesehatan masyarakat
- Memudahkan monitoring kesehatan ibu dan anak
- Menyediakan data kesehatan yang akurat dan real-time

---

## ✨ Fitur Utama

### 👥 Manajemen Data
- 📝 **Pendaftaran Peserta** - Registrasi ibu hamil dan balita
- 📊 **Rekam Medis Digital** - Pencatatan riwayat kesehatan
- 📈 **Grafik Pertumbuhan** - Monitoring pertumbuhan balita
- 💉 **Jadwal Imunisasi** - Pengingat dan tracking imunisasi

### 🔐 Sistem Keamanan
- 🔒 Autentikasi user (Admin, Petugas, User)
- 🛡️ Role-based access control
- 🔑 Password encryption

### 📱 Antarmuka User-Friendly
- 🎨 Dashboard interaktif
- 📊 Laporan dan statistik
- 🖨️ Export data (PDF/Excel)
- 📱 Responsive design

---

## 🛠️ Tech Stack

| Technology | Description |
|-----------|-------------|
| **Framework** | Laravel (PHP) |
| **Database** | MySQL |
| **Frontend** | Blade Templates, Bootstrap, jQuery |
| **Server** | Apache (XAMPP) |
| **Version Control** | Git & GitHub |

---

## 🚀 Instalasi

### Prasyarat
Pastikan sistem Anda sudah terinstall:
- PHP >= 8.0
- Composer
- MySQL/MariaDB
- XAMPP/LAMP/WAMP

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/Suprapto-23/Posyandu-Magement-Sistem.git
   cd Posyandu-Magement-Sistem
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   
   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=posyandu_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Migrasi Database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   
   Akses aplikasi di: `http://localhost:8000`

---

## 👤 Default Login

| Role | Username | Password |
|------|----------|----------|
| Admin | admin@posyandu.com | admin123 |
| Petugas | petugas@posyandu.com | petugas123 |

> ⚠️ **Catatan:** Segera ganti password default setelah login pertama kali!

---

## 📸 Screenshot

### Dashboard Admin
![Dashboard](https://via.placeholder.com/800x400/4CAF50/FFFFFF?text=Dashboard+Admin)

### Data Balita
![Data Balita](https://via.placeholder.com/800x400/2196F3/FFFFFF?text=Data+Balita)

### Grafik Pertumbuhan
![Grafik](https://via.placeholder.com/800x400/FF9800/FFFFFF?text=Grafik+Pertumbuhan)

---

## 📁 Struktur Project

```
Posyandu-Management-System/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── resources/
│   ├── views/
│   └── ...
├── routes/
│   └── web.php
├── .env.example
├── composer.json
└── README.md
```

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Jika Anda ingin berkontribusi:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📝 Roadmap

- [ ] Integrasi notifikasi SMS/WhatsApp
- [ ] Mobile Application (Android/iOS)
- [ ] Multi-bahasa support
- [ ] Export laporan otomatis
- [ ] Integrasi dengan sistem puskesmas

---

## 📄 Lisensi

Project ini dibuat untuk keperluan edukasi dan pengembangan sistem kesehatan masyarakat.

---

## 👨‍💻 Developer

**Suprapto**

- GitHub: [@Suprapto-23](https://github.com/Suprapto-23)
- Email: suprapto.kulo@gmail.com

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap UI
- Chart.js
- Komunitas Developer Indonesia

---

<div align="center">

**⭐ Jika project ini bermanfaat, jangan lupa beri star! ⭐**

Made with ❤️ by Suprapto

</div>
