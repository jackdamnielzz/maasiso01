## Key Components
- AuthService: Handles user authentication and authorization
- DataSyncModule: Manages real-time data synchronization
- APIGateway: Routes requests to appropriate microservices

## Data Flow
1. Client sends request to APIGateway
2. APIGateway authenticates request using AuthService
3. Request is routed to appropriate microservice
4. DataSyncModule ensures real-time updates across connected clients

## External Dependencies
- AWS S3 for file storage
- Stripe for payment processing
- SendGrid for email notifications

## Recent Significant Changes
- [2023-05-20] Migrated from MongoDB to PostgreSQL
- [2023-05-18] Implemented JWT-based authentication
- [2023-10-05] Updated the title tag for the `index.html` page to "MaasISO - ISO Certificering en Implementatie Diensten"
- [2023-10-10] Updated the title tag for the `diensten.html` page to "ISO Diensten - MaasISO"
- [2023-10-10] Updated the title tag for the `waarom-maasiso.html` page to "Waarom MaasISO - ISO Consultancy"

## User Feedback Integration
- Improved error messaging based on user reports
- Added dark mode theme following user suggestions
