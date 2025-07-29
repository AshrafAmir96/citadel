# API Documentation

Citadel provides a comprehensive RESTful API with OAuth2 authentication using Laravel Passport. All API endpoints follow consistent response formats and include proper error handling.

## 🚀 Quick API Overview

Access the API documentation endpoint for a complete overview:

```http
GET /api/
```

This returns a JSON response with all available endpoints and their descriptions.

## 🔐 Authentication Endpoints

### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "message": "User registered successfully"
}
```

**Error Response (422):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "email": ["The email has already been taken."],
            "password": ["The password confirmation does not match."]
        }
    }
}
```

### Login User
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "message": "User logged in successfully"
}
```

**Error Response (401):**
```json
{
    "success": false,
    "error": {
        "code": "AUTHENTICATION_ERROR",
        "message": "Invalid credentials"
    }
}
```

### Logout User
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "User logged out successfully"
}
```

### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2025-07-28T10:00:00.000000Z",
        "created_at": "2025-07-28T10:00:00.000000Z",
        "updated_at": "2025-07-28T10:00:00.000000Z"
    },
    "message": "User retrieved successfully"
}
```

## 👤 User Management Endpoints

### Get All Users (Admin only)
```http
GET /api/users
Authorization: Bearer {token}
```

**Query Parameters (Laravel Query Builder):**
- `filter[name]=john` - Filter by name
- `filter[email]=example.com` - Filter by email
- `filter[created_at]=2025-07-28` - Filter by creation date
- `sort=name,-created_at` - Sort by name ascending, created_at descending
- `fields[users]=id,name,email` - Select specific fields
- `include=roles,permissions` - Include relationships
- `page[size]=10&page[number]=2` - Pagination

**Example with Query Builder:**
```http
GET /api/users?filter[name]=john&sort=-created_at&include=roles&fields[users]=id,name,email&page[size]=5
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "created_at": "2025-07-28T10:00:00.000000Z",
                "roles": [
                    {
                        "id": 1,
                        "name": "admin",
                        "guard_name": "api"
                    }
                ]
            }
        ],
        "per_page": 5,
        "total": 1,
        "links": {
            "first": "http://localhost:8000/api/users?page=1",
            "last": "http://localhost:8000/api/users?page=1",
            "prev": null,
            "next": null
        }
    },
    "message": "Users retrieved successfully"
}
```

**Error Response (403 - Permission Denied):**
```json
{
    "success": false,
    "error": {
        "code": "PERMISSION_DENIED",
        "message": "You do not have permission to access this resource."
    }
}
```

### Get User by ID
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

**Query Parameters:**
- `fields[users]=id,name,email` - Select specific fields
- `include=roles,permissions` - Include relationships

**Example:**
```http
GET /api/users/1?include=roles,permissions&fields[users]=id,name,email
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "roles": [
            {
                "id": 1,
                "name": "admin",
                "guard_name": "api"
            }
        ],
        "permissions": [
            {
                "id": 1,
                "name": "manage users",
                "guard_name": "api"
            }
        ]
    },
    "message": "User retrieved successfully"
}
```

**Error Response (404 - Not Found):**
```json
{
    "success": false,
    "error": {
        "code": "USER_NOT_FOUND",
        "message": "User not found."
    }
}
```

### Update User Profile
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "email": "updated@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Updated Name",
        "email": "updated@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z",
        "updated_at": "2025-07-28T11:00:00.000000Z"
    },
    "message": "User updated successfully"
}
```

## 🔑 Role & Permission Endpoints

### Assign Role to User
```http
POST /api/users/{id}/roles
Authorization: Bearer {token}
Content-Type: application/json

{
    "role": "admin"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "roles": ["admin"]
    },
    "message": "Role assigned successfully"
}
```

**Error Response (422 - Invalid Role):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "role": ["The selected role is invalid."]
        }
    }
}
```

### Get User Permissions
```http
GET /api/users/{id}/permissions
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "roles": ["admin", "editor"],
        "permissions": ["manage users", "edit posts", "view dashboard"],
        "direct_permissions": ["manage users"]
    },
    "message": "User permissions retrieved successfully"
}
```

## 📁 Media Management Endpoints

### Upload File
```http
POST /api/media
Authorization: Bearer {token}
Content-Type: multipart/form-data

file: [binary data]
collection: "avatars"
```

**Success Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "avatar.jpg",
        "file_name": "avatar.jpg",
        "mime_type": "image/jpeg",
        "size": 2048576,
        "collection_name": "avatars",
        "url": "http://localhost:8000/storage/media/1/avatar.jpg",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "message": "File uploaded successfully"
}
```

