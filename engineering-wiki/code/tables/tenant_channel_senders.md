# tenant_channel_senders

Specific sender phone numbers mapped to a company (shared-intake channel routing). Phone numbers have no domain, so for a shared channel an exact-number map is the only routing key (else triage). UNIQUE so one number routes one way.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `identifier` | `VARCHAR(64) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | resolveTicketTenantForChannel L1208 |  |

