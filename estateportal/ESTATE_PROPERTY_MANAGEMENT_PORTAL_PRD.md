# Estate Property Management Portal
## Product Requirements Document (PRD)

**Document Version:** 1.0  
**Status:** Development Reference / Master Specification  
**Product Type:** Real Estate Property and Estate Management Web Application  
**Primary Stack:** Laravel + MySQL + Blade/Tailwind/Alpine.js or equivalent frontend tooling  
**Primary UI Source:** Existing Google Stitch screens  
**Primary Development Tool:** Google Antigravity  
**Primary Users:** Estate administrators and property customers

---

# 1. Executive Summary

The Estate Property Management Portal is a secure, modern and database-driven web application for managing estates, plots, customers, property allocations, estate layouts and property documents.

The platform has two major experiences:

1. **Customer Portal**
2. **Administrator Portal**

Customers should be able to log in securely and see only estates and plots they own or have valid allocations/subscriptions for. They should be able to visually explore the estate layout, identify their exact plots, view property details, access documents and receive notifications.

Administrators should be able to manage the complete estate/property lifecycle, including creating estates, uploading layouts, creating plots, mapping plots onto estate layouts, managing customers, allocating plots, managing documents, sending notifications and reviewing activity logs.

The application must be a genuine Laravel + MySQL application. The existing Google Stitch screens are the approved visual reference and should be converted into reusable, functional Laravel interfaces rather than replaced with a generic dashboard template.

---

# 2. Product Vision

Create a premium digital property-management platform that makes estate ownership easy to understand and manage.

The core customer promise is:

> **Your property, your estate, your documents, all in one secure place.**

The core administrator promise is:

> **Manage estates, plots, customers and property allocations from one intelligent platform.**

The platform should feel like a combination of:

- Premium real estate technology
- Modern SaaS
- Secure fintech
- Interactive mapping software

The interface should be futuristic and impressive while remaining simple enough for non-technical customers.

---

# 3. Product Goals

## 3.1 Primary Goals

- Provide secure customer authentication.
- Provide secure administrator authentication.
- Give customers a personalized property dashboard.
- Show customers only estates connected to their valid property allocations.
- Display interactive estate layouts.
- Highlight a customer's allocated plots.
- Allow administrators to create and manage estates.
- Allow administrators to create and manage plots.
- Allow administrators to map plots onto estate layouts.
- Allow administrators to allocate plots to customers.
- Prevent duplicate active plot allocations.
- Provide secure property document management.
- Provide customer notifications.
- Maintain an auditable activity log.
- Make the platform responsive across desktop, tablet and mobile.
- Use MySQL as the source of truth for application data.
- Provide automated tests covering critical functionality.

## 3.2 Secondary Goals

- Make the platform scalable to many estates.
- Make adding future estates straightforward.
- Make the plot mapping process usable by non-technical administrators.
- Keep the codebase maintainable and deployment-friendly.
- Provide a clean foundation for future features.

---

# 4. Non-Goals for the Initial MVP

The following are not required unless explicitly added later:

- Online property payment processing.
- Full accounting/ERP functionality.
- Public property marketplace.
- Automated AI plot-boundary detection.
- Blockchain ownership verification.
- GIS-grade surveying.
- Native iOS/Android applications.
- Public customer-to-customer communication.
- Automatic legal verification of land documents.

The architecture should not prevent these features from being added later.

---

# 5. Existing UI/UX Assets

Google Stitch has already generated the approved UI screens.

The project contains:

```text
stitch_property_estate_management_portal/
```

Current screen folders:

```text
activity_logs_estateportal_admin
admin_dashboard_estateportal
admin_settings_estateportal
aetheris_management_system
allocate_plot_estateportal
allocation_details_lot_7_estateportal
create_new_estate_estateportal_admin
customer_dashboard_estateportal
customer_details_michael_njoku_estateportal_admin
customer_login_estateportal
customer_management_estateportal_admin
document_management_estateportal_admin
estate_details_potter_s_house_phase_2
estate_management_estateportal_admin
forgot_password_estateportal
layout_management_potter_s_house_phase_2
my_documents_estateportal
my_estates_estateportal
my_profile_estateportal
my_properties_estateportal
notifications_estateportal
plot_management_estateportal_admin
property_details_lot_7_estateportal
reset_password_estateportal
```

Each screen folder contains:

```text
code.html
screen.png
```

## UI Source-of-Truth Rule

The Stitch screens are the approved design reference.

Developers/AI agents must:

- Inspect every screen before implementation.
- Preserve the established visual language.
- Reuse common components.
- Avoid replacing the design with generic Laravel/Bootstrap/admin templates.
- Convert repeated UI elements into reusable components.
- Use `screen.png` for visual verification.
- Use `code.html` as an implementation reference where appropriate.

The final Laravel UI should remain visually consistent with the Stitch prototype.

---

# 6. Primary User Roles

## 6.1 Customer

A customer can:

