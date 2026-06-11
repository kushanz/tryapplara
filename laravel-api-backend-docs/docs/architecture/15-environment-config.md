# Environment Configuration

This document defines environment configuration rules.

## Environment Files

Laravel uses `.env` for environment-specific values.

Do not commit `.env`.

Commit only:

```txt
.env.example
```

## Required Config Areas

```txt
APP_NAME
APP_ENV
APP_KEY
APP_DEBUG
APP_URL

DB_CONNECTION
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

FRONTEND_URL
SANCTUM_STATEFUL_DOMAINS
SESSION_DOMAIN

MAIL_MAILER
MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_PASSWORD

QUEUE_CONNECTION
CACHE_STORE
```

## Local Example

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

FRONTEND_URL=http://localhost:4200
```

## Production Rules

```env
APP_ENV=production
APP_DEBUG=false
```

## Security Rules

- Never commit secrets
- Never expose production credentials in documentation
- Use different credentials per environment
- Rotate leaked secrets immediately
- Keep `.env.example` updated without real secrets

## Config Usage

Use config files instead of calling `env()` everywhere.

Bad:

```php
$value = env('FRONTEND_URL');
```

Good:

```php
$value = config('app.frontend_url');
```

Then define in config:

```php
'frontend_url' => env('FRONTEND_URL'),
```

## CORS and Sanctum

For Angular SPA cookie-based authentication, confirm:

```txt
FRONTEND_URL
SANCTUM_STATEFUL_DOMAINS
SESSION_DOMAIN
CORS allowed origins
supports_credentials
```

Bad CORS configuration can make authentication look broken even when login works.
