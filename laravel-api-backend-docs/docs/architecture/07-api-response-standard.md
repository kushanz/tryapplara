# API Response Standard

All API responses must follow a consistent format.

## Success Response

```json
{
  "success": true,
  "message": "Request completed successfully",
  "data": {}
}
```

## Error Response

```json
{
  "success": false,
  "message": "Something went wrong",
  "errors": {}
}
```

## Validation Error Response

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

## Paginated Response

```json
{
  "success": true,
  "message": "Users fetched successfully",
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "last_page": 10
  }
}
```

## Suggested ApiResponse Helper

Create:

```txt
app/Support/ApiResponse.php
```

Example:

```php
namespace App\Support;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error(
        string $message = 'Something went wrong',
        mixed $errors = null,
        int $status = 400
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
```

## Rules

- Do not return raw Eloquent models directly
- Use API Resources for model transformation
- Use proper HTTP status codes
- Keep response shape predictable
- Do not mix response styles between endpoints

## Bad

```php
return User::all();
```

## Good

```php
return ApiResponse::success(
    UserResource::collection($users),
    'Users fetched successfully'
);
```
