# Location-Based Incident Management System

## 1. Project Name

**Location-Based Incident Management System**
Vietnamese name: **Hệ thống quản lý và giám sát sự cố theo vị trí**

## 2. Project Overview

This project is a web-based incident management system designed to help users report, monitor, and manage incidents based on location. Users can create incident tickets, provide detailed descriptions, upload evidence images, and select the incident location on a map. Administrators can review incidents, assign technicians, track progress, and manage the entire incident lifecycle. Managers can monitor overall performance through dashboards, maps, and reports.

The system is suitable for environments such as an IOC, university campus, office building, smart city monitoring center, or internal support department.

## 3. Problem Statement

In many organizations, incident reporting and handling are still managed through fragmented channels such as phone calls, messages, spreadsheets, or informal communication. This can lead to several problems:

- Incident information is incomplete or difficult to track.
- The exact location of the incident is unclear.
- Evidence such as images is not stored systematically.
- Administrators cannot easily assign and monitor responsible staff.
- Managers lack dashboards and reports to evaluate incident trends and response performance.
- The history of status changes is not transparent.

Therefore, a centralized system is needed to manage incidents from the moment they are reported until they are resolved and closed.

## 4. Project Objectives

The main objectives of this project are:

- Build a web application for reporting and managing incidents.
- Allow users to submit incident tickets with descriptions, categories, priority levels, evidence images, and map locations.
- Support role-based access control for User, Admin, Technician, and Manager.
- Allow Admins to assign incidents to Technicians.
- Allow Technicians to update incident status according to a controlled workflow.
- Store status history and important actions for traceability.
- Display incidents on a map for location-based monitoring.
- Provide dashboards and reports for management.
- Apply software engineering practices such as requirement analysis, database design, API design, testing, documentation, Git workflow, soft delete, audit logs, and state machine logic.

## 5. Target Users

### 5.1 User / Reporter

The User is the person who reports an incident.

Main responsibilities:

- Create incident tickets.
- Upload evidence images.
- Select incident location on a map.
- View personal tickets.
- Comment on personal tickets.
- Confirm whether an incident has been resolved.

### 5.2 Admin / Coordinator

The Admin is responsible for managing and coordinating incidents.

Main responsibilities:

- View all incidents.
- Review newly created incidents.
- Assign incidents to Technicians.
- Update priority and category when necessary.
- Reject invalid incidents.
- Close resolved incidents.
- Manage categories and users.
- Monitor dashboard and map.

### 5.3 Technician

The Technician is responsible for handling assigned incidents.

Main responsibilities:

- View assigned incidents.
- Update incident status.
- Add comments during the handling process.
- Upload after-service evidence if needed.
- Mark incidents as resolved.

### 5.4 Manager

The Manager is responsible for monitoring the overall situation and performance.

Main responsibilities:

- View dashboard statistics.
- View incidents on the map.
- Monitor incident trends.
- Export incident reports.
- Evaluate technician performance and incident response efficiency.

## 6. Scope of the Project

### 6.1 In Scope

The MVP version includes:

- Authentication and role-based authorization.
- Incident ticket creation.
- Image upload for evidence.
- Map location selection.
- Incident list and incident detail pages.
- Admin assignment workflow.
- Technician status update.
- Comment system.
- Status history log.
- Soft delete for incidents.
- Dashboard statistics.
- Map dashboard with incident markers.
- CSV/Excel export.
- Seed data for demonstration.

### 6.2 Out of Scope

The following features will not be implemented in the MVP version:

- Advanced video processing.
- Video streaming.
- Real-time WebSocket dashboard.
- AI-based incident classification.
- PDF report generator.
- Advanced heatmap visualization.
- Mobile application.

These features may be considered as future improvements.

## 7. Main Incident Workflow

The standard incident workflow is:

1. User creates a new incident ticket.
2. System stores the ticket with status `NEW`.
3. Admin reviews the ticket.
4. Admin assigns the ticket to a Technician.
5. Ticket status becomes `ASSIGNED`.
6. Technician starts handling the incident.
7. Ticket status becomes `IN_PROGRESS`.
8. Technician may request more information if needed.
9. Ticket status becomes `PENDING`.
10. Technician resolves the incident.
11. Ticket status becomes `RESOLVED`.
12. Admin or User confirms the result.
13. Ticket status becomes `CLOSED`.

Invalid tickets can be rejected by Admin.

## 8. Ticket Status Flow

Valid ticket statuses:

- `NEW`
- `ASSIGNED`
- `IN_PROGRESS`
- `PENDING`
- `RESOLVED`
- `CLOSED`
- `REJECTED`

Valid transitions:

- `NEW` → `ASSIGNED`
- `NEW` → `REJECTED`
- `ASSIGNED` → `IN_PROGRESS`
- `IN_PROGRESS` → `PENDING`
- `IN_PROGRESS` → `RESOLVED`
- `PENDING` → `IN_PROGRESS`
- `PENDING` → `REJECTED`
- `RESOLVED` → `CLOSED`

Invalid examples:

- `NEW` cannot move directly to `RESOLVED`.
- `CLOSED` cannot move back to `IN_PROGRESS` in the MVP version.
- `REJECTED` is a final status.
- `CLOSED` is a final status.

## 9. Proposed Technology Stack

### Frontend

- React
- Tailwind CSS
- React Router
- React Leaflet
- Recharts or Chart.js
- Axios

### Backend

- Node.js
- Express.js
- JWT Authentication
- Multer for image upload
- RESTful API

### Database

- MySQL
- MySQL Spatial Data using `POINT`
- Spatial index for location-based queries
- Soft delete using `deleted_at`

### Other Tools

- Git and GitHub
- Postman for API testing
- MySQL Workbench
- Draw.io or Mermaid for diagrams
- CSV/Excel export library

## 10. Expected Deliverables

The expected deliverables are:

- Frontend source code.
- Backend source code.
- Database schema and seed data.
- API documentation.
- Test case document.
- Project report.
- README setup guide.
- Demo script.
- Final presentation slides.
- Optional deployment link.

## 11. Demo Scenario

A user notices a traffic incident near a school gate. The user logs into the system, creates a new incident ticket, uploads an image as evidence, selects the exact location on the map, and submits the ticket.

The Admin receives the new incident, reviews the details, and assigns it to a Technician. The Technician updates the ticket status to `IN_PROGRESS`, adds comments during the handling process, and later marks the ticket as `RESOLVED`. The Admin reviews the result and closes the ticket. Finally, the Manager views the dashboard, checks the incident map, and exports a report.

## 12. Future Improvements

Future improvements may include:

- Real-time dashboard using Socket.io.
- AI-based incident category suggestion.
- Duplicate incident detection.
- Heatmap of incident density.
- Email notification.
- Push notification.
- PDF report generation.
- Mobile application.
- Advanced video evidence support.
