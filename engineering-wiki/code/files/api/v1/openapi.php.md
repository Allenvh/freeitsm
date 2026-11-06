# api/v1/openapi.php

> API endpoint - 42 lines

FreeITSM REST API v1 — serves the OpenAPI description document.

GET /api/v1/openapi.json   (or .../openapi.php)
  GET /api/v1/openapi.yaml   (or .../openapi.php?format=yaml)

This is public documentation — no key required — so tooling (Swagger UI,
Postman, client generators) can fetch it directly. It contains no secrets:
only the shape of the API, which any documentation describes. Generated
fresh on each request from the live route table + docs catalogue, so it can
never fall out of step with the API it describes.

## The endpoint

| | |
|---|---|
| URL | `api/v1/openapi.php` |
| Protected by | REST API key |
| Reads | `GET format` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md)

**Calls:** [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md), [apiV1OpenApiToYaml()](/code/functions/apiV1OpenApiToYaml.md)

