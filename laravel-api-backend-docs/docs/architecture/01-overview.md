# Backend Architecture Overview

This Laravel application is a REST API backend intended to serve frontend applications such as Angular, React, mobile apps, or third-party API consumers.

## Goals

- Keep the codebase clean and predictable
- Avoid business logic inside controllers
- Use Laravel conventions where they make sense
- Apply SOLID principles practically
- Keep API responses consistent
- Secure authentication and protected endpoints
- Make the app easy to test and maintain

## Main Request Flow

```txt
Client Request
→ Route
→ Middleware
→ Form Request Validation
→ Controller
→ Action
→ Service
→ Model / Repository
→ API Resource
→ API Response
```

## Key Backend Layers

### Routes

Routes define API entry points.

Example:

```php
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [LoginController::class, 'store']);
});
```

### Middleware

Middleware handles cross-cutting concerns such as:

- Authentication
- Rate limiting
- CORS
- Tenant resolving
- Permission checks
- Request transformation

### Form Requests

Form Requests handle validation and request-level authorization.

### Controllers

Controllers should be thin.

Controller responsibilities:

- Receive validated request
- Call action or service
- Return response

Controllers should not contain:

- Complex business logic
- Raw database queries
- Long conditional workflows
- Password/token generation logic
- Email sending logic

### Actions

Actions represent one business use case.

Examples:

- `RegisterUserAction`
- `LoginUserAction`
- `UpdateProfileAction`
- `SendPasswordResetLinkAction`

### Services

Services contain reusable business logic.

Examples:

- `AuthService`
- `TokenService`
- `FileUploadService`
- `NotificationService`

### Repositories

Repositories are optional.

Use them only when they provide real value, such as complex reusable queries or data-source abstraction.

Do not create repositories blindly for every model.

### API Resources

API Resources transform models into frontend-safe JSON.

Never return raw Eloquent models directly from important API endpoints.

## Recommended Pattern

```txt
Controller
→ FormRequest
→ Action
→ Service
→ Model / Repository
→ Resource
→ ApiResponse
```

## Architecture Rule

If a controller method becomes longer than 15 to 20 lines, it is probably doing too much.
