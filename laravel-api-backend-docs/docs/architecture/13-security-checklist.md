# Security Checklist

This checklist must be reviewed before production deployment.

## Authentication

- Use Laravel Sanctum or Passport
- Protect private routes using authentication middleware
- Rate-limit login endpoint
- Rate-limit password reset endpoint
- Use secure password hashing
- Invalidate tokens or sessions on logout
- Do not expose password hashes
- Do not return sensitive user fields

## Authorization

- Do not rely only on frontend permission checks
- Enforce permissions in backend
- Use policies for model-specific permissions
- Use middleware for route-level permissions
- Return `403 Forbidden` when authenticated users lack access

## Validation

- Validate every request
- Never trust frontend input
- Use Form Requests
- Use custom validation rules for complex cases
- Validate file uploads carefully

## API Responses

- Do not leak stack traces
- Do not expose internal exception messages in production
- Use consistent error response format
- Return correct HTTP status codes

## Database

- Use migrations
- Use parameter binding
- Avoid raw SQL unless necessary
- Use indexes for frequently queried columns
- Do not store plain-text passwords
- Avoid storing unnecessary sensitive data

## Environment

- Never commit `.env`
- Set `APP_DEBUG=false` in production
- Use strong `APP_KEY`
- Separate local, staging, and production credentials
- Rotate secrets if leaked

## CORS

- Allow only trusted frontend domains
- Do not use wildcard origins in production when credentials are enabled
- Confirm allowed methods and headers

## Cookies

If using cookie-based authentication:

- Use `HttpOnly`
- Use `Secure` in production
- Use proper SameSite policy
- Configure trusted domains correctly

## File Uploads

- Validate file type
- Validate file size
- Store files outside public path when needed
- Rename uploaded files
- Do not trust original filenames

## Production Rule

Security is not a frontend feature. Backend must enforce it.
