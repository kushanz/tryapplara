# Authorization

Authentication answers:

```txt
Who is the user?
```

Authorization answers:

```txt
What is the user allowed to do?
```

## Authorization Tools in Laravel

Use:

- Policies
- Gates
- Middleware
- Form Request `authorize()` method

## Policy Example

```php
final class UserPolicy
{
    public function update(User $authUser, User $targetUser): bool
    {
        return $authUser->id === $targetUser->id;
    }
}
```

## Controller Example

```php
public function update(UpdateProfileRequest $request, User $user)
{
    $this->authorize('update', $user);

    // update user
}
```

## Form Request Authorization

```php
public function authorize(): bool
{
    return $this->user()->id === (int) $this->route('user');
}
```

## Role-Based Authorization

For simple systems:

```txt
admin
manager
user
```

For more complex systems, consider permissions:

```txt
users.view
users.create
users.update
users.delete
```

## Recommended Rules

- Do not check permissions only in frontend
- Backend must enforce all permission rules
- Use policies for model-specific authorization
- Use middleware for route-level access
- Use Form Request `authorize()` for request-level checks
- Keep role checks readable
