# api/domains/settings.php

> API endpoint - 99 lines

Domains → Settings, behind the SAME capability as each tab.

GET                       every setting with its default, the last run, the
                          cron URL, and which tabs this analyst may write
POST {action:'save', tab, settings:{key: value}}
                          validated per key; every key must belong to `tab`,
                          and `tab`'s capability is checked — so one request
                          cannot smuggle an alerts key past a monitoring grant
POST {action:'preview'}   the alert run as a dry run: who WOULD be e-mailed
POST {action:'run_now'}   one scheduled run, now (alerts or monitoring cap)
POST {action:'sync_calendar'}

## The endpoint

| | |
|---|---|
| URL | `api/domains/settings.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 45 |  | read [domains](/code/tables/domains.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainHasCap()](/code/functions/domainHasCap.md), [domainRequireCap()](/code/functions/domainRequireCap.md), [domainScheduledRun()](/code/functions/domainScheduledRun.md), [domainSettingDefinitions()](/code/functions/domainSettingDefinitions.md), [domainSettings()](/code/functions/domainSettings.md), [domainSettingValidate()](/code/functions/domainSettingValidate.md), [domainSettingWrite()](/code/functions/domainSettingWrite.md), [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md), [publicBaseUrl()](/code/functions/publicBaseUrl.md)

