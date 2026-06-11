# Request Lifecycle

This document explains how an API request should flow through the Laravel backend.

## Standard Request Flow

```txt
Frontend
→ API Route
→ Middleware
→ Form Request
→ Controller
→ Action
→ Service
→ Model / Repository
→ API Resource
→ API Response
```

## Example: Login Request

```txt
POST /api/v1/auth/login
```

Flow:

```txt
LoginRequest
→ LoginController
→ LoginUserAction
→ AuthService
→ User Model
→ UserResource
→ ApiResponse
```

## Step 1: Route

```php
Route::post('/auth/login', [LoginController::class, 'store']);
```

## Step 2: Form Request

```php
class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
```

## Step 3: Controller

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

## Step 4: Action

```php
final class LoginUserAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function execute(array $data): array
    {
        return $this->authService->login(
            email: $data['email'],
            password: $data['password']
        );
    }
}
```

## Step 5: Service

```php
final class AuthService
{
    public function login(string $email, string $password): array
    {
        // Check credentials
        // Create token
        // Return authenticated user data
    }
}
```

## Step 6: Response

```php
return ApiResponse::success([
    'user' => new UserResource($user),
    'token' => $token,
], 'Login successful');
```

## Rules

- Routes should not contain business logic
- Controllers should not contain business logic
- Form Requests should validate input
- Actions should coordinate use cases
- Services should contain reusable business logic
- Resources should shape output
