# Installation & Specifications

## System Requirements
To ensure optimal performance, particularly when handling multiple concurrent tracking pixels and analytics logging, the following specifications are recommended.

### 💻 Minimum Specifications
-   **Processor**: 1 vCPU (Dual-core or higher recommended).
-   **Memory (RAM)**: 2GB (Required for smooth operation of Redis and PHP-FPM).
-   **Disk Space**: 500MB for application files (SSD recommended).
-   **Operating System**: Linux-based (Ubuntu 22.04 LTS, Debian, or RHEL) or macOS.

### 🛠 Technical Dependencies
-   **PHP**: Version 8.2 or 8.3.
-   **Web Server**: Nginx (Preferred) or Apache.
-   **Database**: MySQL 8.0 or MariaDB 10.6+.
-   **Cache/Queue**: Redis 6.0+.
-   **Node.js**: Version 18+ (For frontend asset compilation).

## Installation Steps

1.  **Clone Source Control**
    ```bash
    git clone <your-repository-url>
    cd shortlink-project
    ```

2.  **Environment Configuration**
    Copy the template and modify variables according to your environment.
    ```bash
    cp .env.example .env
    ```
    Ensure `REDIS_HOST`, `DB_DATABASE`, and `APP_URL` are correctly set.

3.  **Dependency Installation**
    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run build
    ```

4.  **Database & Security Initialization**
    ```bash
    php artisan key:generate
    php artisan migrate --force --seed
    php artisan storage:link
    ```

5.  **Service Setup**
    Ensure your queue worker is running to handle analytics:
    ```bash
    php artisan queue:work --queue=default --tries=3
    ```
