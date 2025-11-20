# PA-Framework-Laptopia
2209106012 - Aldi Solihin

<div align="center">
</div>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA.git">
    <img src="ss-laptopia\Laptopia.png" alt="Logo" width="100%" >
  </a>

  <h3 align="center">Website Toko Komputer Laptopia</h3>

  <p align="center">
    Project Akhir Praktikum Framework Based Programming
    <br />
    <a href="https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/issues/new?labels=bug">Report Bug</a>
    ·
    <a href="https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/issues/new?labels=enhancement">Request Feature</a>
  </p>
</div>

Laptopia adalah platform website modern untuk pencarian laptop, pengelolaan komponen, manajemen pesanan, hingga transaksi produk.
Dibangun menggunakan Laravel, TailwindCSS, dan sistem panel admin canggih untuk mempermudah proses manajemen toko.

Website ini dirancang dengan gaya UI futuristik gelap (dark-neon theme), responsif, dan dilengkapi fitur lengkap untuk user, admin, dan superadmin.

## Tech Stack
### Backend  
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

### Frontend  
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)

## Deskripsi Project
- Laptopia adalah website toko laptop berbasis Laravel dengan fitur lengkap untuk memudahkan pengguna dalam:
- Menjelajahi laptop berdasarkan spesifikasi
- Melihat detail komponen seperti CPU, GPU, RAM, dan penyimpanan
- Melakukan pembelian laptop
- Mengelola pesanan
- Melakukan rating & review produk
- Worker dapat melakukan pemrosesan order dari pengguna dengan mudah 
- Sementara Admin mendapatkan akses penuh untuk mengatur data produk, komponen, pengguna, dan pengelolaan pesanan

## Fitur Utama
### User Features
- Registrasi & Login pengguna  
- Dashboard pengguna  
- Riwayat transaksi lengkap  
- Melihat Katalog Lengkap  
- Filter & pencarian laptop  
- Melihat detail dan spesifikasi lengkap  
- Melakukan pemesanan 

### Admin Features
- Dashboard admin
- CRUD Produk Laptop
- Manajemen pesanan pengguna
- Menambah karyawan
- Manajemen kategori laptop

### Worker
- Manajemen pesanan pengguna

## Dokumentasi (Screenshots)

### Homepage  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/homepage.png?raw=true) 

### Login  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/login.png?raw=true) 

### Register  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/register.png?raw=true) 

### Katalog Produk   
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/catalogue.png?raw=true)  

### Wishlist  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/wishlist.png?raw=true)

### Keranjang Belanja  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/cart.png?raw=true) 

### Order  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/order.png?raw=true) 

### Riwayat Transaksi  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/order-list.png?raw=true) 

### Admin Dashboard  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/admin-dashboard.png?raw=true) 

### CRUD Admin  
![alt text](https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA/blob/main/ss-laptopia/admin-productlist.png?raw=true) 

---

## Cara Melakukan Instalasi Project

```bash
git clone https://github.com/AldiSolihin12/PA-FRAMEWORK-2025-LAPTOPIA.git
cd Laptopia

composer install
npm install

cp .env.example .env
php artisan key:generate

composer run dev

php artisan migrate
php artisan serve
