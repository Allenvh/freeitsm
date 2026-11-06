# api/self-service/preference.php

> API endpoint - 80 lines

API: one per-person setting for the signed-in SELF-SERVICE PORTAL user.

GET  ?key=kb_layout        read it
  POST { key, value }        store it

The portal twin of api/system/get_user_preference.php + set_user_preference.php,
which are keyed by analyst_id and so unusable here.

🔴 KEYS AND VALUES ARE ALLOW-LISTED, not free text. This endpoint is reachable
by any signed-in customer, and an open key/value store behind a portal login is
somewhere to park arbitrary strings on the server. Only settings the portal
actually offers are accepted, and only with values it actually renders.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/preference.php` |
| Protected by | Signed-in portal user (session check) |
| Reads | `GET key` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [portalPrefGet()](/code/functions/portalPrefGet.md), [portalPrefSet()](/code/functions/portalPrefSet.md)

