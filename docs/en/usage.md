# Usage Guide

This chapter covers the essential workflows for managing your digital assets within the ShortLink dashboard.

## 🔗 Creating Your First Link
Navigate to the "Links" section and click on "Create New Link".
-   **Destination URL**: The long URL you wish to shorten.
-   **Custom Alias**: Optional. If left blank, a secure alphanumeric code will be generated.
-   **Protection**: You can enable password protection and set expiration dates for temporary campaigns.
-   **Tracking Pixels**: Enter your GTM or Facebook Pixel ID to track conversion events on the redirect page.

## 👥 Collaborative Workspaces
Workspaces allow multiple users to manage a shared set of links.
1.  **Create Workspace**: Go to "Teams" and create a new environment (e.g., "Marketing Division").
2.  **Add Members**: Invite users via email. They must be registered on the platform.
3.  **Assign Roles**: Admins can manage members and workspace settings; Members can create and manage links.
4.  **Shared Visibility**: Links created within a workspace are visible to all members of that group.

## 📊 Analytics & Reporting
ShortLink provides real-time insights into your link performance.
-   **Dashboard Overview**: View aggregated click data, top-performing links, and geographic distribution.
-   **CSV Export**: For deep data analysis, use the "Export CSV" button in the specific link's detail view. This provides a raw feed of all unique visitor data.

## 🔌 API Integration
The system provides a robust API for automated link generation.
-   **Personal Access Tokens**: Generate a token from your "Settings" page.
-   **Endpoints**: Standard RESTful endpoints are available at `/api/v1/links`. Refer to the developer documentation for request/response schemas.
