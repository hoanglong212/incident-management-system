# Requirements Specification

## 1. Functional Requirements

### FR01 - User Registration

The system shall allow new users to create an account by providing required information such as name, email, password, and phone number.

### FR02 - User Login

The system shall allow registered users to log in using email and password.

### FR03 - Role-Based Access Control

The system shall support four main roles:

- User
- Admin
- Technician
- Manager

Each role shall have different permissions.

### FR04 - Create Incident Ticket

The system shall allow Users to create an incident ticket with the following information:

- Title
- Description
- Category
- Priority
- Address
- Location on map
- Occurred time
- Evidence image

### FR05 - Upload Evidence Image

The system shall allow Users and Technicians to upload image files as evidence.

Supported file types:

- JPG
- JPEG
- PNG
- WebP

The system shall validate file type and file size before saving.

### FR06 - Select Incident Location

The system shall allow Users to select the incident location on a map.

The system shall store:

- Latitude
- Longitude
- Address
- MySQL `POINT` location data

### FR07 - View Incident List

The system shall allow users to view incident lists based on their roles.

- User can view only their own incidents.
- Technician can view only assigned incidents.
- Admin can view all incidents.
- Manager can view all incidents for monitoring purposes.

### FR08 - Search and Filter Incidents

The system shall allow filtering incidents by:

- Status
- Category
- Priority
- Date range
- Assigned technician
- Keyword

### FR09 - View Incident Detail

The system shall display complete incident information, including:

- Ticket code
- Title
- Description
- Category
- Priority
- Status
- Reporter
- Assigned technician
- Address
- Map location
- Evidence images
- Comments
- Status history

### FR10 - Assign Technician

The system shall allow Admins to assign an incident to a Technician.

After assignment, the incident status shall become `ASSIGNED`.

### FR11 - Update Incident Status

The system shall allow Admins and Technicians to update incident status according to valid state transitions.

Invalid transitions shall be rejected by the backend.

### FR12 - Comment on Incident

The system shall allow Users, Admins, and Technicians to add comments to an incident.

### FR13 - Status History Log

The system shall record every status change, including:

- Old status
- New status
- User who changed the status
- Note or reason
- Timestamp

### FR14 - Soft Delete Incident

The system shall allow Admins to delete incidents using soft delete.

Soft-deleted incidents shall not appear in normal lists but shall remain in the database.

### FR15 - Dashboard Statistics

The system shall provide dashboard statistics, including:

- Total incidents
- New incidents
- Assigned incidents
- In-progress incidents
- Resolved incidents
- Closed incidents
- Rejected incidents
- Urgent incidents

### FR16 - Map Dashboard

The system shall display incident markers on a map.

Markers shall show basic incident information, such as:

- Ticket code
- Title
- Status
- Priority
- Category
- Address

### FR17 - Export Report

The system shall allow Admins and Managers to export incident data as CSV or Excel based on filters.

### FR18 - Seed Data

The system shall provide seed data with around 50 to 100 sample incidents for demonstration.

## 2. Non-Functional Requirements

### NFR01 - Security

The system shall use JWT-based authentication.

Passwords shall be hashed before being stored in the database.

### NFR02 - Authorization

The backend shall check user permissions before allowing access to protected APIs.

### NFR03 - Validation

The backend shall validate all important inputs, including:

- Email format
- Required fields
- File type
- File size
- Valid ticket status
- Valid priority
- Valid coordinates

### NFR04 - Error Handling

The system shall return clear error messages for invalid requests, unauthorized access, and server errors.

### NFR05 - Performance

Dashboard and list APIs shall support pagination and filters to avoid loading too much data at once.

### NFR06 - Maintainability

The source code shall be organized into clear modules, such as routes, controllers, services, repositories, middlewares, and validators.

### NFR07 - Traceability

The system shall store important actions in status logs and audit logs.

### NFR08 - Usability

The user interface shall be simple, clean, and responsive for desktop and tablet screens.

### NFR09 - Data Integrity

The system shall use foreign keys and controlled status transitions to protect data consistency.

### NFR10 - Documentation

The project shall include README, API documentation, database design, and test cases.

## 3. Out of Scope

The MVP version will not include:

- Advanced video upload and processing.
- Video streaming.
- WebSocket real-time updates.
- AI classification.
- PDF report builder.
- Mobile app.
- Advanced notification system.
- Heatmap visualization.

## 4. Future Improvements

Possible future improvements include:

- Real-time dashboard using Socket.io.
- AI-based category and priority suggestion.
- Duplicate incident detection.
- Heatmap of incident density.
- Email notification.
- Mobile app.
- PDF reports.
- Advanced video evidence support.
- Integration with external camera or IoT systems.

## 5. User Role Permissions

| Feature                 |    User | Admin | Technician | Manager |
| ----------------------- | ------: | ----: | ---------: | ------: |
| Create incident         |     Yes |   Yes |         No |      No |
| View own incidents      |     Yes |   Yes |         No |      No |
| View all incidents      |      No |   Yes |         No |     Yes |
| View assigned incidents |      No |   Yes |        Yes |      No |
| Assign technician       |      No |   Yes |         No |      No |
| Update status           | Limited |   Yes |        Yes |      No |
| Comment                 |     Yes |   Yes |        Yes |      No |
| Upload evidence image   |     Yes |   Yes |        Yes |      No |
| View dashboard          |      No |   Yes |         No |     Yes |
| View map dashboard      |      No |   Yes |         No |     Yes |
| Export report           |      No |   Yes |         No |     Yes |
| Manage users            |      No |   Yes |         No |      No |
| Manage categories       |      No |   Yes |         No |      No |
