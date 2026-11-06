# api/v1/dev/jsonschema4.php

> API endpoint - 265 lines

Minimal JSON Schema draft-04 validator — dev tool for checking a generated OpenAPI 3.0 document against the official OAS meta-schema without needing Node/Python. Handles the constructs the OAS 3.0 schema actually uses.

Usage (CLI only):
  curl -s http://localhost/freeitsm-app/api/v1/openapi.json > /tmp/o.json
  php api/v1/dev/jsonschema4.php /tmp/o.json api/v1/dev/oas-3.0-schema.json

Proven to reject malformed documents (missing openapi/responses, bad version
pattern), so a clean pass is meaningful. For the authoritative linter run,
additionally use `spectral lint` / `swagger-cli validate` in a Node env.

## The endpoint

| | |
|---|---|
| URL | `api/v1/dev/jsonschema4.php` |
| Protected by | REST API key |

## Classes

- [JsonSchema4](/code/classes/JsonSchema4.md) (class, line 17)

