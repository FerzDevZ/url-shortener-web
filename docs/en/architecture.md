# Architecture & Tech Stack

## System Architecture
ShortLink follows a decoupled service architecture designed for high availability and performance.

### 🔌 Tech Stack
-   **Backend**: PHP 8.2+ with Laravel 11 framework (LTS-ready).
-   **Database**: MySQL 8.0/MariaDB utilizing optimized indexing for fast link retrieval.
-   **Caching**: Redis 6.2+ for session storage and high-speed redirect caching.
-   **Processing**: Asynchronous queue processing via Redis for logging analytical data without impacting user redirect speed.
-   **Frontend**: Custom Vanilla CSS system with Vite bundling, ensuring a lightweight yet premium user experience.

## Performance Optimization
-   **Caching Strategy**: Every active shortlink is cached in Redis upon the first request. Subsequent redirects bypass the primary database entirely.
-   **Queueing**: Analytical data collection (GeoIP, Browser fingerprinting) is handled in the background by dedicated workers.
-   **Resource Management**: Automated garbage collection for expired logs and unused media assets.

## Security Overview
-   **Protection**: Built-in protection against brute-force attacks via rate limiting.
-   **Privacy**: GDPR-compliant analytics (No full IP storage; anonymized geolocation only).
-   **Redundancy**: Transactional database operations to ensure data integrity during team workspace modifications.