- Log in.
- View their dashboard.
- View their estates.
- View their properties.
- View their allocated plots.
- Explore estate layouts.
- See their own plots highlighted.
- View property details.
- View authorized documents.
- Download authorized documents.
- View notifications.
- Manage permitted profile information.
- Change password.
- Log out.

A customer must never be able to access another customer's private property information.

## 6.2 Administrator

An administrator can:

- Log in to the admin portal.
- View platform statistics.
- Create estates.
- Edit estates.
- Manage estate layouts.
- Create plots.
- Edit plots.
- Map plot boundaries.
- Manage plot statuses.
- Create customers.
- Edit customers.
- View customer details.
- Allocate plots.
- Manage allocations.
- Upload documents.
- Manage documents.
- Send/manage notifications.
- View activity logs.
- Manage appropriate settings.

Administrative actions must be protected by server-side authorization.

---

# 7. Core Business Model

The most important data relationship is:

```text
USER
  ↓
PLOT ALLOCATION
  ↓
PLOT
  ↓
ESTATE
```

Do not use a simplistic direct:

```text
User → Estate
```

relationship as the ownership source of truth.

A customer may own multiple plots in one estate and multiple plots across different estates.

Example:

```text
Michael
 ├── Estate A
 │    ├── LOT 7
 │    └── LOT 15
 │
 ├── Estate B
 │    └── LOT 23
 │
 └── Estate C
      ├── LOT 4
      ├── LOT 5
      └── LOT 6
```

The system must derive the customer's estates from valid plot allocations.

---

# 8. Core Customer Journey

```text
LOGIN
 ↓
CUSTOMER DASHBOARD
 ↓
MY ESTATES
 ↓
ESTATE DETAILS
 ↓
INTERACTIVE ESTATE MAP
 ↓
SHOW MY PLOTS
 ↓
SELECT PLOT
 ↓
PROPERTY DETAILS
 ↓
DOCUMENTS / NOTIFICATIONS
```

---

# 9. Core Administrator Journey

```text
ADMIN LOGIN
 ↓
ADMIN DASHBOARD
 ↓
ESTATES
 ↓
CREATE / OPEN ESTATE
 ↓
UPLOAD LAYOUT
 ↓
CREATE / MAP PLOTS
 ↓
CUSTOMERS
 ↓
ALLOCATE PLOT
 ↓
CONFIRM ALLOCATION
 ↓
CUSTOMER RECEIVES PROPERTY
 ↓
NOTIFICATION + ACTIVITY LOG
```

---

# 10. Required Customer Screens

## 10.1 Customer Login

Route concept:

```text
/login
```

Features:

- Username/email
- Password
- Remember me
- Show/hide password
- Forgot password
- Login validation
- Authentication errors
- Secure session handling

## 10.2 Forgot Password

Route concept:

```text
/forgot-password
```

Features:

- Email/username input
- Password reset request
- Validation
- Success state
- Back to login

## 10.3 Reset Password

Route concept:

```text
/reset-password/{token}
```

Features:

- New password
- Confirm password
- Password strength
- Validation
- Success state

## 10.4 Customer Dashboard

Route concept:

```text
/dashboard
```

Display:

- Customer name
- Estate count
- Property count
- Allocated plot count
- Recent activity
- Recent notifications
- My Estates
- My Properties
- Quick actions

All values must be database-driven.

## 10.5 My Estates

Route:

```text
/my-estates
```

Display only estates connected to the customer's valid allocations.

Include:

- Search
- Filters
- Estate cards
- Location
- Property count
- Status
- View Estate

## 10.6 Estate Details

Route:

```text
/estates/{estate}
```

Display:

- Estate name
- Location
- Description
- Total plots
- Customer's plots
- Estate status
- Estate layout
- Customer property cards

## 10.7 Interactive Estate Map

The map is a core feature.

Features:

- Plot polygons
- Plot numbers
- Plot status
- Hover state
- Selected state
- Zoom
- Pan
- Reset
- Fit estate
- Show My Plots
- Search plot where appropriate
- Responsive/touch interaction

Statuses:

- Available
- Reserved
- Allocated
- Pending
- Unavailable

The customer's plots must be clearly highlighted.

## 10.8 Plot Details

Display:

- Plot number
- Plot size
- Estate
- Location
- Status
- Allocation date
- Property reference
- Documents

Another customer's private details must not be displayed.

## 10.9 My Properties

Route:

```text
/my-properties
```

Display all properties belonging to the customer.

Fields:

- Estate
- Plot
- Size
- Status
- Allocation date
- Reference

## 10.10 Property Details

Route concept:

```text
/properties/{allocation}
```

Display:

- Property information
- Estate location
- Plot position
- Documents
- Property timeline

## 10.11 My Documents

Route:

```text
/my-documents
```

Display:

- Document
- Property
- Estate
- Type
- Date
- View
- Download

Only authorized documents may be accessed.

## 10.12 Notifications

Route:

```text
/notifications
```

Features:

- Read/unread
- Notification type
- Message
- Date
- Mark as read
- Mark all as read

