# includes/domains/settings.php

> PHP - 269 lines

Domains — settings: the keys, their defaults, and the one reader.

Every key lives in `system_settings` and is written only by
api/domains/settings.php, which validates it and checks the capability of the
tab it belongs to (see domains/settings/manifest.php for why the generic
settings writer is deliberately NOT allowed to touch these).

🔑 THE DEFAULTS ARE CHOSEN SO THAT INSTALLING THE MODULE CHANGES NOTHING UNTIL
SOMEBODY DECIDES. Nothing is emailed until alerts are switched on, nothing
external is queried beyond the registry lookups a person asked for by adding a
domain, and the "noisy" scans (Certificate Transparency, look-alikes) are off.
The settings screen shows each default, so what is not configured is visible.

⚠️ The JS on domains/settings/index.php receives these defaults FROM this file
(via the settings endpoint) rather than repeating them, so the screen and the
server cannot disagree about what "not set yet" means.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainSettingDefinitions()](/code/functions/domainSettingDefinitions.md) | 29 | key => [default, validator, tab]. The tab decides which capability may write the key; the validator is one of the names `domainSettingValidate()` understands. |
| [domainSettings()](/code/functions/domainSettings.md) | 134 | Every setting, with its default applied where nothing is stored. |
| [domainSetting()](/code/functions/domainSetting.md) | 166 | Returns one Domains setting as a string, from the cached settings (its default when not saved). |
| [domainSettingWrite()](/code/functions/domainSettingWrite.md) | 177 | Write one setting (no validation — callers go through `domainSettingValidate()`). |
| [domainSettingValidate()](/code/functions/domainSettingValidate.md) | 193 | Validate and normalise one incoming value. Returns the value to store, or throws InvalidArgumentException with a message fit to show the operator. |
| [domainParseDays()](/code/functions/domainParseDays.md) | 255 | "60, 30, 7" → [60, 30, 7] — sorted high to low, de-duplicated, nonsense dropped. 0 is allowed and means "on the day". |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 146 | `domainSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 180 | `domainSettingWrite` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Included by:** [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

