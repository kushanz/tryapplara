# Error Handling

This document defines how errors should be handled in the Laravel API.

## Goals

- Keep API errors predictable
- Avoid leaking internal details
- Return proper HTTP status codes
- Make frontend error handling simple
- Log unexpected exceptions

## Standard Error Response

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

## Common HTTP Status Codes

```txt
200 OK                  Successful request
201 Created             Resource created
400 Bad Request          Invalid request
401 Unauthorized         Not authenticated
403 Forbidden            Authenticated but not allowed
404 Not Found            Resource not found
422 Unprocessable Entity Validation failed
429 Too Many Requests    Rate limit exceeded
500 Server Error         Unexpected backend error
```

## Rules

- Do not return raw exception messages in production
- Log unexpected exceptions
- Use custom exceptions for expected business errors
- Use validation exceptions for invalid input
- Use authorization exceptions for permission failures

## Example Custom Exception

```php
final class InvalidCredentialsException extends Exception
{
    public function render()
    {
        return ApiResponse::error(
            message: 'Invalid email or password',
            status: 401
        );
    }
}
```

## Frontend-Friendly Error Format

The frontend should always expect:

```txt
success
message
errors
```

That allows Angular interceptors and forms to handle errors consistently.