**Error Response (422 - File Too Large):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "file": ["The file may not be greater than 10240 kilobytes."]
        }
    }
}
```

**Error Response (500 - Upload Failed):**
```json
{
    "success": false,
    "error": {
        "code": "UPLOAD_ERROR",
        "message": "File upload failed: Disk full"
    }
}
```

### Get Media Files
```http
GET /api/media
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "avatar.jpg",
            "file_name": "avatar.jpg",
            "mime_type": "image/jpeg",
            "size": 2048576,
            "collection_name": "avatars",
            "url": "http://localhost:8000/storage/media/1/avatar.jpg",
            "created_at": "2025-07-28T10:00:00.000000Z"
        }
    ],
    "message": "Media files retrieved successfully"
}
```

### Delete Media File
```http
DELETE /api/media/{id}
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Media file deleted successfully"
}
```

**Error Response (404 - Media Not Found):**
```json
{
    "success": false,
    "error": {
        "code": "MEDIA_NOT_FOUND",
        "message": "Media file not found."
    }
}
```

## 🔍 Search Endpoints

### Search Content
```http
GET /api/search?q={query}&limit=10&offset=0
Authorization: Bearer {token}
```

**Parameters:**
- `q` (required): Search query string (1-255 characters)
- `limit` (optional): Number of results to return (1-100, default: 10)
- `offset` (optional): Number of results to skip (default: 0)

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "results": [
            {
                "type": "user",
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "created_at": "2025-07-28T10:00:00.000000Z"
            }
        ],
        "query": "john",
        "limit": 10,
        "offset": 0,
        "total": 1
    },
    "message": "Search completed successfully"
}
```

**Error Response (422 - Invalid Query):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "q": ["The q field is required."]
        }
    }
}
```

**Error Response (500 - Search Failed):**
```json
{
    "success": false,
    "error": {
        "code": "SEARCH_ERROR",
        "message": "Search failed: Search service unavailable"
    }
}
```

## 📊 API Response Format

All API responses follow a consistent JSON structure for both success and error cases:

**Success Response Structure:**
```json
{
    "success": true,
    "data": { /* Response data */ },
    "message": "Operation completed successfully"
}
```

**Error Response Structure:**
```json
{
    "success": false,
    "error": {
        "code": "ERROR_CODE",
        "message": "Human-readable error message",
        "details": { /* Additional error details, typically validation errors */ }
    }
}
```

**Common Error Codes:**
- `VALIDATION_ERROR` - Input validation failed (422)
- `AUTHENTICATION_ERROR` - Invalid credentials (401)
- `PERMISSION_DENIED` - Insufficient permissions (403)
- `USER_NOT_FOUND` - User does not exist (404)
- `MEDIA_NOT_FOUND` - Media file does not exist (404)
- `UPLOAD_ERROR` - File upload failed (500)
- `SEARCH_ERROR` - Search operation failed (500)

**HTTP Status Codes:**
- `200` - Success
- `201` - Created (for registration and file uploads)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Unprocessable Entity (validation errors)
- `500` - Internal Server Error

## 🔒 API Security

- **OAuth2 Bearer Tokens** - All protected endpoints require valid Bearer token authentication
- **Role-Based Access Control** - Permission checks on sensitive operations (user management)
- **Input Validation** - All request data is validated using Laravel's form request validation
- **Rate Limiting** - API calls are rate-limited to prevent abuse (configurable)
- **CORS Support** - Cross-origin resource sharing with configurable origins
- **CSRF Protection** - Built-in CSRF token validation for web routes
- **SQL Injection Prevention** - Eloquent ORM with parameter binding
- **XSS Protection** - Automatic output escaping and input sanitization

## 📝 API Testing

### Using cURL

**Register a new user:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Get user info (replace TOKEN with actual token):**
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer TOKEN"
```

**Upload a file:**
```bash
curl -X POST http://localhost:8000/api/media \
  -H "Authorization: Bearer TOKEN" \
  -F "file=@/path/to/your/file.jpg" \
  -F "collection=uploads"
```

**Search users:**
```bash
curl -X GET "http://localhost:8000/api/search?q=john&limit=5" \
  -H "Authorization: Bearer TOKEN"
```

### Using Postman

1. Create a new collection called "Citadel API"
2. Set up environment variables:
   - `baseUrl`: `http://localhost:8000`
   - `token`: `{{access_token}}` (will be set after login)
3. Create requests for each endpoint using the examples above
4. Use the login request to automatically set the token for subsequent requests

### Automated Testing

The project includes comprehensive API tests written in Pest PHP:

```bash
# Run all API tests
php artisan test --filter=AuthenticationApiTest

# Run specific test
php artisan test --filter="user can register with valid data"

# Run tests with coverage
php artisan test --coverage --filter=AuthenticationApiTest
```
