# api/v1/lib/openapi_check.php

> API endpoint - 121 lines

FreeITSM REST API v1 — OpenAPI self-check (CLI).

php api/v1/lib/openapi_check.php

Verifies the invariants that keep the generated OpenAPI document valid and
in step with the live API, using no external tooling:
  1. drift    — every route in the table has a docs/spec entry and vice versa
                (so the spec can never silently omit or invent an endpoint)
  2. refs     — every `$ref` resolves to a defined component schema
  3. opIds    — operationIds are present and unique
  4. responses— every operation declares at least one response
  5. shape    — no empty array is emitted where OpenAPI requires an object

Exits non-zero on any failure, so it can gate CI. For full JSON-Schema
conformance, additionally run `spectral lint` or `swagger-cli validate`
against /api/v1/openapi.json in a Node environment.

## The endpoint

| | |
|---|---|
| URL | `api/v1/lib/openapi_check.php` |
| Protected by | REST API key |

## How it hangs together

**Includes:** [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), [api/v1/lib/routes.php](/code/files/api/v1/lib/routes.php.md)

**Calls:** [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)

