# Folder Structure

This document defines the recommended folder structure for the Laravel API backend.

## Recommended Structure

```txt
app/
  Actions/
    Auth/
      LoginUserAction.php
      RegisterUserAction.php
      LogoutUserAction.php

  DTOs/
    Auth/
      LoginData.php
      RegisterData.php

  Exceptions/

  Http/
    Controllers/
      Api/
        Auth/
          LoginController.php
          RegisterController.php
          LogoutController.php
          AuthUserController.php

    Middleware/

    Requests/
      Auth/
        LoginRequest.php
        RegisterRequest.php

    Resources/
      UserResource.php

  Models/
    User.php

  Repositories/
    Contracts/
      UserRepositoryInterface.php

    Eloquent/
      EloquentUserRepository.php

  Services/
    Auth/
      AuthService.php
      TokenService.php

routes/
  api.php

tests/
  Feature/
    Auth/
      LoginTest.php
      RegisterTest.php

  Unit/
    Services/
      AuthServiceTest.php
```

## Folder Responsibilities

## `app/Http/Controllers`

Controllers handle HTTP input and output.

Good controller behavior:

```php
public function store(LoginRequest $request, LoginUserAction $action)
{
    $result = $action->execute($request->validated());

    return ApiResponse::success($result, 'Login successful');
}
```

Bad controller behavior:

```php
public function store(Request $request)
{
    // validation
    // database queries
    // password checking
    // token generation
    // response formatting
    // audit logging
}
```

That is not a controller. That is a junk drawer.

## `app/Http/Requests`

Use Form Requests for validation.

Examples:

- `LoginRequest`
- `RegisterRequest`
- `UpdateProfileRequest`
- `ChangePasswordRequest`

## `app/Actions`

Actions represent specific use cases.

Examples:

- `LoginUserAction`
- `RegisterUserAction`
- `UpdateUserProfileAction`
- `SendPasswordResetLinkAction`

Use Actions when a business process has multiple steps.

## `app/Services`

Services contain reusable business logic.

Examples:

- `AuthService`
- `TokenService`
- `EmailService`
- `FileUploadService`

Use Services when logic is reused across multiple Actions or Controllers.

## `app/Repositories`

Repositories are optional.

Use repositories when:

- Query logic is repeated
- Query logic is complex
- Data source may change
- Testing needs abstraction

Avoid repositories when:

- It only wraps `User::find($id)`
- It adds no real abstraction
- It makes simple code harder to understand

## `app/DTOs`

DTOs keep structured data clean between layers.

Example:

```php
final readonly class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
```

## `app/Http/Resources`

Resources transform API response data.

Example:

```php
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
```

## Rule

Do not fight Laravel conventions. Extend them carefully.
