# api/system/debug-tools/D005_endpoint_permissions.php

> API endpoint - 459 lines

Debug Tool D005 — Endpoint permission coverage

Scans EVERY endpoint under api/ and reports what actually guards it:
  - a capability (RBAC Layer 2)      — requireCapabilityJson(Cap::X)
  - administrator only               — includes/admin_api_guard.php
  - module access (RBAC Layer 1)     — requireModuleAccessJson('<module>')
  - an API key                       — the REST API v1 front controller
  - logged in, and nothing more      — only a `$_SESSION`['analyst_id'] check
  - NOTHING AT ALL                   — no authentication of any kind

WHY THIS EXISTS
---------------
A type system catches a MISSPELLED permission. Nothing catches a permission
somebody simply FORGOT TO WRITE. That omission is how api/settings/
save_system_settings.php ended up letting any logged-in analyst rewrite the
vCenter credentials and the brute-force lockout policy (#829), how six Intune
endpoints let any analyst trigger a full tenant sync (#830), and how
api/rfp-builder/save_ai_settings.php still lets anyone overwrite an AI API key.

Every one of those was found BY HAND, BY ACCIDENT, while looking for something
else. This tool is the systematic version: it reads all ~630 endpoints in a few
seconds and ranks what it finds by how much damage the gap allows.

It is a STATIC read of the source. It cannot prove an endpoint is safe — an
endpoint may do its own bespoke checking that this tool can't see, which is why
findings are ranked as "worth a look", not "definitely broken". But an endpoint
that writes to the database with no guard at all is nearly always a real bug.

READ-ONLY. Writes nothing, changes nothing, prints no secrets.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D005_endpoint_permissions.php` |
| Protected by | Signed-in analyst (session check) (via includes/settings_keys.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 54 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [d005IsPublicByDesign()](/code/functions/d005IsPublicByDesign.md) | 74 | Endpoints that are PUBLIC BY DESIGN — they must work before anyone is logged in, or they belong to the self-service portal, which authenticates its own way. Being unguarded is the point, so they are excluded from the findings rather than shouted about. Listed explicitly so the exclusion is a decision, not an accident. |
| [d005ByDesign()](/code/functions/d005ByDesign.md) | 100 | Endpoints that are CORRECTLY reachable by any logged-in analyst, with the reason. |
| [d005IsWriteShaped()](/code/functions/d005IsWriteShaped.md) | 118 | Does this endpoint change anything? Writes are what make a missing guard dangerous. |
| [d005Classify()](/code/functions/d005Classify.md) | 143 | What guards it? Returns [level, detail]. Strongest match wins. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md)

**Calls:** [capAll()](/code/functions/capAll.md), [capIsUmbrella()](/code/functions/capIsUmbrella.md), [capSelfCheck()](/code/functions/capSelfCheck.md), [settingKeyOwners()](/code/functions/settingKeyOwners.md)