## 10.13 Profile

Route:

```text
/profile
```

Features:

- View profile
- Edit permitted fields
- Change password
- Logout

## 10.14 Customer Empty/Error States

Design and implement:

- No estates
- No properties
- No documents
- No notifications
- No search results
- 404
- 403
- 419
- 422
- 429
- 500
- Session expired
- Map loading error
- Document loading error

---

# 11. Required Administrator Screens

## 11.1 Admin Login

Route:

```text
/admin/login
```

Must have secure authentication.

## 11.2 Admin Dashboard

Route:

```text
/admin/dashboard
```

Display:

- Total estates
- Total plots
- Allocated plots
- Available plots
- Reserved plots
- Pending plots
- Total customers
- Allocation statistics
- Estate performance
- Recent allocations
- Recent activity
- Quick actions

## 11.3 Estate Management

Route:

```text
/admin/estates
```

Features:

- List estates
- Search
- Filter
- View
- Edit
- Create
- Manage layout
- Delete/deactivate where safe

## 11.4 Create Estate

Route:

```text
/admin/estates/create
```

Fields:

- Name
- Description
- Location
- City
- LGA
- State
- Country
- Status
- Estate information
- Layout upload

Supported layout formats:

- PDF
- PNG
- JPG
- SVG

## 11.5 Estate Details

Route:

```text
/admin/estates/{estate}
```

Tabs/sections:

- Overview
- Plots
- Layout
- Customers
- Documents
- Activity

## 11.6 Layout Management

Route:

```text
/admin/estates/{estate}/layout
```

Core capabilities:

- View layout
- Upload layout
- Replace layout
- Zoom
- Pan
- Add plot
- Edit plot
- Delete plot
- Draw polygon
- Edit polygon points
- Set plot number
- Set plot size
- Set status
- Save coordinates

Suggested interface:

```text
LEFT SIDEBAR
Tools

CENTER
Estate layout/map

RIGHT SIDEBAR
Selected plot details
```

## 11.7 Plot Management

Route:

```text
/admin/plots
```

Display:

- Plot number
- Estate
- Size
- Status
- Customer
- Allocation date
- Actions

Filters:

- Estate
- Status
- Size
- Customer

## 11.8 Create/Edit Plot

Features:

- Estate
- Plot number
- Plot reference
- Size
- Status
- Coordinates
- Notes

## 11.9 Customer Management

Route:

```text
/admin/customers
```

Display:

- Name
- Username
- Email
- Phone
- Properties
- Estates
- Status
- Date joined

## 11.10 Customer Details

Route:

```text
/admin/customers/{customer}
```

Display:

- Customer profile
- Properties
- Estates
- Documents
- Activity

Admin actions:

- Edit customer
- Allocate plot
- Upload document
- Disable account

## 11.11 Plot Allocation

Route:

```text
/admin/allocations/create
```

Workflow:

1. Select estate.
2. Select available plot.
3. Select customer.
4. Select status.
5. Set allocation date.
6. Add notes.
7. Review.
8. Confirm.

On successful allocation:

- Create allocation record.
- Change plot status.
- Create notification.
- Create activity log.
- Customer sees property.
- Customer sees estate.
- Customer sees plot highlighted on map.

## 11.12 Allocation Details

Display:

- Customer
- Estate
- Plot
- Size
- Status
- Allocation date
- Allocation reference
- Documents
- Activity

## 11.13 Document Management

Features:

- Upload
- View
- Download
- Delete
- Assign to customer/property
- Categorize

## 11.14 Admin Notifications

Allow admins to create/send relevant customer notifications.

## 11.15 Activity Logs

Display:

- Actor
- Action
- Resource
- Description
- Timestamp

Use pagination.

## 11.16 Settings

Sections:

- General
- Security
- Notifications
- Profile
- System preferences

---

# 12. Database Requirements

Use Laravel migrations and MySQL.

Minimum entities:

```text
users
estates
plots
plot_allocations
documents
notifications
activity_logs
```

Additional tables may be created when useful.

---

# 13. Users Table

Suggested fields:

```text
id
name
username
email
phone
password
role
status
profile_photo
email_verified_at
remember_token
created_at
updated_at
```

Roles:

```text
admin
customer
```

Statuses:

```text
active
inactive
```

---

# 14. Estates Table

Suggested fields:

```text
id
name
slug
description
location
city
lga
state
country
status
layout_file
layout_type
total_plots
created_by
created_at
updated_at
```

Statuses:

```text
draft
active
inactive
completed
```

---

# 15. Plots Table

Suggested fields:

```text
id
estate_id
plot_number
plot_reference
size_sqm
status
coordinates
notes
created_at
updated_at
```

Coordinates must support polygon data, preferably JSON.

Example:

```json
[
  {"x":100,"y":200},
  {"x":180,"y":210},
  {"x":190,"y":280},
  {"x":110,"y":270}
]
```

Plot statuses:

