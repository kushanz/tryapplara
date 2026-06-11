# SOLID Principles in Laravel

SOLID principles are useful only when they make the code easier to change, test, and understand.

Do not use SOLID as an excuse to create unnecessary layers.

## S - Single Responsibility Principle

A class should have one reason to change.

### Bad

```php
class AuthController
{
    public function login(Request $request)
    {
        // validate input
        // check user credentials
        // create token
        // send login notification
        // format response
    }
}
```

This controller has too many responsibilities.

### Good

```txt
LoginRequest       → validates input
LoginController    → handles HTTP layer
LoginUserAction    → coordinates login use case
AuthService        → handles auth logic
UserResource       → formats user response
```

## O - Open/Closed Principle

Classes should be open for extension but closed for modification.

Example:

Instead of hardcoding notification logic:

```php
class NotificationService
{
    public function send(string $type)
    {
        if ($type === 'email') {
            // send email
        }

        if ($type === 'sms') {
            // send sms
        }
    }
}
```

Prefer separate implementations:

```php
interface NotificationChannel
{
    public function send(string $message): void;
}
```

Then create:

```txt
EmailNotificationChannel
SmsNotificationChannel
```

## L - Liskov Substitution Principle

Implementations should be replaceable without breaking behavior.

Example:

```php
interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
}
```

Both implementations should behave consistently:

```txt
EloquentUserRepository
ExternalUserRepository
```

## I - Interface Segregation Principle

Do not create huge interfaces.

### Bad

```php
interface UserRepositoryInterface
{
    public function findByEmail(string $email);
    public function uploadAvatar();
    public function sendWelcomeEmail();
    public function generateInvoice();
}
```

This interface is doing too much.

### Good

Split responsibilities:

```txt
UserRepositoryInterface
AvatarServiceInterface
WelcomeEmailServiceInterface
BillingServiceInterface
```

## D - Dependency Inversion Principle

High-level modules should depend on abstractions, not concrete implementations.

### Example

```php
final class LoginUserAction
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {}
}
```

Bind the implementation in a service provider:

```php
$this->app->bind(
    UserRepositoryInterface::class,
    EloquentUserRepository::class
);
```

## Practical Rule

Apply SOLID when it reduces future pain.

Do not create interfaces for everything on day one.

Create interfaces when:

- Multiple implementations are expected
- Testing is difficult without abstraction
- External services are involved
- The dependency is volatile
