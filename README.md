# Employee Tracker — Laravel Sanctum REST API

A secure, token-based REST API built with Laravel 13 and Laravel Sanctum for the Employee Tracker system. This API handles employee authentication, screenshot management, activity tracking, configuration management, and user data modules.

---

## Tech Stack

| Technology | Purpose |
|------------|---------|
| Laravel 13 | PHP Backend Framework |
| Laravel Sanctum | API Token Authentication |
| Laravel Storage | File Management |
| MySQL | Database |
| PHP 8.4 | Programming Language |
| Composer | Dependency Manager |

---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/merryzonish/employee-tracker.git
cd employee-tracker
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_tracker
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed the Database (10 Predefined Users)
```bash
php artisan db:seed
```

### 6. Create Storage Symlink
```bash
php artisan storage:link
```

This command links `public/storage` to `storage/app/public` so uploaded files are accessible via URL.

### 7. Start the Development Server
```bash
php artisan serve
```

Server will run at: `http://127.0.0.1:8000`

---

## Storage Configuration

This project uses **Laravel Storage** with the `public` disk for managing files.

- Screenshots are stored in: `storage/app/public/screenshots/{user_email}/`
- Media files are stored in: `public/media/{user_email}/`
- Files are accessible via: `http://127.0.0.1:8000/storage/screenshots/{user_email}/{filename}`
- All file operations use Laravel's `Storage` facade

---

## API Endpoints

### Base URL
```
http://127.0.0.1:8000/api
```

---

### 1. Login

**Endpoint:** `POST /api/login`

**Description:** Authenticates a user and returns a Sanctum API token.

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "email": "ali@company.com",
    "password": "password123"
}
```

**Success Response** `200 OK`:
```json
{
    "message": "Login successful",
    "token": "1|your_generated_token_here",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "Ali Hassan",
        "email": "ali@company.com",
        "created_at": "2026-06-04T10:49:05.000000Z",
        "updated_at": "2026-06-04T10:49:05.000000Z"
    }
}
```

**Error Response** `401 Unauthorized`:
```json
{
    "message": "Invalid credentials"
}
```

---

### 2. Logout

**Endpoint:** `POST /api/logout`

**Description:** Revokes the current user's API token. Respects global and user-level logout restrictions.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Success Response** `200 OK`:
```json
{
    "message": "Logged out successfully"
}
```

**Error Response** `403 Forbidden`:
```json
{
    "message": "Logout is restricted for your account."
}
```

---

### 3. Store Screenshot (Automatic Upload)

**Endpoint:** `POST /api/screenshots/store`

**Description:** Receives a screenshot file from the client application and saves it to Laravel Storage. Used for automatic periodic uploads based on a configured interval.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Request Body:** `form-data`

| Key | Type | Value |
|-----|------|-------|
| screenshot | File | image file (png, jpg, jpeg, max 5MB) |
| screenshot_time | Text | 2026-06-05 14:30:00 |

**Success Response** `201 Created`:
```json
{
    "message": "Screenshot uploaded successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "file_path": "screenshots/ali@company.com/filename.png",
        "screenshot_time": "2026-06-05 14:30:00",
        "created_at": "2026-06-05T04:11:26.000000Z",
        "updated_at": "2026-06-05T04:11:26.000000Z"
    }
}
```

---

### 4. Capture Screenshot (Real-Time)

**Endpoint:** `POST /api/screenshots/capture`

**Description:** Admin triggers a real-time screenshot capture for a specific user.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Request Body:** `form-data`

| Key | Type | Value |
|-----|------|-------|
| user_id | Text | 2 |
| screenshot | File | image file (png, jpg, jpeg, max 5MB) |

**Success Response** `201 Created`:
```json
{
    "message": "Real-time screenshot captured successfully",
    "data": {
        "id": 2,
        "user_id": 2,
        "file_path": "screenshots/sara@company.com/capture_filename.png",
        "screenshot_time": "2026-06-05T04:18:30.000000Z",
        "created_at": "2026-06-05T04:18:30.000000Z",
        "updated_at": "2026-06-05T04:18:30.000000Z"
    }
}
```

---

### 5. Fetch Screenshots

**Endpoint:** `GET /api/screenshots`

**Description:** Returns paginated screenshot records for the authenticated user, sorted by latest first. Supports optional date filtering.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Optional Query Parameter:**
```
?date=2026-06-05
```

**Success Response** `200 OK`:
```json
{
    "message": "Screenshots fetched successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "file_path": "screenshots/ali@company.com/filename.png",
                "screenshot_time": "2026-06-05 14:30:00",
                "created_at": "2026-06-05T04:11:26.000000Z",
                "updated_at": "2026-06-05T04:11:26.000000Z"
            }
        ],
        "total": 1,
        "per_page": 10,
        "last_page": 1
    }
}
```

---

### 6. Delete Screenshot

**Endpoint:** `POST /api/screenshots/delete`

**Description:** Deletes a specific screenshot record and its associated file from storage.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "id": 1
}
```