```text
available
reserved
allocated
pending
unavailable
```

---

# 16. Plot Allocations Table

Suggested fields:

```text
id
plot_id
user_id
allocation_reference
status
allocated_at
notes
created_by
created_at
updated_at
```

An active plot must not have multiple active owners/allocations.

Use application validation and appropriate database constraints/indexing.

---

# 17. Documents Table

Suggested fields:

```text
id
user_id
plot_id
estate_id
name
document_type
file_path
file_name
mime_type
file_size
uploaded_by
created_at
updated_at
```

Types:

```text
allocation_letter
receipt
survey_plan
contract
agreement
other
```

Documents should use Laravel Storage.

Private documents must not be accessible through predictable public URLs.

---

# 18. Notifications

Use Laravel's notification infrastructure or a database-backed notification model.

Required functionality:

- Create notification
- Display notification
- Read/unread
- Mark as read
- Mark all as read

---

# 19. Activity Logs

Suggested fields:

```text
id
user_id
action
resource_type
resource_id
description
metadata
created_at
updated_at
```

Log important system events.

---

# 20. Estate Layout Architecture

The uploaded estate layout is a visual background/reference.

Ownership is NOT stored in the PDF/image.

The database is the source of truth.

Architecture:

```text
Estate
 ↓
Plot
 ↓
Plot Allocation
 ↓
User
```

The layout stores or references visual information.

Plot coordinates define the interactive boundaries.

This separation is mandatory because it allows the same architecture to support many estates.

---

# 21. Polygon-Based Mapping

Use polygon boundaries rather than simple x/y points for each plot.

Each plot should have a polygon.

Example:

```text
LOT 7
coordinates:
[
    {x: ..., y: ...},
    {x: ..., y: ...},
    {x: ..., y: ...},
    {x: ..., y: ...}
]
```

Coordinates should be normalized against the source layout dimensions so the map remains responsive.

Do not use fixed browser pixel coordinates.

---

# 22. Layout Mapping Workflow

Administrator:

```text
Open Estate
 ↓
Open Layout Manager
 ↓
Upload/View Layout
 ↓
Select "Add Plot"
 ↓
Draw polygon around plot
 ↓
Enter Plot Number
 ↓
Enter Plot Size
 ↓
Select Status
 ↓
Save
```

After saving:

- Plot record is stored in MySQL.
- Polygon coordinates are stored.
- Plot appears on the map.
- Plot can be allocated.
- Plot status changes based on allocation.
- Customer map reflects the database state.

---

# 23. Customer Map Behavior

Map states:

### Customer's own plot

Visually highlighted.

### Another customer's allocated plot

Show:

```text
LOT 8
Status: Allocated
```

Do not show:

- Customer name
- Email
- Phone
- Documents
- Private allocation data

### Available plot

Show:

```text
LOT 10
Status: Available
```

### Reserved plot

Show:

```text
LOT 11
Status: Reserved
```

---

# 24. "Show My Plots" Feature

This is a key customer feature.

When clicked:

- Highlight all customer plots.
- Zoom/focus on their plots.
- Make them visually distinct.
- Allow individual plot selection.

If a customer has multiple plots, all should be highlighted.

---

# 25. Potter's House Phase 2 Demo Estate

Use this as the primary demo estate.

Name:

```text
POTTER'S HOUSE PHASE 2
```

Location:

```text
Off Ibusa/Warri Expressway,
Ibusa Town,
Oshimili North LGA,
Delta State, Nigeria
```

Use approximately 21 plots:

```text
LOT 1
LOT 2
LOT 3
LOT 4
LOT 5
LOT 6
LOT 7
LOT 8
LOT 9
LOT 10
LOT 11
LOT 12
LOT 13
LOT 14
LOT 15
LOT 16
LOT 17
LOT 18
LOT 19
LOT 20
LOT 21
```

Use realistic plot sizes including:

```text
464 sqm
333 sqm
292 sqm
409 sqm
```

Use multiple statuses:

- Available
- Allocated
- Reserved

The actual estate layout asset should be used if available in the project.

Do not invent exact plot geometry if the actual layout asset is available.

---

# 26. Demo Accounts

Create development/test accounts through seeders.

## Demo Administrator

```text
Name: Estate Portal Administrator
Username: admin
Email: admin@estateportal.test
Password: Admin@12345
Role: admin
```

## Demo Customer 1

```text
Name: Michael Njoku
Username: michael
Email: michael@estateportal.test
Password: Michael@12345
Role: customer
```

Michael should have:

```text
Potter's House Phase 2
LOT 7
LOT 15
```

## Demo Customer 2

```text
Name: David Okafor
Username: david
Email: david@estateportal.test
Password: David@12345
Role: customer
```

David should have at least one allocated plot.

These credentials are development/test credentials only.

---

# 27. TEST_ACCOUNTS.md

Create:

```text
TEST_ACCOUNTS.md
```

at the project root.

Include:

- Customer login URL
- Admin login URL
- Dashboard URLs where useful
- Demo credentials
- Assigned demo properties
- Testing scenarios

