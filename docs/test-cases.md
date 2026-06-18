# Test Cases

## TC01 - Register Successfully

Precondition:

- Email does not exist in the system.

Steps:

1. Open register page.
2. Enter valid name, email, password, and phone.
3. Click register.

Expected result:

- Account is created successfully.
- User can login with the new account.

## TC02 - Login Successfully

Precondition:

- User account already exists.

Steps:

1. Open login page.
2. Enter valid email and password.
3. Click login.

Expected result:

- User logs in successfully.
- JWT token is stored.
- User is redirected to the correct dashboard based on role.

## TC03 - Login with Wrong Password

Steps:

1. Enter valid email.
2. Enter wrong password.
3. Click login.

Expected result:

- System displays error message.
- User is not logged in.

## TC04 - Create Incident Successfully

Precondition:

- User is logged in.

Steps:

1. Open create incident page.
2. Enter title, description, category, priority, address.
3. Select location on map.
4. Submit form.

Expected result:

- New incident is created.
- Incident status is `NEW`.
- Incident appears in user's ticket list.

## TC05 - Create Incident Missing Required Field

Steps:

1. Open create incident page.
2. Leave title empty.
3. Submit form.

Expected result:

- System displays validation error.
- Incident is not created.

## TC06 - Upload Valid Image

Precondition:

- Incident exists.

Steps:

1. Open incident detail.
2. Upload a valid PNG/JPG image.

Expected result:

- Image is uploaded successfully.
- Image appears in incident detail.

## TC07 - Upload Invalid File Type

Steps:

1. Upload an unsupported file type.

Expected result:

- System rejects the file.
- Error message is displayed.

## TC08 - Admin Assigns Technician

Precondition:

- Admin is logged in.
- Incident status is `NEW`.

Steps:

1. Open incident detail.
2. Select technician.
3. Click assign.

Expected result:

- Incident is assigned to selected technician.
- Status changes from `NEW` to `ASSIGNED`.
- Status log is created.

## TC09 - Technician Updates Status to In Progress

Precondition:

- Technician is assigned to the incident.

Steps:

1. Technician opens assigned incident.
2. Change status to `IN_PROGRESS`.
3. Add note.
4. Submit.

Expected result:

- Status is updated.
- Status log is created.

## TC10 - Invalid Status Transition

Precondition:

- Incident status is `NEW`.

Steps:

1. Try to change status directly from `NEW` to `RESOLVED`.

Expected result:

- Backend rejects the request.
- Incident status remains `NEW`.

## TC11 - Comment on Incident

Precondition:

- User has permission to view incident.

Steps:

1. Open incident detail.
2. Enter comment.
3. Submit comment.

Expected result:

- Comment is saved.
- Comment appears in incident detail.

## TC12 - Soft Delete Incident

Precondition:

- Admin is logged in.
- Incident exists.

Steps:

1. Admin deletes incident.

Expected result:

- Incident `deleted_at` is updated.
- Incident no longer appears in normal list.
- Data still exists in database.

## TC13 - Dashboard Summary

Precondition:

- System has sample incident data.

Steps:

1. Manager opens dashboard.

Expected result:

- Dashboard shows correct total incidents.
- Counts by status, priority, and category are displayed.

## TC14 - Map Dashboard

Precondition:

- Incidents have latitude and longitude.

Steps:

1. Admin opens map dashboard.

Expected result:

- Incident markers appear on map.
- Clicking marker shows incident information.

## TC15 - Export Report

Precondition:

- Manager is logged in.

Steps:

1. Open report page.
2. Select filters.
3. Click export CSV/Excel.

Expected result:

- File is downloaded.
- Exported data matches selected filters.
