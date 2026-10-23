# 🍰 CakeLuv Patisserie - Premium E-Commerce Platform

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8.0-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Midtrans](https://img.shields.io/badge/Midtrans_Payment-00A9E0?style=for-the-badge)

**CakeLuv Patisserie** is a modern premium bakery and artisan patisserie e-commerce web application engineered with **Laravel 13** and **Tailwind CSS v4**. It features an interactive product catalog, dynamic cart management, automated checkout powered by the **Midtrans Snap API**, Google OAuth authentication, and a **Shadcn-Admin Styled Management Dashboard** featuring 12-month revenue analytics and live order tracking.

---

## 📸 High-Definition Application Preview

<p align="center">
  <b>Storefront Landing Page</b><br>
  <img src="screenshots/landing-preview.png" alt="CakeLuv Landing Page" width="920">
</p>

<p align="center">
  <b>Modern Administrative Dashboard (Shadcn-Admin Style)</b><br>
  <img src="screenshots/dashboard-preview.png" alt="CakeLuv Admin Dashboard" width="920">
</p>

---

## ✨ Key Features

### 🛍️ Customer-Facing Storefront
- **Dynamic Artisan Catalog:** Showcases artisanal cakes and celebratory accessories with instant responsive category filtering.
- **Interactive Shopping Cart:** Real-time cart calculations supporting custom baker requests and cake inscriptions on individual line items.
- **Integrated Payment Gateway:** Instant checkout via **Midtrans Snap API** (supporting Bank Virtual Accounts, GoPay, QRIS, and Credit Cards) with automated asynchronous webhook updates.
- **Multi-Method Authentication:** Secure credential login/registration backed by Laravel Auth alongside one-click **Google OAuth (SSO)** via Laravel Socialite.
- **Luxury UI/UX Aesthetics:** Designed with **Tailwind CSS v4** and compiled with Vite 8 for smooth animations, fluid transitions, and zero layout shift.

### 📊 Administrative Suite & Operations
- **Data Analytics Dashboard (Shadcn-Admin Style):** Real-time KPI summary cards, order volumes, 12-month bar chart revenue analytics, and top-selling category doughnut charts via **Chart.js**.
- **Catalog Management (CRUD):** Add, modify, toggle stock availability, and manage multi-asset product image uploads with automated asset pipelines.
- **Real-Time Order Fulfillment:** Track incoming orders and manage the full transaction lifecycle (*Pending &rarr; Paid &rarr; Processed &rarr; Shipped*).
- **Dynamic Store Settings:** Configure branding, atelier address, head chef bio, and operating hours dynamically from the administrative panel.

### 🛡️ Security & Panel Hardening
- **Dynamic Admin Route Obfuscation:** The administrative suite avoids predictable URL routes (`/admin`). The access prefix is dynamically driven by `ADMIN_PREFIX` in `.env` (default: `cakeluv-portal-mgmt`).
- **Automated Scanner Mitigation:** Access attempts targeting default `/admin` or `/admin/dashboard` paths immediately return **HTTP 404 Not Found**, concealing management suites from bots.
- **Strict Role-Based Access Control (RBAC):** Server-side middleware validates permissions; unauthorized users attempting access are redirected with flash security alerts.
- **Named Route Architecture:** All administrative links and redirects use named routes (`route('admin.*')`), ensuring rock-solid navigation across prefix modifications.

---

## 🛠️ Technical Architecture & Technology Stack

- **Backend Framework:** Laravel 13.x (PHP 8.3+)
- **Architecture Pattern:** Clean MVC (Model-View-Controller) utilizing Eloquent ORM, Service / Controller separation, and Custom Middleware Route Guards.
- **Database:** MySQL with structured Laravel Migrations, Model Factories, and Database Seeders.
- **Frontend:** Laravel Blade Templating, Tailwind CSS v4, compiled via Vite 8.
- **Integrations & Third-Party Libraries:**
  - **Midtrans Snap API:** Real-time payment gateway orchestration.
  - **Laravel Socialite:** Google OAuth 2.0 Single Sign-On.
  - **Chart.js:** Visual data rendering for 12-month revenue metrics.
  - **Shadcn-Admin UI System:** Polished Slate OKLCH color design system.

---

## 🚀 Local Installation & Setup Guide

### 1. Repository Cloning
```bash
git clone https://github.com/pranataa-labs/Cake-Luv.git
cd Cake-Luv
```

### 2. Dependency Installation
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```
Configure your database and Midtrans credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cakeluv
DB_USERNAME=root
DB_PASSWORD=
ADMIN_PREFIX=cakeluv-portal-mgmt
MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_IS_PRODUCTION=false
```

### 4. Database Migration & Seeding
```bash
php artisan migrate --seed
```

### 5. Compile Assets & Launch Application
```bash
npm run build
php artisan serve
```
Access the application at `http://localhost:8000`.

---

## 🔐 Demo Credentials

- **Email:** `admin@cakeluv.com`
- **Password:** `password`
