# 🚀 SMS Platform API (Laravel 12)

An advanced and modular **SMS Messaging Platform** built with **Laravel 12**.  
It integrates multiple SMS gateways (Dotgo, 2FroComs, Africa’s Talking, etc.) and provides a full suite of tools for:

-   ✔ Contact Management
-   ✔ Message Campaigns
-   ✔ SMS Sending
-   ✔ User Authentication & Verification
-   ✔ Multi-provider routing

This API-only project is designed for scalability, performance, and clean architecture.

## NB: SMS SERVICES credential.

The default sms service provider hardcoded is 2frocoms. You can register to get the api keys needed to run the system.

-   (2frocoms) https://2frocoms.com/
-   (dotgo) https://www.dotgo.com/

---

## 📘 Features

### 🔐 Authentication

-   Registration
-   Login
-   Email verification via OTP
-   Forgot password
-   Logout
-   `auth:sanctum` token-based security

### 👥 Contacts Module

-   Create, update, list, delete contacts
-   Group contacts (using tags)
-   Bulk import support-ready

### 📢 Campaign Module

-   Create SMS campaigns
-   Select recipients by:
    -   Contact IDs
    -   Phone numbers
    -   Contact groups
-   Multi-provider SMS dispatching
-   Status tracking

### 📩 Messages

-   View messages belonging to campaigns
-   Provider routing layer (extensible)

---

## 🛠 Installation Guide

### ### 1. **Requirements**

You need the following installed:

| Tool                           | Link                            |
| ------------------------------ | ------------------------------- |
| **PHP 8.3+**                   | https://www.php.net/downloads   |
| **Laravel Herd (Recommended)** | https://herd.laravel.com        |
| **MySQL**                      | https://dev.mysql.com/downloads |
| **Redis**                      | https://redis.io/downloads      |
| **Composer**                   | https://getcomposer.org         |

> 💡 **Default configuration allows SQLite.**  
> If you want a zero-setup environment, no DB installation is needed — the app will automatically fall back to `database/database.sqlite`.

---

## 🧰 2. **Clone & Install Dependencies**

```bash
git clone https://github.com/your-repo/sms-platform.git
cd sms-platform
composer install
```

## ⚙️ 3. Environment Setup

### Copy `.env` file

```bash
cp .env.example .env
```

## Generate application key

```bash
php artisan key:generate
```

## 🗄 SQLite (Default)

```bash
touch database/database.sqlite
```

## Update .env:

```bash
DB_CONNECTION=sqlite
```

SQLite requires no username/password and works out of the box.

## 🐬 MySQL (Optional)

If you prefer MySQL, update your .env:

```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_DATABASE=sms
    DB_USERNAME=root
    DB_PASSWORD=password
```

🗄 4. Run Migrations

```bash
php artisan migrate
```

🚀 5. Start the Application
Using Herd (recommended)

```bash
herd serve
```

Or via Artisan

```bash
php artisan serve
```

Start the Queue worker (Processing of sms is via queue)

```bash
php artisan queue:listen
```

## 🔑 Authentication Flow (Sanctum)

Laravel Sanctum handles token-based API authentication.

Register → User receives auth token

User must verify email before accessing protected routes (verification info can be seen in log file if no SMTP mail service provider added)

Send token in headers on every request:

Authorization: Bearer <token>
Accept: application/json

## 📚 API Endpoints (Summary)

# Full API Documentation (Postman):

👉 https://documenter.getpostman.com/view/29320193/2sB3dHVYBr

# 🧑‍💻 Public Endpoints

Method Endpoint Description

-   GET / App health check
-   POST /login Login
-   POST /register Register
-   POST /forgot-password Request password reset
    🔐 Email Verification Endpoints
    | Method Endpoint Description
-   POST /verify-email Verify OTP
-   POST /resend-verification-otp Resend OTP

Middleware: throttle:authentication

# 🙍 Authenticated User Routes

Method Endpoint Controller

-   GET /me UserController@show
-   PUT /me UserController@update
-   PUT /me/logout UserController@logout

Middleware: auth:sanctum

# ✔️ Protected Endpoints (Verified Email Required)

# 📇 Contacts

Method Endpoint Description

-   GET /contacts List contacts
-   POST /contacts Create contact
-   GET /contacts/{id} Show contact
-   PUT /contacts/{id} Update contact
-   DELETE /contacts/{id} Delete contact

# 📢 Campaigns

Method Endpoint Description

-   GET /campaigns List campaigns
-   POST /campaigns Create campaign
-   GET /campaigns/{id} Show campaign
-   PUT /campaigns/{id} Update campaign
-   DELETE /campaigns/{id} Delete campaign

Middleware:

auth:sanctum

verified

## 🧩 Providers Integrated

This project includes a clean and extensible SMS Provider Driver System with:

-   Dotgo

-   2FroComs

-   Africa’s Talking

# Easily extendable for:

-   Twilio

-   Termii

-   Nexmo (Vonage)

-   Any REST-based SMS API

## 🏗 Architecture Overview

-   Modular Service Layer

-   Provider Strategy Pattern

-   Contact & Tagging System

-   Queue-Ready SMS Sending

-   Caching Layer with Tagging Support Fallback

-   Unified Exception Handler with Standard API Response Format

-   Advanced FormRequest Validation (conditional rules)

-   Laravel Sanctum Authentication

## 📄 License

MIT License © YourName

## 🙋 Need Help?

If you need help with setup or contributing, open an issue or contact the project (soliudeen999@gmail.com).
