# ShortLink: Advanced URL Management & Analytics Platform

ShortLink is a comprehensive, enterprise-grade URL shortening solution built for performance, branding, and deep analytical insight. This platform empowers users and teams to manage their digital footprint through customizable links, high-resolution QR codes, and real-time tracking integration.

## 🚀 Key Features

### 🛠 Core Link Management
- **Shortening & Custom Aliases**: Generate secure short URLs with the option for personalized, brand-specific aliases.
- **Smart Redirects**: High-speed redirection engine optimized with Redis caching for sub-millisecond latency.
- **Security First**: Protect sensitive links with password encryption, expiration dates, and bot-safe redirection.
- **Micro Landing Pages**: Create professional "Link-in-Bio" profiles to centralize your social presence.

### 🎨 Advanced Branding
- **Dynamic QR Codes**: Fully customizable QR codes including custom hexagonal colors and centralized brand logos.
- **Visual Excellence**: Premium, glassmorphism-inspired UI with full support for dark and light modes.

### 📊 Intelligence & Analytics
- **Deep Insights**: Real-time tracking of click volume, geolocation (Country & City via GeoIP), device types, and browser statistics.
- **Professional Export**: Streamed CSV export functionality for processing raw click data in external BI tools.
- **Tracking Pixels**: Native integration for **Facebook Pixel** and **Google Tag Manager** to measure conversion flows accurately.

### 👥 Team Collaboration (Workspaces)
- **Multi-user Environments**: Dedicated workspaces for teams to collaborate on shared link campaigns.
- **Role-Based Access Control (RBAC)**: Fine-grained permissions (Admin/Member) to manage organizational resources and members.

### 🔌 Developer Experience
- **RESTful API**: A robust, well-documented API secured by Laravel Sanctum for third-party integrations.
- **Performance Optimized**: Background queue processing (Redis) for analytical logging to ensure zero-latency for end users.

## 🛠 Tech Stack

- **Framework**: [Laravel 11.x](https://laravel.com) (PHP 8.2+)
- **Database**: MySQL 8.0 / MariaDB
- **Caching & Queues**: Redis
- **Security**: Laravel Sanctum, Honeypot Protection, Math Captcha
- **Analytics Engine**: torann/geoip, simple-software-io/qr-code
- **Frontend Architecture**: Vanilla CSS (Custom tokens), Chart.js, Vite

## 📋 Prerequisites

- **PHP**: 8.2 or higher
- **Extensions**: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Database**: MySQL 8.0+
- **Cache**: Redis 6.0+
- **System**: Composer & NPM (Node.js 18+)

## 🚀 Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd project1
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   # Update DB_DATABASE, DB_USERNAME, DB_PASSWORD, and REDIS settings
   ```

4. **Initialize Application**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Build Assets & Start Server**:
   ```bash
   npm run build
   php artisan serve
   ```

## 💻 Minimal Specifications

- **Server**: 1 vCPU, 2GB RAM (Recommended for concurrent Queue/Redis operations)
- **Disk**: 500MB (excluding storage for logs/uploads)
- **OS**: Linux (Ubuntu 22.04 LTS recommended) / macOS / Windows (WSL2)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
