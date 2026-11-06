# api/v1/dev/openapi_fix.php

> API endpoint - 147 lines

Auto-fix the typed OpenAPI schemas against LIVE responses: add fields the serializers return but the schema omits (type inferred from the live value), mark `nullable` where live data is null, and relax over-strict `required`. Patches the shared component schemas in lib/openapi_schemas.php so a fix propagates to every user. Idempotent — run until openapi_verify.php is clean.

Usage (CLI only):
  php api/v1/dev/openapi_fix.php <api_key> [base_url]

Run this after adding or changing a module's serializer to bring the typed
schemas back in line with reality, then run openapi_verify.php to confirm,
then openapi_check.php + `spectral lint` for full validation.

## The endpoint

| | |
|---|---|
| URL | `api/v1/dev/openapi_fix.php` |
| Protected by | REST API key |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [oaf_get()](/code/functions/oaf_get.md) | 34 | GETs one API URL with the bearer key and returns [HTTP code, decoded JSON]. |
| [oaf_infer()](/code/functions/oaf_infer.md) | 46 | Builds an OpenAPI schema from a live value - its type, nested properties or list items, and nullable for nulls, strings and objects, which can be empty in other rows. |
| [oaf_patch()](/code/functions/oaf_patch.md) | 75 | Brings a schema node into line with one live value, in place: follows `$ref` into the shared component schemas, marks nullable where the value is null, types a typeless node, adds missing object properties and drops required fields the response does not have. |
| [oaf_resolvePath()](/code/functions/oaf_resolvePath.md) | 120 | Turns a path template into a real path by replacing each {id} with the first id its parent collection returns - null when there is no row to use. Ids are cached per collection. |

## How it hangs together

**Includes:** [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md)

**Calls:** [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)

