# Testing Strategy

Testing protects architecture from slowly rotting.

## Test Types

```txt
Feature Tests → API endpoints and workflows
Unit Tests    → isolated services/actions
```

## Recommended Test Structure

```txt
tests/
  Feature/
    Auth/
      LoginTest.php
      RegisterTest.php
      LogoutTest.php

    Profile/
      UpdateProfileTest.php

  Unit/
    Services/
      AuthServiceTest.php

    Actions/
      LoginUserActionTest.php
```

## What to Test First

Start with high-value API flows:

```txt
register user
login user
logout user
get authenticated user
update profile
validation errors
unauthorized access
forbidden access
```

## Login Feature Test Example

```php
it('allows a user to login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user',
                'token',
            ],
        ]);
});
```

## Validation Test Example

```php
it('requires email during login', function () {
    $response = $this->postJson('/api/v1/auth/login', [
        'password' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
```

## Testing Rules

- Test behavior, not implementation details
- Test API response shape
- Test validation errors
- Test unauthorized access
- Test permission failures
- Use factories instead of hardcoded records
- Do not depend on real external services in tests

## Minimum Test Coverage for New Feature

Every new API feature should include:

```txt
happy path test
validation failure test
unauthenticated test
authorization failure test, if applicable
```
