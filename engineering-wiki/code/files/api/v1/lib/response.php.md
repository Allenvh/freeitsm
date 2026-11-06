# api/v1/lib/response.php

> API endpoint - 113 lines

FreeITSM REST API v1 — response + input helpers.

Every success body is  { "data": ..., "meta": {...}? }
Every error body is    { "error": { "code": "...", "message": "..." } }
with a real HTTP status code (unlike the internal session AJAX endpoints,
which always return 200 — the v1 surface is for machines, so status codes
carry the outcome).

## The endpoint

| | |
|---|---|
| URL | `api/v1/lib/response.php` |
| Protected by | REST API key |
| Reads | `GET page`, `GET per_page` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiRespond()](/code/functions/apiRespond.md) | 19 | Emit a success response and stop. `$meta` (pagination etc.) is optional. |
| [apiError()](/code/functions/apiError.md) | 37 | Emit an error response and stop. |
| [apiFailFromService()](/code/functions/apiFailFromService.md) | 56 | Emit the API error response for a ServiceError raised by a shared service (see includes/service_context.php). Maps kind -> HTTP status and passes the code + message through verbatim, so a service-backed handler produces the same error body it did when the logic lived inline. Resolves ServiceError / serviceErrorHttpStatus at call time (the calling resource has required them). |
| [apiJsonBody()](/code/functions/apiJsonBody.md) | 64 | Parse the JSON request body. Returns [] for an empty body; 400s on a body that is present but not valid JSON (silent garbage-in is worse than an error). |
| [apiIsoDate()](/code/functions/apiIsoDate.md) | 81 | DB "YYYY-MM-DD HH:MM:SS" (stored UTC) -> ISO 8601 "YYYY-MM-DDTHH:MM:SSZ", or null. |
| [apiParseDate()](/code/functions/apiParseDate.md) | 95 | Parse an incoming date/time filter or field (ISO 8601, with or without Z, or "YYYY-MM-DD HH:MM:SS") into a UTC DB string. 400s on garbage. |
| [apiPagination()](/code/functions/apiPagination.md) | 106 | Pagination from ?page= and ?per_page= (defaults 1 / 25, per_page capped at 100). |

## How it hangs together

**Included by:** [api/mcp/index.php](/code/files/api/mcp/index.php.md), [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md), [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md), [api/v1/lib/bootstrap.php](/code/files/api/v1/lib/bootstrap.php.md), [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)

**Calls:** [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

