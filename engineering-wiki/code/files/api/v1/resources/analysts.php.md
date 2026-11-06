# api/v1/resources/analysts.php

> API endpoint - 101 lines

FreeITSM REST API v1 — analyst provisioning (3.5.0, PR #174 from @Kraleemil's design). Lets an identity source keep analysts and their access in step:

POST  /analysts               create (starts with no access; signs in via SSO)
  GET   /analysts/{id}          one analyst, active or not
  PATCH /analysts/{id}          full_name, email, is_active, auth_provider_id
  GET   /analysts/{id}/access   admin, modules, companies, capabilities (+ effective)
  PATCH /analysts/{id}/access   set any of them

GET /analysts (the list) is in reference.php.

The rules are in includes/services/analysts.php. This adapter adds the KEY
rule: 🔴 every write needs a key that (a) acts as a System administrator and
(b) is scoped to all companies. In the app only administrators manage analysts
(admin_api_guard.php); a key is held to the same rule, because a key that could
create staff or hand out modules and capabilities without being an admin could
grant its own analyst more than it has.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/analysts.php` |
| Protected by | REST API key |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiAnalystsRequireProvisioner()](/code/functions/apiAnalystsRequireProvisioner.md) | 25 | 403 unless this key may provision analysts. |
| [apiSerializeAnalyst()](/code/functions/apiSerializeAnalyst.md) | 41 | Shapes an analyst row for the API - id, username, name, email, active and admin flags, sign-in provider and dates. Never the password hash or MFA secret. |
| [apiAnalystsCreate()](/code/functions/apiAnalystsCreate.md) | 57 | POST /analysts |
| [apiAnalystsGet()](/code/functions/apiAnalystsGet.md) | 67 | GET /analysts/{id} |
| [apiAnalystsUpdate()](/code/functions/apiAnalystsUpdate.md) | 75 | PATCH /analysts/{id} |
| [apiAnalystsAccessGet()](/code/functions/apiAnalystsAccessGet.md) | 85 | GET /analysts/{id}/access |
| [apiAnalystsAccessUpdate()](/code/functions/apiAnalystsAccessUpdate.md) | 93 | PATCH /analysts/{id}/access |

## How it hangs together

**Includes:** [includes/services/analysts.php](/code/files/includes/services/analysts.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiRespond()](/code/functions/apiRespond.md)