**Success Response** `200 OK`:
```json
{
    "message": "Screenshot deleted successfully"
}
```

---

### 7. Track Activity

**Endpoint:** `POST /api/track/activity`

**Description:** Receives activity data from the desktop tracker application.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "app_name": "Visual Studio Code",
    "window_title": "UserActivityController.php",
    "clicks": 25,
    "keystrokes": 120,
    "is_idle": false,
    "tracked_at": "2026-06-07 12:00:00"
}
```

**Success Response** `201 Created`:
```json
{
    "message": "Activity tracked successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "app_name": "Visual Studio Code",
        "window_title": "UserActivityController.php",
        "clicks": 25,
        "keystrokes": 120,
        "is_idle": false,
        "tracked_at": "2026-06-07T12:00:00.000000Z",
        "created_at": "2026-06-07T11:35:07.000000Z",
        "updated_at": "2026-06-07T11:35:07.000000Z"
    }
}
```

---

### 8. Activity Stats

**Endpoint:** `GET /api/track/activity/stats`

**Description:** Returns paginated activity records and summary stats. Supports optional date filtering.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Optional Query Parameter:**
```
?date=2026-06-07
```

**Success Response** `200 OK`:
```json
{
    "message": "Activity stats fetched successfully",
    "summary": {
        "total_clicks": "25",
        "total_keystrokes": "120",
        "idle_count": "0",
        "active_count": "1"
    },
    "activities": {
        "current_page": 1,
        "data": [],
        "total": 1,
        "per_page": 10,
        "last_page": 1
    }
}
```

---

### 9. List Configurations

**Endpoint:** `GET /api/configs`

**Description:** Returns all system configurations.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Configurations fetched successfully",
    "data": {
        "tracker_screenshot_interval": 5,
        "tracker_api_url": "https://api.example.com",
        "tracker_admin_password": "secret-password",
        "tracker_allowed_ips": ["192.168.1.10", "10.0.0.5"],
        "tracker_logout_restriction": true
    }
}
```

---

### 10. Create Configuration

**Endpoint:** `POST /api/configs`

**Description:** Creates a new system configuration entry.

**Supported Keys:** `tracker_screenshot_interval`, `tracker_api_url`, `tracker_admin_password`, `tracker_allowed_ips`, `tracker_logout_restriction`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "key": "tracker_screenshot_interval",
    "value": 5
}
```

**Success Response** `201 Created`:
```json
{
    "message": "Configuration created successfully",
    "data": {
        "id": 1,
        "key": "tracker_screenshot_interval",
        "value": 5,
        "created_at": "2026-06-09T10:50:20.000000Z",
        "updated_at": "2026-06-09T10:50:20.000000Z"
    }
}
```

---

### 11. Update Configuration

**Endpoint:** `PUT /api/configs/{key}`

**Description:** Updates an existing configuration by its key.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "value": 10
}
```

**Success Response** `200 OK`:
```json
{
    "message": "Configuration updated successfully",
    "data": {
        "id": 1,
        "key": "tracker_screenshot_interval",
        "value": 10,
        "created_at": "2026-06-09T10:50:20.000000Z",
        "updated_at": "2026-06-09T10:55:04.000000Z"
    }
}
```

---

### 12. List User Contacts

**Endpoint:** `GET /api/contacts`

**Description:** Returns all contacts for the authenticated user.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Contacts fetched successfully",
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "type": "work_email",
            "label": "Office Email",
            "value": "ali@company.com",
            "is_primary": true,
            "is_verified": false,
            "verified_at": null,
            "notes": "Primary work email",
            "created_at": "2026-06-13T19:01:30.000000Z",
            "updated_at": "2026-06-13T19:01:30.000000Z"
        }
    ]
}
```

---

### 13. Create User Contact

**Endpoint:** `POST /api/contacts`

**Description:** Stores a new contact record for the authenticated user.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "type": "work_email",
    "label": "Office Email",
    "value": "ali@company.com",
    "is_primary": true,
    "notes": "Primary work email"
}
```

