# Staff Leave Management System

A production-realistic Internal Web Application built with Laravel 10 for managing staff leave requests.

## Features

- **RBAC (Role Based Access Control):** Strictly separated permissions for Employees, Managers, and Admins.
- **Employee Portal:** Apply for leave, track status, view leave balance vs entitlement, and cancel pending requests.
- **Manager Dashboard:** Review (Approve/Reject) leave applications from assigned team members with comments.
- **Admin Panel:** Full CRUD for users (Employees/Managers), Leave Type management (with entitlement configuration), and global leave status override.
- **Department Integration:** Employees and managers are organized by departments for better organizational structure.
- **Advanced Filtering:** Admin can filter applications by Leave Type, Status, Department, Date Range, and Name/Email.
- **Validation:** Overlap detection for approved leaves and date range validation.

## Tech Stack

- **Backend:** Laravel 10
- **Database:** MySQL
- **Frontend:** Blade, Vanilla JS, Bootstrap 5 (Responsive)
- **Auth:** Laravel Breeze (Session Based)

## Project Setup Instructions

### 1. Installation

```bash
# Clone the repository
git clone https://github.com/shaifali-singh1208/leave_management.git
cd leave_management

# Install dependencies
composer install
npm install
npm run build
```

### 2. Environment Configuration

```bash
# Create .env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leave_management
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Migration & Seeding

```bash
# Create database and run migrations with seeders
php artisan migrate --seed
```

## Login Credentials

| Role         | Email              | Password  |
| ------------ | ------------------ | --------- |
| **Admin**    | admin@gmail.com    | 123456789 |
| **Manager**  | manager@gmail.com  | 12345678  |
| **Employee** | employee@gmail.com | 12345678  |

## Architectural Decisions & Trade-offs

- **Modular Design:** Used Laravel's standard MVC architecture. Separated logic for Employees, Managers, and Admins into dedicated controllers.
- **Middleware Guarding:** Implemented custom middleware (`AdminMiddleware`, `ManagerMiddleware`, `EmployeeMiddleware`) to ensure secure route access.
- **Interactive UI:** Leveraged Bootstrap 5 for a clean, responsive interface and native Modals for leave reviews to avoid page reloads.
- **Database Optimization:** Used Eloquent scopes (`scopeFilter`) for clean and reusable query filtering logic.
- **Validation:** Implemented strict date validation and overlap checks to prevent data inconsistency.

## Bonus Features Implemented

- **CSV Export (Admin Only):** Administrators can export filtered leave application data to CSV.
- **Leave Balance Auto-calculation:** Dynamically tracks "Days Used" vs "Entitlement" for each leave type.
- **Basic API Endpoints:** JSON API for retrieving the leave application list.
    - **Endpoint:** `GET /api/leave-applications`
- **Department Association:** Seamless linking of users to specific organizational departments.
