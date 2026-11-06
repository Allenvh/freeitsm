# api/v1/dev/openapi_stub.php

> API endpoint - 50 lines

Scaffold catalogue entries for routes that aren't documented yet.

php api/v1/dev/openapi_stub.php

Compares the route table (lib/routes.php) with the catalogue (spec.json) and
prints a ready-to-paste `spec.json` item for every route that has no entry —
method, path (with the right `{id}` placeholders), the permission tuple, and
stub `s` / `d` / `params` you fill in. Paste each into the matching section's
`items` array in api/v1/spec.json, flesh it out, then run openapi_fix.php +
openapi_check.php. Prints nothing when everything is already documented.

## The endpoint

| | |
|---|---|
| URL | `api/v1/dev/openapi_stub.php` |
| Protected by | REST API key |

## How it hangs together

**Includes:** [api/v1/lib/routes.php](/code/files/api/v1/lib/routes.php.md)

