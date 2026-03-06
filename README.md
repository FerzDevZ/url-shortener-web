# ShortLink Project Manual

ShortLink is a high-performance URL management system designed for organizational collaboration and detailed traffic attribution. Unlike standard shortening tools, it is built with an emphasis on data integrity, team-based scoping, and professional branding requirements.

## Project Resources

Detailed technical documentation and user guides are available in the bilingual project manual:

*   [English Version](docs/en/introduction.md)
*   [Bahasa Indonesia](docs/id/pengantar.md)

## Technical Overview

*   **Platform**: Laravel 11 (PHP 8.2+)
*   **Infrastructure**: Redis (Caching & Queues), MySQL/MariaDB
*   **Architecture**: Decoupled analytics processing for zero-latency redirects.

## Quick Start (Developer Mode)

```bash
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

---
*For full configuration details, security policies, and API specifications, please refer to the documentation book in the `docs/` directory.*
