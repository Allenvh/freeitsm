# api/self-service/get_catalogue.php

> API endpoint - 78 lines

API: the request catalogue — forms offered to customers in the portal.

Two gates, both required:
  is_portal_visible = 1  — someone deliberately offered this form to
                           customers. Defaults to 0, so an upgrade never
                           exposes an existing internal form (a new-starter
                           request a manager fills in is `is_active`, but has
                           no business being on a customer's menu).
  is_active = 1          — the analyst-side on/off, unchanged.

Forms are VERSIONED: each row is one snapshot, chained by parent_form_id, and
the LEAF (no children) is the current one. The catalogue lists leaves only,
exactly as the analyst list does — otherwise every past revision of a form
would appear as a separate thing to request.

⚠️ Forms are NOT multi-company yet (no tenant_id on any forms table), so this
catalogue is the same for every company. That is a known limitation, not an
oversight — see the module's multi-tenancy work.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_catalogue.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

