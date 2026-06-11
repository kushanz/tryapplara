# Coding Standards

This document defines coding standards for the Laravel backend.

## General Rules

- Use strict typing where practical
- Use meaningful names
- Keep classes focused
- Keep methods small
- Avoid duplicated logic
- Avoid large controllers
- Avoid magic strings where constants/enums make sense

## Naming

### Controllers

```txt
LoginController
RegisterController
ProfileController
```

### Requests

```txt
LoginRequest
RegisterRequest
UpdateProfileRequest
```

### Actions

```txt
LoginUserAction
RegisterUserAction
UpdateProfileAction
```

### Services

```txt
AuthService
TokenService
FileUploadService
```

### Repositories

```txt
UserRepositoryInterface
EloquentUserRepository
```

## Controller Rules

Controllers should:

- Accept request
- Call action/service
- Return response

Controllers should not:

- Run complex queries
- Handle long business workflows
- Send emails directly
- Create tokens directly
- Contain large if/else blocks

## Service Rules

Services should:

- Contain reusable business logic
- Be testable
- Avoid depending directly on request objects

## Action Rules

Actions should:

- Represent one use case
- Have one public execute method
- Coordinate services and repositories

## Formatting

Use Laravel Pint for code style.

```bash
./vendor/bin/pint
```

## Static Analysis

Consider PHPStan or Larastan for larger projects.

## Bad Code Smells

Watch for:

```txt
Controller method longer than 20 lines
Repeated validation rules
Repeated response structures
Repeated query logic
Too many responsibilities in one service
Service named HelperService
Classes ending with Manager without clear responsibility
```

## Rule

Simple code wins until complexity proves it needs architecture.
