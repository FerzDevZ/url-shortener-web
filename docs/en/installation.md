# 3. Installation and Deployment Guide

## 3.1 Minimum Hardware Specifications
For stable operation in a production environment, specifically for handling concurrent tracking pixels and analytics logging, the following specifications are required:
*   **CPU**: 1 vCPU (Dual-core recommended for background workers).
*   **Memory**: 2GB RAM minimum (To accommodate PHP-FPM, Redis, and MySQL overhead).
*   **Storage**: 500MB available disk space (Excluding application logs and user-uploaded assets).

## 3.2 Deployment Checklist
1.  **Environment Preparation**: Ensure PHP 8.2+, MySQL 8.0+, and Redis 6.0+ are installed.
2.  **Source Acquisition**:
    ```bash
    git clone <repository-url>
    cd project-root
    ```
3.  **Dependency Resolution**:
    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run build
    ```
4.  **System Initialization**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --force --seed
    php artisan storage:link
    ```
5.  **Service Configuration**:
    *   Configure a process manager (e.g., Supervisor) to run `php artisan queue:work` continuously for analytics processing.
    *   Ensure Nginx/Apache points to the `public/` directory.
