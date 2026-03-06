# 2. Architecture and Technical Design

## 2.1 System Architecture
ShortLink is built using a layered architecture that prioritizes the speed of the redirection engine over administrative overhead.

### 2.1.1 Core Components
*   **Application Framework**: Laravel 11.x (PHP 8.2+), providing a robust ORM and security middleware.
*   **Persistence Layer**: MySQL/MariaDB for relational data storage (Links, Users, Workspaces).
*   **High-Speed Caching**: Redis 6.2+ used as an intermediary to store link metadata, significantly reducing database load during peak traffic.
*   **Asynchronous Processing**: Background workers handle the intensive task of logging click analytics and GeoIP lookups.

## 2.2 Functional Modules

### 2.2.1 Redirection Engine
The redirection logic is decoupled from the main analytics database. When a short link is accessed:
1.  The system queries Redis for the target URL.
2.  If found, the user is redirected immediately.
3.  An asynchronous job is dispatched to record the visit details.

### 2.2.2 Workspace Synchronization
The multi-user workspace feature utilizes a normalized many-to-many relationship. This allows for complex organizational structures where users can transition between personal and collaborative environments seamlessly.

## 2.3 Security Protocols
*   **Access Tokens**: API access is managed via cryptographically secure tokens (Laravel Sanctum).
*   **Data Integrity**: Foreign key constraints with cascade/null-on-delete policies ensure a clean database state.
*   **Bot Mitigation**: Redirection flows include passive bot-checking mechanisms to filter artificial traffic from analytics reports.
