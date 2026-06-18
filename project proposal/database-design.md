# Database Design

## 1. Overview

The database is designed to support a location-based incident management system. The main entities include users, roles, incidents, categories, attachments, comments, status logs, notifications, and audit logs.

The system applies:

- Role-based access control.
- Soft delete for important data.
- Status history tracking.
- MySQL Spatial Data for location-based queries.
- Foreign keys for data integrity.

## 2. Main Tables

## 2.1 roles

Stores user roles in the system.

| Column      | Type         | Description                                 |
| ----------- | ------------ | ------------------------------------------- |
| id          | BIGINT       | Primary key                                 |
| name        | VARCHAR(50)  | Role name: USER, ADMIN, TECHNICIAN, MANAGER |
| description | VARCHAR(255) | Role description                            |
| created_at  | DATETIME     | Created time                                |
| updated_at  | DATETIME     | Updated time                                |

## 2.2 users

Stores account information.

| Column     | Type         | Description          |
| ---------- | ------------ | -------------------- |
| id         | BIGINT       | Primary key          |
| role_id    | BIGINT       | Foreign key to roles |
| name       | VARCHAR(100) | Full name            |
| email      | VARCHAR(150) | Unique email         |
| password   | VARCHAR(255) | Hashed password      |
| phone      | VARCHAR(20)  | Phone number         |
| avatar_url | VARCHAR(255) | Avatar image URL     |
| status     | ENUM         | ACTIVE, INACTIVE     |
| created_at | DATETIME     | Created time         |
| updated_at | DATETIME     | Updated time         |
| deleted_at | DATETIME     | Soft delete time     |

## 2.3 incident_categories

Stores incident categories.

| Column      | Type         | Description          |
| ----------- | ------------ | -------------------- |
| id          | BIGINT       | Primary key          |
| name        | VARCHAR(100) | Category name        |
| description | TEXT         | Category description |
| created_at  | DATETIME     | Created time         |
| updated_at  | DATETIME     | Updated time         |
| deleted_at  | DATETIME     | Soft delete time     |

Example categories:

- Traffic
- Camera
- Network
- Equipment
- Security
- Environment
- Facility
- Software
- Other

## 2.4 incidents

Stores main incident ticket information.

| Column      | Type            | Description                                                     |
| ----------- | --------------- | --------------------------------------------------------------- |
| id          | BIGINT          | Primary key                                                     |
| code        | VARCHAR(30)     | Unique ticket code, example: INC-000001                         |
| title       | VARCHAR(255)    | Incident title                                                  |
| description | TEXT            | Detailed description                                            |
| category_id | BIGINT          | Foreign key to incident_categories                              |
| priority    | ENUM            | LOW, MEDIUM, HIGH, URGENT                                       |
| status      | ENUM            | NEW, ASSIGNED, IN_PROGRESS, PENDING, RESOLVED, CLOSED, REJECTED |
| reporter_id | BIGINT          | User who reported the incident                                  |
| assigned_to | BIGINT          | Technician assigned to the incident                             |
| address     | VARCHAR(255)    | Incident address                                                |
| ward        | VARCHAR(100)    | Ward                                                            |
| district    | VARCHAR(100)    | District                                                        |
| city        | VARCHAR(100)    | City                                                            |
| latitude    | DECIMAL(10, 7)  | Latitude                                                        |
| longitude   | DECIMAL(10, 7)  | Longitude                                                       |
| location    | POINT SRID 4326 | Spatial location                                                |
| occurred_at | DATETIME        | Time when incident occurred                                     |
| assigned_at | DATETIME        | Time when assigned                                              |
| resolved_at | DATETIME        | Time when resolved                                              |
| closed_at   | DATETIME        | Time when closed                                                |
| created_at  | DATETIME        | Created time                                                    |
| updated_at  | DATETIME        | Updated time                                                    |
| deleted_at  | DATETIME        | Soft delete time                                                |

Important notes:

- `latitude` and `longitude` are kept for simple frontend display.
- `location` is used for spatial queries.
- `deleted_at` is used for soft delete.
- Ticket should not be physically deleted from database.

## 2.5 incident_attachments

Stores uploaded evidence images.

| Column      | Type         | Description              |
| ----------- | ------------ | ------------------------ |
| id          | BIGINT       | Primary key              |
| incident_id | BIGINT       | Foreign key to incidents |
| uploaded_by | BIGINT       | Foreign key to users     |
| file_url    | VARCHAR(255) | File path or URL         |
| file_name   | VARCHAR(255) | Original file name       |
| file_type   | VARCHAR(50)  | MIME type                |
| file_size   | BIGINT       | File size in bytes       |
| created_at  | DATETIME     | Created time             |
| deleted_at  | DATETIME     | Soft delete time         |

## 2.6 incident_comments

Stores comments in each incident.

| Column      | Type     | Description              |
| ----------- | -------- | ------------------------ |
| id          | BIGINT   | Primary key              |
| incident_id | BIGINT   | Foreign key to incidents |
| user_id     | BIGINT   | Comment author           |
| content     | TEXT     | Comment content          |
| created_at  | DATETIME | Created time             |
| updated_at  | DATETIME | Updated time             |
| deleted_at  | DATETIME | Soft delete time         |

## 2.7 incident_status_logs

Stores status change history.

| Column      | Type        | Description              |
| ----------- | ----------- | ------------------------ |
| id          | BIGINT      | Primary key              |
| incident_id | BIGINT      | Foreign key to incidents |
| old_status  | VARCHAR(50) | Previous status          |
| new_status  | VARCHAR(50) | New status               |
| changed_by  | BIGINT      | User who changed status  |
| note        | TEXT        | Reason or note           |
| created_at  | DATETIME    | Created time             |

## 2.8 notifications

Stores in-app notifications.

| Column     | Type         | Description           |
| ---------- | ------------ | --------------------- |
| id         | BIGINT       | Primary key           |
| user_id    | BIGINT       | Notification receiver |
| title      | VARCHAR(255) | Notification title    |
| message    | TEXT         | Notification message  |
| is_read    | BOOLEAN      | Read status           |
| created_at | DATETIME     | Created time          |

## 2.9 audit_logs

Stores important user actions.

| Column      | Type         | Description                    |
| ----------- | ------------ | ------------------------------ |
| id          | BIGINT       | Primary key                    |
| user_id     | BIGINT       | User who performed the action  |
| action      | VARCHAR(100) | Action name                    |
| entity_type | VARCHAR(100) | Entity type, example: INCIDENT |
| entity_id   | BIGINT       | Entity ID                      |
| old_value   | JSON         | Previous data                  |
| new_value   | JSON         | New data                       |
| ip_address  | VARCHAR(50)  | User IP address                |
| created_at  | DATETIME     | Created time                   |

## 3. Relationships

- One role has many users.
- One user can report many incidents.
- One technician can be assigned to many incidents.
- One category has many incidents.
- One incident has many attachments.
- One incident has many comments.
- One incident has many status logs.
- One user has many notifications.
- One user has many audit logs.

## 4. Suggested Spatial Query

Find incidents within 1 kilometer of a specific point:

```
SELECT *
FROM incidents
WHERE deleted_at IS NULL
  AND ST_Distance_Sphere(
    location,
    ST_SRID(POINT(106.7009000, 10.7769000), 4326)
  ) <= 1000;
```

---
