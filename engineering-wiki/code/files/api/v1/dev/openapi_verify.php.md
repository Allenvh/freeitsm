# api/v1/dev/openapi_verify.php

> API endpoint - 135 lines

Verify the typed OpenAPI response schemas against LIVE responses from a running install. For every GET endpoint it resolves any {id} parameters from a sibling collection, fetches the real response, and validates the `data` payload against the bound schema — OpenAPI-aware (`$ref`, allOf, nullable). Reports type violations AND response fields the schema fails to document.

Usage (CLI only):
  php api/v1/dev/openapi_verify.php <api_key> [base_url]
  FITSM_API_KEY=fitsm_... php api/v1/dev/openapi_verify.php

The key needs read access to the resources you want checked. base_url
defaults to the local install's front controller. Run openapi_fix.php first
to auto-apply the fixes this reports, then re-run this to confirm zero.

## The endpoint

| | |
|---|---|
| URL | `api/v1/dev/openapi_verify.php` |
| Protected by | REST API key |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [oav_get()](/code/functions/oav_get.md) | 34 | GETs one API URL with the bearer key and returns [HTTP code, decoded JSON]. |
| [oav_resolveRef()](/code/functions/oav_resolveRef.md) | 47 | Follows a schema's `$ref` chain into the component schemas and returns the schema it ends on (an empty array when a name is missing). |
| [oav_validate()](/code/functions/oav_validate.md) | 63 | Validates a live value against an OpenAPI schema (`$ref`, allOf, nullable) and returns every problem as "path: message" - type mismatches, missing required properties and response fields the schema does not document. |
| [oav_resolvePath()](/code/functions/oav_resolvePath.md) | 105 | Turns a path template into a real path by replacing each {id} with the first id its parent collection returns - null when there is no row to use. Ids are cached per collection. |

## How it hangs together

**Includes:** [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md)

**Calls:** [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)

