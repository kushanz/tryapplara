# Database and Migrations

This document defines database rules for the Laravel backend.

## Rules

- Use migrations for all database changes
- Never manually change production database structure
- Keep migrations small and clear
- Use foreign keys where appropriate
- Use indexes for frequently queried columns
- Avoid nullable columns unless the business allows missing values

## Migration Example

```php
Schema::create('profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('job_title')->nullable();
    $table->string('mobile')->nullable();
    $table->timestamps();

    $table->index('mobile');
});
```

## Naming Rules

Good table names:

```txt
users
profiles
orders
order_items
password_reset_tokens
```

Bad table names:

```txt
tbl_users
userData
OrderDetailsTable
```

## Column Rules

Use clear names:

```txt
first_name
last_name
email_verified_at
created_at
updated_at
```

Avoid unclear names:

```txt
fname
lname
flag1
type2
```

## Model Fillable

Always define fillable fields:

```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

## Avoid Mass Assignment Problems

Do not blindly pass request data into models unless validated.

Bad:

```php
User::create($request->all());
```

Good:

```php
User::create($request->validated());
```

## Seeders and Factories

Use factories for test data.

Use seeders for local/default data.

Examples:

```txt
UserFactory
AdminUserSeeder
RoleSeeder
PermissionSeeder
```
