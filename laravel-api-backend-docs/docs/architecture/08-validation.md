# Validation

Validation should be handled using Laravel Form Request classes.

## Why Form Requests?

Form Requests keep controllers clean and reusable.

## Bad

```php
public function store(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // login logic
}
```

## Good

```php
public function store(LoginRequest $request)
{
    $data = $request->validated();

    // login logic
}
```

## Example LoginRequest

```php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
```

## Example RegisterRequest

```php
final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
```

## Validation Rules

- Validate every external input
- Never trust frontend validation alone
- Use `required`, `string`, `email`, `exists`, `unique`, `max`, `min`
- Use custom Rule classes for complex validation
- Keep business validation outside controllers

## Business Rule vs Input Validation

Input validation:

```txt
email is required
password must be at least 8 characters
status must be active or inactive
```

Business validation:

```txt
user account is locked
subscription is expired
user cannot approve own request
```

Business validation belongs in Actions or Services.