Do not invent URLs.

Generate URLs based on the actual application `APP_URL` and named routes.

---

# 28. README.md

Create/update:

```text
README.md
```

Include:

- Product overview
- Features
- Technology stack
- Requirements
- Installation
- Environment setup
- MySQL configuration
- Migration
- Seeder instructions
- Storage setup
- Frontend build
- Running locally
- Testing
- Demo accounts
- Main routes
- Deployment guidance
- Architecture overview

---

# 29. Authentication Requirements

Implement:

- Login
- Logout
- Password reset
- Session regeneration
- Secure password hashing
- Rate limiting
- Validation
- Authorization
- Role protection

Never store plaintext passwords.

---

# 30. Authorization Requirements

Server-side authorization is mandatory.

Customers must not be able to:

- Access admin routes.
- View another customer's properties.
- View another customer's documents.
- Download another customer's documents.
- Modify another customer's allocation.
- Access unauthorized estate data.

Implement Laravel Policies, Gates and middleware where appropriate.

Potential policies:

```text
EstatePolicy
PlotPolicy
PlotAllocationPolicy
DocumentPolicy
PropertyPolicy
CustomerPolicy
```

Do not rely only on frontend visibility.

---

# 31. Validation Requirements

Validate all forms.

Examples:

- Required fields
- Unique username
- Unique email where applicable
- Password rules
- Estate fields
- Plot number
- Plot size
- Plot status
- Allocation
- File uploads
- Polygon coordinates

Display validation errors using the existing Stitch UI.

---

# 32. File Management

Use Laravel Storage.

Estate layouts:

```text
PDF
PNG
JPG
SVG
```

Property documents:

```text
PDF
Images
Other approved document types
```

Validate file types and file sizes.

Private documents must require authorization before viewing/downloading.

---

# 33. UI Component Architecture

Convert repeated Stitch elements into reusable components.

Potential components:

```text
Button
Card
StatCard
Badge
Modal
Drawer
Input
Select
Textarea
Table
Pagination
Sidebar
Topbar
Notification
EstateCard
PlotCard
DocumentCard
PropertyCard
MapControls
PlotTooltip
EmptyState
LoadingState
ErrorState
```

Use:

```text
resources/views/components/
```

and appropriate layouts.

---

# 34. Layout Architecture

Use separate layouts for:

```text
Authentication
Customer
Admin
```

Suggested structure:

```text
resources/views/
    layouts/
        auth.blade.php
        customer.blade.php
        admin.blade.php

    components/

    auth/

    customer/

    admin/
```

Keep business logic out of Blade.

---

# 35. Recommended Laravel Architecture

Use:

```text
app/
    Models/
    Http/
        Controllers/
        Requests/
        Middleware/
    Policies/
    Notifications/
    Services/
```

Use Form Request classes for complex validation.

Use service classes for complex business logic such as plot allocation.

Example:

```text
PlotAllocationService
EstateLayoutService
DocumentService
NotificationService
ActivityLogService
```

Do not put all business logic inside controllers.

---

# 36. Routes

Customer routes should include approximately:

```text
/login
/forgot-password
/reset-password/{token}
/dashboard
/my-estates
/estates/{estate}
/my-properties
/properties/{allocation}
/documents
/documents/{document}
/notifications
/profile
```

Admin routes:

```text
/admin/login
/admin/dashboard
/admin/estates
/admin/estates/create
/admin/estates/{estate}
/admin/estates/{estate}/layout
/admin/plots
/admin/customers
/admin/customers/{customer}
/admin/allocations
/admin/allocations/create
/admin/allocations/{allocation}
/admin/documents
/admin/notifications
/admin/activity-logs
/admin/settings
```

Use named routes.

Use route model binding where appropriate.

Always authorize bound resources.

---

# 37. Dashboard Data

No dashboard statistic should be hardcoded.

Customer dashboard should calculate:

- Estates
- Properties
- Allocations
- Notifications
- Recent activity

Admin dashboard should calculate:

- Estates
- Plots
- Customers
- Allocations
- Status distribution
- Recent activity

Use efficient queries and eager loading.

---

# 38. Search, Filtering and Pagination

Implement where appropriate.

Customer:

- Estates
- Properties
- Documents
- Notifications

Admin:

- Estates
- Plots
- Customers
- Allocations
- Documents
- Activity logs

Use server-side filtering for large datasets.

Use Laravel pagination.

---

# 39. Performance Requirements

Avoid N+1 queries.

Use:

```text
Eager loading
Database indexes
Pagination
Query optimization
Caching where useful
Lazy loading of heavy assets
Image optimization
```

The map must remain responsive for estates containing hundreds of plots.

Only load the relevant estate's plot data.

---

# 40. Responsive Requirements

The application must work on:

- Desktop
- Laptop
- Tablet
- Mobile

Mobile should not simply be a scaled-down desktop.

Pay particular attention to:

