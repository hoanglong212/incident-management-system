# API Design

## 1. Authentication APIs

### POST /api/auth/register

Register a new user.

Request body:

```
{
  "name": "Long Nguyen",
  "email": "long@example.com",
  "password": "password123",
  "phone": "0900000000"
}
```

Response:

```
{
  "message": "Register successfully"
}
```

### POST /api/auth/login

Login user.

Request body:

```
{
  "email": "long@example.com",
  "password": "password123"
}
```

Response:

```
{
  "token": "jwt_token",
  "user": {
    "id": 1,
    "name": "Long Nguyen",
    "role": "USER"
  }
}
```

### GET /api/auth/me

Get current authenticated user.

## 2. Incident APIs

### GET /api/incidents

Get incident list.

Query parameters:

- status
- category_id
- priority
- assigned_to
- reporter_id
- from_date
- to_date
- keyword
- page
- limit

### POST /api/incidents

Create new incident.

Request body:

```
{
  "title": "Camera at Gate A is not working",
  "description": "The camera screen is black and cannot record.",
  "category_id": 1,
  "priority": "HIGH",
  "address": "Gate A, Campus",
  "latitude": 10.7769000,
  "longitude": 106.7009000,
  "occurred_at": "2026-06-18 09:00:00"
}
```

### GET /api/incidents/:id

Get incident detail.

### PUT /api/incidents/:id

Update incident basic information.

### DELETE /api/incidents/:id

Soft delete incident.

## 3. Attachment APIs

### POST /api/incidents/:id/attachments

Upload evidence image.

Rules:

- Only JPG, JPEG, PNG, WebP.
- Maximum file size should be defined in backend config.
- Only users with permission can upload.

## 4. Comment APIs

### GET /api/incidents/:id/comments

Get comments of an incident.

### POST /api/incidents/:id/comments

Add comment to an incident.

Request body:

```
{
  "content": "I have checked the incident location."
}
```

## 5. Assignment APIs

### PUT /api/incidents/:id/assign

Assign incident to technician.

Request body:

```
{
  "technician_id": 5,
  "note": "Please check this issue today."
}
```

## 6. Status APIs

### PUT /api/incidents/:id/status

Update incident status.

Request body:

```
{
  "status": "IN_PROGRESS",
  "note": "Technician has started checking the issue."
}
```

Backend must validate state transition before updating.

## 7. Dashboard APIs

### GET /api/dashboard/summary

Return summary cards.

### GET /api/dashboard/incidents-by-category

Return incident count grouped by category.

### GET /api/dashboard/incidents-by-status

Return incident count grouped by status.

### GET /api/dashboard/incidents-by-priority

Return incident count grouped by priority.

### GET /api/dashboard/map-incidents

Return incident markers for map dashboard.

## 8. Report APIs

### GET /api/reports/incidents/export

Export filtered incidents as CSV or Excel.

Query parameters:

- status
- category_id
- priority
- from_date
- to_date