**Success Response** `201 Created`:
```json
{
    "message": "Contact created successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "type": "work_email",
        "label": "Office Email",
        "value": "ali@company.com",
        "is_primary": true,
        "notes": "Primary work email",
        "created_at": "2026-06-13T19:01:30.000000Z",
        "updated_at": "2026-06-13T19:01:30.000000Z"
    }
}
```

---

### 14. Update User Contact

**Endpoint:** `PUT /api/contacts/{id}`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "value": "ali.hassan@company.com",
    "label": "Updated Office Email"
}
```

**Success Response** `200 OK`:
```json
{
    "message": "Contact updated successfully",
    "data": {
        "id": 1,
        "type": "work_email",
        "label": "Updated Office Email",
        "value": "ali.hassan@company.com",
        "updated_at": "2026-06-13T19:04:15.000000Z"
    }
}
```

---

### 15. Delete User Contact

**Endpoint:** `DELETE /api/contacts/{id}`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Contact deleted successfully"
}
```

---

### 16. List Categories

**Endpoint:** `GET /api/categories`

**Description:** Returns all active parent categories with their children.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Categories fetched successfully",
    "data": [
        {
            "id": 1,
            "name": "Profile Picture",
            "slug": "profile-picture",
            "type": "media",
            "parent_id": null,
            "description": "User profile pictures",
            "is_active": true,
            "children": []
        }
    ]
}
```

---

### 17. Create Category

**Endpoint:** `POST /api/categories`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "name": "Resume",
    "type": "media",
    "description": "User resume files"
}
```

**Success Response** `201 Created`:
```json
{
    "message": "Category created successfully",
    "data": {
        "id": 2,
        "name": "Resume",
        "slug": "resume",
        "type": "media",
        "parent_id": null,
        "description": "User resume files",
        "is_active": true,
        "created_at": "2026-06-13T19:01:46.000000Z",
        "updated_at": "2026-06-13T19:01:46.000000Z"
    }
}
```

---

### 18. Update Category

**Endpoint:** `PUT /api/categories/{id}`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

**Request Body:**
```json
{
    "name": "Updated Resume",
    "is_active": false
}
```

**Success Response** `200 OK`:
```json
{
    "message": "Category updated successfully",
    "data": {
        "id": 2,
        "name": "Updated Resume",
        "slug": "updated-resume",
        "is_active": false,
        "updated_at": "2026-06-13T20:00:00.000000Z"
    }
}
```

---

### 19. Delete Category

**Endpoint:** `DELETE /api/categories/{id}`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Category deleted successfully"
}
```

---

### 20. List Media

**Endpoint:** `GET /api/media`

**Description:** Returns paginated media records for the authenticated user with category details.

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Media fetched successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "file_path": "media/ali@company.com/filename.png",
                "category_id": 2,
                "model_type": null,
                "model_id": null,
                "category": {
                    "id": 2,
                    "name": "Resume",
                    "slug": "resume"
                }
            }
        ],
        "total": 1,
        "per_page": 10,
        "last_page": 1
    }
}
```

---

### 21. Upload Media

**Endpoint:** `POST /api/media`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Request Body:** `form-data`

| Key | Type | Value |
|-----|------|-------|
| file | File | any file (max 10MB) |
| category_id | Text | 2 |
| model_type | Text | App\Models\User (optional) |
| model_id | Text | 1 (optional) |

**Success Response** `201 Created`:
```json
{
    "message": "Media uploaded successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "file_path": "media/ali@company.com/filename.png",
        "category_id": 2,
        "model_type": null,
        "model_id": null,
        "created_at": "2026-06-13T19:06:01.000000Z",
        "updated_at": "2026-06-13T19:06:01.000000Z"
    }
}
```

---

### 22. Delete Media

**Endpoint:** `DELETE /api/media/{id}`

**Request Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response** `200 OK`:
```json
{
    "message": "Media deleted successfully"
}
```

---

## IP Validation

The system supports two levels of IP restrictions:

- **Global IPs** — stored in `tracker_allowed_ips` config — applies to all users
- **User-specific IPs** — stored in `users.allowed_ips` — applies to specific user only

Both must pass when configured. Tracker routes are protected by the `validate.ip` middleware.

---

## Logout Restriction

A user can logout only if:
1. Global `tracker_logout_restriction` is disabled
2. OR global restriction is enabled AND user's `can_user_logout` is `true`

---

## Authentication

This API uses Laravel Sanctum for token-based authentication.