- Sidebar
- Tables
- Forms
- Interactive map
- Plot details
- Admin layout editor
- Documents
- Charts

The map must support touch interaction.

---

# 41. Accessibility Requirements

Use:

- Accessible contrast
- Clear labels
- Keyboard focus states
- Proper form labels
- Adequate touch targets
- Meaningful button labels
- Error messages
- Status indicators that do not rely solely on color

---

# 42. Micro-interactions

Use subtle animations for:

- Page transitions
- Hover states
- Plot selection
- Map zoom
- Drawers
- Modals
- Toasts
- Loading states

Avoid excessive animation.

The application should feel premium, not like a gaming interface.

---

# 43. Required Statuses

Estate:

```text
draft
active
inactive
completed
```

Plot:

```text
available
reserved
allocated
pending
unavailable
```

User:

```text
active
inactive
```

Allocation:

```text
pending
active
completed
cancelled
```

---

# 44. Critical Business Rules

## Rule 1

A customer can own multiple plots.

## Rule 2

A customer can own plots in multiple estates.

## Rule 3

An estate contains multiple plots.

## Rule 4

A plot belongs to exactly one estate.

## Rule 5

A plot cannot have multiple simultaneous active allocations.

## Rule 6

Only authorized administrators can allocate plots.

## Rule 7

Customers can only see their own detailed property information.

## Rule 8

Customers can only download documents they are authorized to access.

## Rule 9

When a plot is allocated, its status must update appropriately.

## Rule 10

A successful allocation must create an activity record.

## Rule 11

A successful allocation should create a customer notification.

## Rule 12

The customer dashboard must reflect the allocation automatically through database relationships.

---

# 45. Critical Allocation Workflow

Initial state:

```text
LOT 7 = available
Michael = no LOT 7 allocation
```

Admin allocates LOT 7 to Michael.

Expected database/application results:

```text
LOT 7 = allocated

Michael owns LOT 7

Michael sees Potter's House Phase 2

Michael sees LOT 7 under My Properties

LOT 7 is highlighted on the estate map

Michael receives an allocation notification

An activity log is created
```

Then attempt to allocate LOT 7 to David.

Expected:

```text
Allocation rejected
```

because LOT 7 already has an active allocation.

This business rule must be covered by automated tests.

---

# 46. Automated Testing Requirements

Use:

```text
Pest or PHPUnit
```

and browser automation where supported.

Tests must cover:

## Authentication

- Customer login
- Admin login
- Invalid login
- Logout
- Password reset

## Authorization

- Customer cannot access admin
- Customer cannot access another customer's property
- Customer cannot access another customer's documents
- Admin can access admin functions
- Unauthenticated users are redirected

## Estates

- Create estate
- Edit estate
- View estate
- Estate relationships

## Plots

- Create plot
- Edit plot
- Plot belongs to estate
- Status changes

## Allocations

- Allocate plot
- Prevent duplicate active allocation
- Correct customer ownership
- Correct estate visibility
- Correct plot status
- Notification creation
- Activity log creation

## Documents

- Upload
- View
- Download
- Authorization

## Notifications

- Creation
- Read/unread
- Mark as read

## Activity Logs

- Important actions create logs

---

# 47. Browser Testing

If browser automation is available, test:

## Customer

```text
Open login
 ↓
Login as Michael
 ↓
Dashboard
 ↓
My Estates
 ↓
Potter's House Phase 2
 ↓
Estate Map
 ↓
Verify LOT 7 highlighted
 ↓
Click LOT 7
 ↓
Property details
 ↓
Documents
 ↓
Notifications
 ↓
Profile
```

## Admin

```text
Admin login
 ↓
Dashboard
 ↓
Estate management
 ↓
Potter's House Phase 2
 ↓
Plots
 ↓
Layout Management
 ↓
Customers
 ↓
Allocate Plot
 ↓
Confirm
 ↓
Verify database/application state
```

---

# 48. Clean Installation Test

The project must be rebuildable from a clean environment.

Verify:

```text
composer install
```

Configure `.env`.

Then:

```text
php artisan migrate:fresh --seed
```

Then:

```text
php artisan storage:link
```

Then install/build frontend dependencies where required.

The project should start successfully.

---

# 49. Code Quality Requirements

Before completion:

- Check PHP syntax.
- Check Laravel routes.
- Check migrations.
- Check models.
- Check controllers.
- Check policies.
- Check Blade templates.
- Check JavaScript.
- Check CSS/build.
- Check browser console.
- Check broken links.
- Check undefined variables.
- Check missing routes.
- Check missing database columns.
- Check failed queries.
- Check authorization.

No critical errors should remain.

---

# 50. No Fake Functionality

Do not leave buttons that appear functional but do nothing.

Every button/action must either:

- Perform a real operation.
- Navigate to a real implemented route.
- Open a real implemented modal/drawer.
- Submit a real backend action.

Avoid:

```text
TODO
Coming Soon
Mock API
Fake data
Dead buttons
```

unless explicitly required as a visual state.

