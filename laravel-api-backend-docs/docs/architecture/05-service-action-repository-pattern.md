# Service, Action, and Repository Pattern

This document explains when to use Controllers, Actions, Services, and Repositories.

## Simple Summary

```txt
Controller  → HTTP layer
Action      → one use case
Service     → reusable business logic
Repository  → data access abstraction
Model       → Eloquent entity
Resource    → API output transformation
```

## Controller

A controller should be boring.

Responsibilities:

- Accept request
- Call action/service
- Return response

Example:

```php
final class LoginController
{
    public function store(LoginRequest $request, LoginUserAction $action)
    {
        $result = $action->execute($request->validated());

        return ApiResponse::success($result, 'Login successful');
    }
}
```

## Action

An Action represents one business operation.

Examples:

```txt
RegisterUserAction
LoginUserAction
LogoutUserAction
UpdateProfileAction
CreateOrderAction
```

Example:

```php
final class RegisterUserAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function execute(array $data): array
    {
        return $this->authService->register($data);
    }
}
```

Use Actions when:

- The operation has multiple steps
- The logic is specific to one use case
- You want the controller to stay clean

## Service

A Service contains reusable business logic.

Examples:

```txt
AuthService
TokenService
FileUploadService
EmailVerificationService
```

Example:

```php
final class AuthService
{
    public function register(array $data): array
    {
        // create user
        // create token
        // return data
    }
}
```

Use Services when:

- Logic is reused
- Logic does not belong to one controller
- Logic interacts with external systems
- Logic is complex enough to test separately

## Repository

A Repository handles data access abstraction.

Example:

```php
interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function create(array $data): User;
}
```

Implementation:

```php
final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
}
```

## Important Warning

Do not blindly create a repository for every model.

This is bad:

```php
$user = $this->userRepository->find($id);
```

When it only wraps this:

```php
$user = User::find($id);
```

That is fake architecture.

Use repositories only when they earn their place.

## Recommended Usage

For small features:

```txt
Controller → FormRequest → Service → Model → Resource
```

For medium/large features:

```txt
Controller → FormRequest → Action → Service → Repository → Resource
```
