# Laravel API Backend Documentation

This documentation package defines the backend architecture, coding standards, authentication strategy, API response format, validation rules, security checklist, and testing guidelines for a Laravel API application.

## Suggested Usage

Copy the `docs` folder into the root of your Laravel repository.

```txt
your-laravel-app/
  app/
  bootstrap/
  config/
  database/
  docs/
    architecture/
  routes/
  tests/
```

## Recommended Reading Order

1. `01-overview.md`
2. `02-folder-structure.md`
3. `03-request-lifecycle.md`
4. `09-authentication.md`
5. `07-api-response-standard.md`
6. `08-validation.md`
7. `04-solid-principles.md`
8. `05-service-action-repository-pattern.md`
9. `13-security-checklist.md`

## Backend Direction

This Laravel app is designed as a REST API backend for frontend applications such as Angular.

Core principles:

- Keep controllers thin
- Use Form Requests for validation
- Use Actions for use-case workflows
- Use Services for reusable business logic
- Use API Resources for response transformation
- Use Laravel Sanctum for first-stage API authentication
- Follow SOLID principles without over-engineering