---

# 51. Environment Configuration

Create:

```text
.env.example
```

Include appropriate variables for:

```text
APP_NAME
APP_ENV
APP_KEY
APP_DEBUG
APP_URL

DB_CONNECTION
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

MAIL configuration
FILESYSTEM configuration
```

Never commit production credentials.

---

# 52. Security Requirements

Implement Laravel best practices:

- CSRF protection
- Secure password hashing
- Authorization
- Validation
- Rate limiting
- Session protection
- Mass assignment protection
- Secure file handling
- SQL injection protection
- XSS protection
- Secure authentication
- Resource authorization

Do not expose sensitive errors in production.

---

# 53. Error Handling

Implement appropriate pages/states for:

```text
404
403
419
422
429
500
```

Handle:

- Missing estate
- Missing plot
- Missing property
- Missing document
- Missing layout
- Unauthorized access
- Invalid form submissions
- Failed uploads
- Database exceptions
- Session expiration

---

# 54. Seed Data Requirements

Seed:

- 1 admin
- At least 2 customers
- 1 primary estate
- 21 plots
- Multiple plot statuses
- Multiple allocations
- Sample documents
- Sample notifications
- Sample activity logs

The database should be useful immediately after:

```text
php artisan migrate --seed
```

---

# 55. Future Scalability

The system should be designed so a new estate can be added without code changes.

Example:

```text
Estate A
21 plots

Estate B
100 plots

Estate C
500 plots
```

Each estate can have:

- Its own layout
- Its own plots
- Its own plot coordinates
- Its own customers/allocations
- Its own documents

The same application logic must handle all estates.

---

# 56. UI Verification

After implementation, compare the Laravel pages with the Stitch reference screenshots.

Verify:

- Overall layout
- Spacing
- Typography
- Cards
- Navigation
- Forms
- Tables
- Map
- Status badges
- Responsive behavior

Make corrections where the Laravel implementation significantly deviates from the approved Stitch design.

---

# 57. Deliverables

The completed project must contain:

```text
Complete Laravel application
MySQL migrations
Eloquent models
Controllers
Form Requests
Policies
Services where appropriate
Blade layouts
Reusable Blade components
Customer portal
Admin portal
Authentication
Estate management
Plot management
Interactive map
Layout management
Plot allocation
Property details
Document management
Notifications
Activity logs
Profile management
Responsive UI
Seeders
Factories where useful
Automated tests
Browser tests where supported
TEST_ACCOUNTS.md
README.md
.env.example
```

---

# 58. Final Acceptance Criteria

The project is considered complete only if all of the following are true:

## Customer

- Customer can log in.
- Customer can log out.
- Customer can reset password.
- Customer dashboard works.
- Customer only sees their estates.
- Customer only sees their properties.
- Customer can view interactive layouts.
- Customer's plots are highlighted.
- Customer can view property details.
- Customer can access authorized documents.
- Customer can view notifications.
- Customer can manage permitted profile information.

## Admin

- Admin can log in.
- Admin dashboard works.
- Admin can create estates.
- Admin can edit estates.
- Admin can upload layouts.
- Admin can create plots.
- Admin can map plot polygons.
- Admin can manage plots.
- Admin can create customers.
- Admin can manage customers.
- Admin can allocate plots.
- Duplicate active allocations are prevented.
- Admin can manage documents.
- Admin can create notifications.
- Admin can view activity logs.
- Admin can manage settings.

## Technical

- MySQL is used.
- Database relationships work.
- No important data is hardcoded.
- Authorization is enforced server-side.
- Documents are protected.
- Responsive UI works.
- Automated tests pass.
- Frontend builds successfully.
- No critical console errors.
- No broken major routes.
- Clean database migration and seeding works.

---

# 59. Required Test Scenarios

The final QA process must explicitly test:

### Scenario 1
Admin logs in.

### Scenario 2
Admin views Potter's House Phase 2.

### Scenario 3
Admin sees 21 plots.

### Scenario 4
Admin views plot statuses.

### Scenario 5
Admin creates a customer.

### Scenario 6
Admin allocates LOT 7 to Michael.

### Scenario 7
Michael logs in.

### Scenario 8
Michael sees Potter's House Phase 2.

### Scenario 9
Michael sees LOT 7 in My Properties.

### Scenario 10
LOT 7 is highlighted on the map.

### Scenario 11
Michael opens LOT 7 details.

### Scenario 12
Michael accesses his documents.

### Scenario 13
Michael receives the allocation notification.

### Scenario 14
The activity log records the allocation.

### Scenario 15
David attempts to access Michael's property.

Expected:

```text
Access denied.
```

### Scenario 16
Admin attempts to allocate already allocated LOT 7 to David.

Expected:

```text
Allocation rejected.
```

### Scenario 17
David logs in and sees only his own properties.

### Scenario 18
Unauthenticated user attempts to access admin dashboard.

Expected:

```text
Redirect to login.
```

---

# 60. Development Workflow

