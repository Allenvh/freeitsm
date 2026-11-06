# api/v1/lib/openapi.php

> API endpoint - 508 lines

FreeITSM REST API v1 — OpenAPI 3.0.3 document generator.

Derives the spec at request time from the same sources the product already
maintains, so it cannot drift from the live API:
  - lib/routes.php      — the authoritative route table (paths, methods,
                          permission tuples, handler names)
  - spec.json           — the documentation catalogue shared with the
                          interactive docs page (summaries, descriptions,
                          parameters, request examples, endpoint-specific errors)
  - lib/permissions.php — the permission catalogue (security scopes)
  - lib/openapi_schemas.php — typed component schemas + per-endpoint response
                          bindings (verified against live responses)
  - the resource files  — scanned for their `apiRespond()` status codes so each
                          operation declares the success code it actually returns

3.0.3 is targeted deliberately for the widest tooling compatibility (Swagger
UI, Redoc, Postman, Insomnia, client generators). Emitted as JSON (canonical)
or YAML (same document, human-readable).

## The endpoint

| | |
|---|---|
| URL | `api/v1/lib/openapi.php` |
| Protected by | REST API key |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md) | 26 | Build the complete OpenAPI 3.0.3 document as a PHP array. |
| [apiV1OpenApiInfoDescription()](/code/functions/apiV1OpenApiInfoDescription.md) | 87 | The document-level description shown at the top of Swagger UI / Redoc. |
| [apiV1BaseSchemas()](/code/functions/apiV1BaseSchemas.md) | 102 | Base component schemas that always exist (envelope, error, pagination). |
| [apiV1BuildOperation()](/code/functions/apiV1BuildOperation.md) | 142 | Build one OpenAPI Operation Object for a docs endpoint. |
| [apiV1BuildResponses()](/code/functions/apiV1BuildResponses.md) | 233 | Assemble the responses map: success code(s) + every error the endpoint can return. |
| [apiV1SuccessEnvelope()](/code/functions/apiV1SuccessEnvelope.md) | 268 | Wrap a data schema (or a generic object) in the { data, meta? } envelope. |
| [apiV1EndpointErrors()](/code/functions/apiV1EndpointErrors.md) | 287 | The errors an endpoint can return: the common ones derived from its shape, plus any endpoint-specific ones from the docs extras. Mirrors the docs page's `errorsOf()` so the two never disagree. Returns [code => description]. |
| [apiV1OperationId()](/code/functions/apiV1OperationId.md) | 328 | A stable, unique operationId from method + path. |
| [apiV1ParamDescription()](/code/functions/apiV1ParamDescription.md) | 340 | Pull a parameter description from the docs params for a given name/in. |
| [apiV1MapEndpointsToHandlers()](/code/functions/apiV1MapEndpointsToHandlers.md) | 356 | Match every docs endpoint (method + path template) to its handler function by testing the template against the route regexes. Returns ['M /path' => handler]. |
| [apiV1HandlerSuccessCodes()](/code/functions/apiV1HandlerSuccessCodes.md) | 379 | Scan the resource files for each handler's `apiRespond()` status codes, so an operation declares the success code(s) it genuinely returns (201 on create, 200 otherwise, both where a handler upserts). Returns [handler => [codes]]. |
| [apiV1OpenApiToYaml()](/code/functions/apiV1OpenApiToYaml.md) | 421 | Renders the OpenAPI document as YAML, with a generated-file header line. |
| [apiV1YamlNode()](/code/functions/apiV1YamlNode.md) | 432 | Renders one value as YAML at the given indent - lists as "- " items with the first key of a mapping hung on the dash, mappings as "key: value", empty arrays as [] or {}. |
| [apiV1YamlKey()](/code/functions/apiV1YamlKey.md) | 473 | A mapping key as YAML - plain when it is a simple identifier or path, double-quoted otherwise. |
| [apiV1YamlScalar()](/code/functions/apiV1YamlScalar.md) | 484 | A scalar as YAML - null, true, false and numbers as they are, a string plain only when it is unambiguous text and double-quoted otherwise, so nothing is read back as another type. |
| [apiV1YamlQuote()](/code/functions/apiV1YamlQuote.md) | 504 | Double-quotes a string for YAML, escaping backslashes, quotes, newlines, tabs and carriage returns. |

## How it hangs together

**Includes:** [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), [api/v1/lib/routes.php](/code/files/api/v1/lib/routes.php.md)

**Included by:** [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md), [api/v1/dev/openapi_verify.php](/code/files/api/v1/dev/openapi_verify.php.md), [api/v1/lib/openapi_check.php](/code/files/api/v1/lib/openapi_check.php.md), [api/v1/openapi.php](/code/files/api/v1/openapi.php.md)

**Calls:** [apiV1PermissionCatalog()](/code/functions/apiV1PermissionCatalog.md)

