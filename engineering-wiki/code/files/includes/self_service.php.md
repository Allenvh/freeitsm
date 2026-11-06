# includes/self_service.php

> PHP - 17 lines

Shared self-service portal helpers.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [selfServiceRegistrationEnabled()](/code/functions/selfServiceRegistrationEnabled.md) | 12 | Is self-service self-registration allowed? Defaults to FALSE (disabled) — an install must deliberately turn it on under System → Security. Off by default because open sign-up is an attack surface most desks don't need; when on, the email-confirmation flow (register.php) still applies. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 13 | `selfServiceRegistrationEnabled` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Included by:** [api/self-service/register.php](/code/files/api/self-service/register.php.md), [self-service/login.php](/code/files/self-service/login.php.md), [self-service/register.php](/code/files/self-service/register.php.md)

