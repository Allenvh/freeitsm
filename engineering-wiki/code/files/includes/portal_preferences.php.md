# includes/portal_preferences.php

> PHP - 105 lines

Per-person settings for SELF-SERVICE PORTAL users.

🔑 WHY THIS EXISTS RATHER THAN REUSING user_preferences.

`user_preferences` is keyed by `analyst_id`, so it cannot hold anything for a
portal user — they have no analyst row. That gap has been patched once
already, when the portal's colour palette was given its own column on the
`users` table. A second one-off column is where that stops being a patch and
starts being a habit, so this is the generic twin: same shape, same idea,
keyed by the portal user instead.

🔴 EVERY FUNCTION HERE WORKS BEFORE DATABASE VERIFICATION HAS RUN. An upgrade
ships the code before the operator runs the schema check, so on that install
the table does not exist yet. A read returns the default and a write is
quietly dropped; nothing throws, and the portal renders. Proved by dropping
the table and loading the page.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [portalPrefGet()](/code/functions/portalPrefGet.md) | 28 | One preference for one portal user, or `$default`. |
| [portalPrefSet()](/code/functions/portalPrefSet.md) | 56 | Store one preference. Returns false if it could not be stored — the caller decides whether that is worth telling anybody, and for a layout toggle it is not: the layout still changed, only the memory of it failed. |
| [portalKbLayout()](/code/functions/portalKbLayout.md) | 100 | The layout this portal user should see. Always one of PORTAL_KB_LAYOUTS. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 | `portalPrefGet` | read [portal_user_preferences](/code/tables/portal_user_preferences.md) |  |
| 63 | `portalPrefSet` | read [portal_user_preferences](/code/tables/portal_user_preferences.md) |  |
| 68 | `portalPrefSet` | **update** [portal_user_preferences](/code/tables/portal_user_preferences.md) (`preference_value`, `updated_datetime`) |  |
| 72 | `portalPrefSet` | **insert** [portal_user_preferences](/code/tables/portal_user_preferences.md) (`user_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## How it hangs together

**Included by:** [api/self-service/preference.php](/code/files/api/self-service/preference.php.md)

