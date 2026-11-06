# tenant_settings

Per-company answers to settings that otherwise live install-wide in system_settings (discussion #72). A company with no row follows the install default — see includes/tenant_settings.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `setting_key` | `VARCHAR(100) NOT NULL` |  |
| `setting_value` | `TEXT NULL` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md):123 | `setTenantSetting` | delete |  |  |
| [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md):128 | `setTenantSetting` | insert | `tenant_id`, `setting_key`, `setting_value` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/feature_bingo/cards/tickets-core.php](/code/files/includes/feature_bingo/cards/tickets-core.php.md) | L17, L910 |  |
| [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) | tenantSetting L49, tenantSettingsForKey L146 |  |

