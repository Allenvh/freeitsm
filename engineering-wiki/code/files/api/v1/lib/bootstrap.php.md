# api/v1/lib/bootstrap.php

> API endpoint - 39 lines

FreeITSM REST API v1 — bootstrap.

Shared boot for every v1 request: config + core includes, JSON/CORS headers,
and a last-resort exception handler so an unexpected error still returns a
well-formed JSON error instead of an HTML stack trace.

The legacy machine-ingest endpoints under api/external/ are untouched by all
of this — v1 is a separate, versioned surface with its own key store.

## The endpoint

| | |
|---|---|
| URL | `api/v1/lib/bootstrap.php` |
| Protected by | REST API key |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md)