Follow this sequence.

## Phase 1: Discovery

- Inspect project structure.
- Inspect all Stitch folders.
- Inspect all `code.html` files.
- Inspect all `screen.png` references.
- Identify reusable UI.
- Identify navigation.
- Identify all states.

## Phase 2: Architecture

- Configure Laravel.
- Configure MySQL.
- Design migrations.
- Design models.
- Define relationships.
- Define policies.
- Define routes.

## Phase 3: Authentication

- Customer authentication.
- Admin authentication.
- Password reset.
- Authorization.

## Phase 4: Customer Portal

- Dashboard.
- Estates.
- Estate details.
- Map.
- Properties.
- Documents.
- Notifications.
- Profile.

## Phase 5: Admin Portal

- Dashboard.
- Estates.
- Layout management.
- Plots.
- Customers.
- Allocations.
- Documents.
- Notifications.
- Activity logs.
- Settings.

## Phase 6: Interactive Map

- Layout rendering.
- Plot polygons.
- Plot status.
- Plot selection.
- Plot highlighting.
- Show My Plots.
- Admin mapping.

## Phase 7: Seed Data

- Demo accounts.
- Demo estate.
- 21 plots.
- Allocations.
- Documents.
- Notifications.
- Activity logs.

## Phase 8: Testing

- Unit tests.
- Feature tests.
- Authorization tests.
- Business logic tests.
- Browser tests where supported.

## Phase 9: QA

- UI comparison.
- Responsive testing.
- Console error inspection.
- Route inspection.
- Database verification.
- Security verification.

## Phase 10: Documentation

Create:

```text
README.md
TEST_ACCOUNTS.md
```

---

# 61. Important AI Development Rules

When using this PRD as a reference:

1. Treat it as the master product specification.
2. Do not remove core requirements without a clear technical reason.
3. Do not replace the approved Stitch UI with a generic design.
4. Do not hardcode production functionality.
5. Do not invent missing database relationships.
6. Do not expose private customer data.
7. Do not claim a feature works without testing it.
8. Do not claim tests pass unless they were actually executed.
9. Fix discovered errors rather than ignoring them.
10. Keep the architecture scalable.
11. Prefer reusable components.
12. Keep business logic out of Blade templates.
13. Use MySQL as the source of truth.
14. Use Laravel authorization for protected resources.
15. Keep the application deployment-friendly.
16. Preserve the existing Stitch reference assets.
17. Build real functionality rather than a static prototype.

---

# 62. Definition of Done

The project is DONE when:

```text
[✓] Stitch screens inspected
[✓] Laravel project configured
[✓] MySQL configured
[✓] Migrations created
[✓] Models created
[✓] Relationships implemented
[✓] Authentication implemented
[✓] Customer portal implemented
[✓] Admin portal implemented
[✓] Estate management implemented
[✓] Plot management implemented
[✓] Interactive mapping implemented
[✓] Layout mapping implemented
[✓] Plot allocation implemented
[✓] Duplicate allocation prevention implemented
[✓] Documents implemented
[✓] Notifications implemented
[✓] Activity logs implemented
[✓] Authorization implemented
[✓] Validation implemented
[✓] Error handling implemented
[✓] Responsive UI implemented
[✓] Demo data seeded
[✓] Demo accounts created
[✓] TEST_ACCOUNTS.md created
[✓] README.md created
[✓] Automated tests created
[✓] Automated tests executed
[✓] Browser tests executed where supported
[✓] Errors fixed
[✓] Final tests executed again
[✓] Final application verified
```

Do not mark an item complete merely because code exists. It should be considered complete only after implementation and verification.

---

# 63. Final Product Principle

The most important principle of this project is:

> **The UI should represent the real database state.**

If a plot is allocated in MySQL, the application should automatically reflect that everywhere relevant.

If a plot becomes available, the map should reflect it.

If a customer receives a new allocation, their estate list, property list, map, dashboard, notification center and property details should update from the underlying database relationships.

The application should never require manually editing multiple screens to keep property information synchronized.

The final system should feel like one connected product, not a collection of individual pages.

---

# 64. Final Expected Architecture

```text
                    ESTATE PORTAL
                         |
          +--------------+--------------+
          |                             |
     CUSTOMER PORTAL              ADMIN PORTAL
          |                             |
     Authentication               Authentication
          |                             |
     Dashboard                     Dashboard
          |                             |
     My Estates                    Estates
          |                             |
     Estate Details                Layout Manager
          |                             |
   Interactive Map                 Plots
          |                             |
    Property Details              Customers
          |                             |
     Documents                    Allocations
          |                             |
    Notifications                Documents
          |                             |
       Profile                  Notifications
                                      |
                                 Activity Logs
                                      |
                                   Settings
                         |
                    Laravel Backend
                         |
                    Business Logic
                         |
                     Eloquent ORM
                         |
                       MySQL
                         |
               Secure File Storage
```

The final product must be a complete, maintainable, secure and tested Laravel application built around this architecture.
