# api/knowledge/assistant_settings.php

> API endpoint - 79 lines

API: Knowledge — the assistant's tunables.

GET  → current values (defaults filled in where nothing is stored)
POST → save

These are settings rather than constants because "how thin is too thin" is a
genuine judgement that differs by desk: a small internal IT team wants the bar
low, because any question asked twice is worth writing down. An MSP with a
public portal wants it high, because a thin article is a support call.

⚠️ Every value is clamped server-side. A cluster threshold of 0 would put every
closed ticket into one enormous group and a lookback of 30000 would read the
entire history on a button press — neither is a useful thing to allow, and
neither should depend on the number input's min/max holding.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/assistant_settings.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson); Permission: Cap::KNOWLEDGE_AI (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [writeupSettings()](/code/functions/writeupSettings.md)