1. Call `POST /api/login` with valid credentials
2. Copy the `token` from the response
3. Add it to the `Authorization` header of all protected requests:
```
Authorization: Bearer {your_token_here}
```

---

## Predefined Test Users

All users have the default password: `password123`

| # | Name | Email |
|---|------|-------|
| 1 | Ali Hassan | ali@company.com |
| 2 | Sara Khan | sara@company.com |
| 3 | Usman Ahmed | usman@company.com |
| 4 | Fatima Noor | fatima@company.com |
| 5 | Bilal Raza | bilal@company.com |
| 6 | Ayesha Malik | ayesha@company.com |
| 7 | Hamza Sheikh | hamza@company.com |
| 8 | Zara Hussain | zara@company.com |
| 9 | Omar Farooq | omar@company.com |
| 10 | Hina Baig | hina@company.com |

---

## Database Tables

| Table | Purpose |
|-------|---------|
| users | Stores employee accounts |
| personal_access_tokens | Sanctum API tokens |
| user_screenshots | Screenshot records with file paths |
| user_activities | Employee activity tracking data |
| configs | System configuration settings |
| user_bank_accounts | Employee bank account details |
| user_contacts | Employee contact information |
| categories | File and media categories |
| media | Uploaded media files |

---

## Project Structure

```
employee-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php              # Login & Logout logic
│   │   │   ├── CategoryController.php          # Category CRUD
│   │   │   ├── ConfigController.php            # Configuration management
│   │   │   ├── MediaController.php             # Media upload & management
│   │   │   ├── UserActivityController.php      # Activity tracking & stats
│   │   │   ├── UserBankAccountController.php   # Bank account management
│   │   │   ├── UserContactController.php       # Contact management
│   │   │   └── UserScreenshotController.php    # Screenshot management
│   │   └── Middleware/
│   │       └── ValidateTrackerIp.php           # IP validation middleware
│   └── Models/
│       ├── Category.php                        # Category model
│       ├── Config.php                          # Config model
│       ├── Media.php                           # Media model
│       ├── User.php                            # User model with Sanctum
│       ├── UserActivity.php                    # Activity model
│       ├── UserBankAccount.php                 # Bank account model
│       ├── UserContact.php                     # Contact model
│       └── UserScreenshot.php                  # Screenshot model
├── database/
│   ├── migrations/                             # All database migrations
│   └── seeders/
│       ├── DatabaseSeeder.php                  # Runs all seeders
│       └── UserSeeder.php                      # 10 predefined users
├── routes/
│   └── api.php                                 # All API routes
├── storage/
│   └── app/
│       └── public/
│           └── screenshots/                    # Screenshot files
│               └── {user_email}/               # Organized by user email
└── config/
    └── sanctum.php                             # Sanctum configuration
```

---

## API Routes Summary

| Method | Endpoint | Description | Auth | IP Check |
|--------|----------|-------------|------|----------|
| POST | /api/login | User login | No | No |
| POST | /api/logout | User logout | Yes | No |
| GET | /api/configs | List configs | Yes | No |
| POST | /api/configs | Create config | Yes | No |
| PUT | /api/configs/{key} | Update config | Yes | No |
| GET | /api/contacts | List contacts | Yes | No |
| POST | /api/contacts | Create contact | Yes | No |
| PUT | /api/contacts/{id} | Update contact | Yes | No |
| DELETE | /api/contacts/{id} | Delete contact | Yes | No |
| GET | /api/categories | List categories | Yes | No |
| POST | /api/categories | Create category | Yes | No |
| PUT | /api/categories/{id} | Update category | Yes | No |
| DELETE | /api/categories/{id} | Delete category | Yes | No |
| GET | /api/media | List media | Yes | No |
| POST | /api/media | Upload media | Yes | No |
| DELETE | /api/media/{id} | Delete media | Yes | No |
| GET | /api/screenshots | Fetch screenshots | Yes | Yes |
| POST | /api/screenshots/store | Upload screenshot | Yes | Yes |
| POST | /api/screenshots/capture | Real-time capture | Yes | Yes |
| POST | /api/screenshots/delete | Delete screenshot | Yes | Yes |
| POST | /api/track/activity | Track activity | Yes | Yes |
| GET | /api/track/activity/stats | Activity stats | Yes | Yes |

---

## Quick Command Reference

```bash
# Install dependencies
composer install

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Run migrations and seed together
php artisan migrate --seed

# Create storage symlink
php artisan storage:link

# Start server
php artisan serve
```

---

## Developer

**Merry Zonish**
GitHub: [@merryzonish](https://github.com/merryzonish)