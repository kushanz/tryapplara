# Authentication

This document defines the authentication strategy for the Laravel API backend.

## Recommended Package

Use Laravel Sanctum for the first version of the API.

Sanctum is suitable for:

- Angular SPA authentication
- Token-based API authentication
- First-party frontend applications
- Simple mobile API authentication

Use Laravel Passport only when the application must act as a full OAuth2 server.

## Authentication Endpoints

```txt
POST   /api/v1/auth/register
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
GET    /api/v1/auth/me
POST   /api/v1/auth/forgot-password
POST   /api/v1/auth/reset-password
POST   /api/v1/auth/change-password
```

## Route Example

```php
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [RegisterController::class, 'store']);
        Route::post('/login', [LoginController::class, 'store']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthUserController::class, 'show']);
        Route::post('/auth/logout', [LogoutController::class, 'destroy']);
        Route::post('/auth/change-password', [ChangePasswordController::class, 'update']);
    });
});
```

## Login Response Example

```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "plain-text-token"
  }
}
```

## Angular SPA Auth Options

### Option 1: Token-Based Auth

Angular stores the token and sends it in the Authorization header.

```txt
Authorization: Bearer token_here
```

Pros:

- Simple
- Works for mobile and external clients
- Easy to debug

Cons:

- Storing tokens in browser storage has security risk
- Must handle token expiration carefully

### Option 2: Cookie-Based SPA Auth

Laravel stores session/auth state using secure cookies.

Pros:

- Better for first-party SPA security
- Can use HttpOnly cookies
- Token is not directly accessible from JavaScript

Cons:

- CORS and cookie config must be correct
- Frontend and backend domain setup matters

## Recommendation

For a first-party Angular frontend and Laravel backend under trusted domains, prefer cookie-based Sanctum authentication.

For public APIs, mobile apps, or third-party clients, use token-based Sanctum.

## Security Rules

- Use HTTPS in production
- Never expose password hashes
- Never return raw User model
- Rate-limit login endpoint
- Invalidate token/session on logout
- Protect private routes with `auth:sanctum`
- Do not store sensitive auth data in localStorage when avoidable

## Login Flow

```txt
Angular login form
→ POST /api/v1/auth/login
→ Laravel validates credentials
→ Laravel returns user and token/session
→ Angular stores safe auth state
→ Angular calls protected APIs
```

## Logout Flow

```txt
Angular clicks logout
→ POST /api/v1/auth/logout
→ Laravel deletes token/session
→ Angular clears local user state
→ Redirect to login
```
