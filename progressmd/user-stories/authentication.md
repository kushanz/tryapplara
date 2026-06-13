# Authentication User Story

## Story Reference

- Story ID: `US-AUTH-001`
- Module: Authentication
- Status: Implemented

## Story

As an API user,
I want to register, log in, manage my session, reset my password, and verify my email,
So that I can securely access protected parts of the application.

## Planned Endpoints

- `POST /api/register`
- `POST /api/login`
- `GET /api/me`
- `POST /api/logout`
- `POST /api/forgot-password`
- `POST /api/reset-password`
- `POST /api/email/verification-notification`
- `GET /api/verify-email/{id}/{hash}`

## Related Tables

- `users`
- `password_reset_tokens`
- `personal_access_tokens`

## Related Architecture Docs

- `../../laravel-api-backend-docs/docs/architecture/01-overview.md`
- `../../laravel-api-backend-docs/docs/architecture/02-folder-structure.md`
- `../../laravel-api-backend-docs/docs/architecture/05-service-action-repository-pattern.md`
- `../../laravel-api-backend-docs/docs/architecture/07-api-response-standard.md`
- `../../laravel-api-backend-docs/docs/architecture/08-validation.md`
- `../../laravel-api-backend-docs/docs/architecture/09-authentication.md`
- `../../laravel-api-backend-docs/docs/architecture/12-testing-strategy.md`
- `../../laravel-api-backend-docs/docs/architecture/13-security-checklist.md`

## Notes

- Laravel Sanctum is used for API token authentication
- Email verification is supported through signed verification links
- Password reset is designed for API-first frontend integration
- Protected endpoints use `auth:sanctum`
- API responses follow the shared success/error response structure
- Swagger/OpenAPI documentation is available in `docs/swagger/auth.openapi.json`
- API bearer tokens expire after `1 hour` by default through `SANCTUM_TOKEN_EXPIRATION=60`
- `POST /api/login` returns only the token payload; user profile data is fetched through `GET /api/me`
- User roles are stored in `users.role` with `admin`, `manager`, and `customer`
- Sanctum token abilities are issued from the user role, such as `users:read` and `users:update`
