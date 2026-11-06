# DomainsService

> Class in [includes/services/domains.php](/code/files/includes/services/domains.php.md), line 43

## Methods

| Method | What it does |
|---|---|
| [fieldMap()](#fieldmap) | The fields a person (or an API key) may set, and how each is validated. domain_name and auth_code are handled separately. |
| [bulkFields()](#bulkfields) | Fields the bulk editor may set on many domains at once. |
| [saveDomain()](#savedomain) | Create (no id) or update (id present) a domain. Returns ['id', 'created']. |
| [createDomain()](#createdomain) | Adds a domain to the register for a company and returns its id. |
| [updateDomain()](#updatedomain) | Changes a domain's fields, records each change in its history and fires domain.updated when anything changed. |
| [deleteDomain()](#deletedomain) | Delete a domain and everything that hangs off it. Returns the id. |
| [bulkAdd()](#bulkadd) | Add many domains from a pasted list (one per line, or separated by commas or spaces). Every name goes through the same create path as a single add. |
| [bulkUpdate()](#bulkupdate) | Set the same fields on many domains. Each goes through `updateDomain()`, so each gets its own history rows and scope check; a domain the caller cannot reach is reported, not silently skipped. |
| [refreshLookup()](#refreshlookup) | Look a domain up now and apply the answer. For the "Refresh" button and the API. |
| [applyLookup()](#applylookup) | Write a lookup result onto a domain, under the operator's overwrite rule. |
| [recordLookupError()](#recordlookuperror) | Records that the last registration lookup for a domain failed, and why. |
| [runChecks()](#runchecks) | Run the health/security checks for one domain, store the result, and compare against the last run. Returns the check result plus 'changes'. |
| [revealAuthCode()](#revealauthcode) | The decrypted auth code, for somebody the adapter has already checked holds Cap::DOMAINS_AUTH_CODES. Recorded in the domain's history unless the operator switched that off. |
| [saveStatus()](#savestatus) | Adds or updates a domain status (name, colour, alerts on or off, active, order); names must be unique. |
| [deleteStatus()](#deletestatus) | Deletes a domain status; refuses while any domain uses it. |
| [saveRegistrarAccount()](#saveregistraraccount) | Adds or updates a registrar account (the login at a registrar) for a company. |
| [deleteRegistrarAccount()](#deleteregistraraccount) | Deletes a registrar account, unlinking it from its domains first. |
| [loadRow()](#loadrow) | Loads one domain; throws not found when it does not exist. |
| [loadForActor()](#loadforactor) | Load a domain the caller may touch — out of scope reads as not found. |
| [loadAccountForActor()](#loadaccountforactor) | Loads a registrar account the actor's companies can reach; throws not found otherwise. |
| [assertScope()](#assertscope) | Throws not found when the row's company is outside the actor's companies. |
| [storeTenant()](#storetenant) | The Default company is stored as NULL, the way every scoped table does. |
| [validateField()](#validatefield) | Validates and cleans one domain field according to its type in `fieldMap()`, and returns the value to store. |
| [fieldReady()](#fieldready) | Is this field's column there yet? The #153 and #162 columns arrive with Database Verification. |
| [normaliseParties()](#normaliseparties) | #162: a domain has ONE customer - a person, or a supplier (optionally with one of its contacts) - and ONE technical contact - a supplier contact, or an analyst. Setting one kind clears the other, so a caller (the edit dialog, or a REST PATCH naming just one field) never has to know which kind was there before. Naming both kinds at once is refused rather than guessed. Clearing a field clears only that field, except that clearing the supplier also clears its contact. |
| [lookup()](#lookup) | A validated id from a lookup table, or null for none; throws for an unknown id. |
| [requireAnalyst()](#requireanalyst) | Throws unless the id is an active analyst. |
| [str()](#str) | Trimmed text cut to `$max` characters, or null when blank. |
| [same()](#same) | True when two values are equal, treating null and '' as the same. |
| [matchSupplier()](#matchsupplier) | A supplier whose legal or trading name matches the registry's registrar name. |
| [audit()](#audit) | Records one change in a domain's history; a failure is logged, never thrown. |
| [assertContractVisible()](#assertcontractvisible) | A domain may only be linked to a contract its editor can see - one in a company outside their reach is "unknown", as everywhere else - and, since contracts belong to a company (3.5.0), only to one in the DOMAIN's company. `$domainTenant` is the domain's stored company (NULL = Default). |
| [auditDisplay()](#auditdisplay) | Ids stored as names, so the history reads as English a year later. |
| [eventPayload()](#eventpayload) | The payload every domain.* event carries. |
| [dispatchCrud()](#dispatchcrud) | Fires the domain.<verb> workflow event for a domain; a failure is logged, never thrown. |
| [afterDateChange()](#afterdatechange) | Expiry dates feed the calendar; keep it in step. Never fails the write. |

### fieldMap

*public static* - line 49

The fields a person (or an API key) may set, and how each is validated. domain_name and auth_code are handled separately.

```php
function fieldMap(): array
```

**Called from:** [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)

### bulkFields

*public static* - line 89

Fields the bulk editor may set on many domains at once.

```php
function bulkFields(): array
```

### saveDomain

*public static* - line 107

Create (no id) or update (id present) a domain. Returns ['id', 'created'].

```php
function saveDomain(PDO $conn, ActorContext $ctx, array $in, ?int $tenantId = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new), domain_name, auth_code and any `fieldMap()` key |
| `$tenantId` | `?int` | `null` | the company a NEW domain belongs to (the adapter resolves it: the analyst's active company, or the API key's). Ignored on update — moving a domain between companies is not an edit. |

**Called from:** [api/domains/save.php](/code/files/api/domains/save.php.md)

### createDomain

*public static* - line 124

Adds a domain to the register for a company and returns its id.

Validates every field, starts it in the first active status, encrypts any auth code,
records it in the history and fires the domain.created workflow event.

```php
function createDomain(
    PDO $conn,
    ActorContext $ctx,
    array $in,
    ?int $tenantId,
    string $source = 'app'
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | domain_name (required), auth_code and any `fieldMap()` key |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` | `'app'` | the history row's source; 'app' (default) records 'app' or 'api' from the caller, anything else as given |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 137 | `DomainsService::createDomain` | read [domains](/code/tables/domains.md) |  |
| 157 | `DomainsService::createDomain` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 161 | `DomainsService::createDomain` | **insert** [domains](/code/tables/domains.md) |  |
| 166 | `DomainsService::createDomain` | **update** [domains](/code/tables/domains.md) (`auth_code`) |  |

**Called from:** [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md)

### updateDomain

*public static* - line 185

Changes a domain's fields, records each change in its history and fires domain.updated when anything changed.

A new domain name clears the change-watch baseline.

```php
function updateDomain(PDO $conn, ActorContext $ctx, int $id, array $in, string $source = ''): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain to change |
| `$in` | `array` |  | domain_name, auth_code and any `fieldMap()` key; absent keys are left alone |
| `$source` | `string` | `''` | the history row's source; '' records 'app' or 'api' from the caller |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 198 | `DomainsService::updateDomain` | read [domains](/code/tables/domains.md) |  |
| 230 | `DomainsService::updateDomain` | **update** [domains](/code/tables/domains.md) |  |

**Called from:** [api/domains/auth_code.php](/code/files/api/domains/auth_code.php.md), [api/domains/links.php](/code/files/api/domains/links.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md)

### deleteDomain

*public static* - line 245

Delete a domain and everything that hangs off it. Returns the id.

```php
function deleteDomain(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain to delete, with its history, alerts, checks and links |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 261 | `DomainsService::deleteDomain` | **delete** [domains](/code/tables/domains.md) |  |

**Called from:** [api/domains/delete.php](/code/files/api/domains/delete.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md)

### bulkAdd

*public static* - line 285

Add many domains from a pasted list (one per line, or separated by commas or spaces). Every name goes through the same create path as a single add.

```php
function bulkAdd(
    PDO $conn,
    ActorContext $ctx,
    string $text,
    array $defaults,
    ?int $tenantId,
    string $source = 'app'
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$text` | `string` |  | the pasted list - names separated by new lines, commas, semicolons, tabs or spaces (at most 1,000) |
| `$defaults` | `array` |  | `fieldMap()` values every new domain starts with, e.g. status_id or owner_analyst_id |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` | `'app'` | the history row's source, as `createDomain()` |

**Returns:** array{added:array<int,array{id:int,name:string}>, skipped:array<int,array{input:string,reason:string}>}

**Called from:** [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)

### bulkUpdate

*public static* - line 317

Set the same fields on many domains. Each goes through `updateDomain()`, so each gets its own history rows and scope check; a domain the caller cannot reach is reported, not silently skipped.

```php
function bulkUpdate(PDO $conn, ActorContext $ctx, array $ids, array $fields): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | the domains to change |
| `$fields` | `array` |  | [field => value]; only `bulkFields()` keys are used |

**Called from:** [api/domains/bulk_update.php](/code/files/api/domains/bulk_update.php.md)

### refreshLookup

*public static* - line 339

Look a domain up now and apply the answer. For the "Refresh" button and the API.

```php
function refreshLookup(PDO $conn, ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain to look up |

**Called from:** [api/domains/process.php](/code/files/api/domains/process.php.md), [api/domains/save.php](/code/files/api/domains/save.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md)

### applyLookup

*public static* - line 366

Write a lookup result onto a domain, under the operator's overwrite rule.

'always' — the registry is the record for the facts it holds.
'blanks' — it only fills fields nobody has typed into.

Registry statuses are never typed by people, so they always follow the
registry. A typed expiry date on a registry that publishes none (.de) is
never blanked by that registry's silence: a null answer is "not published",
not "no date".

```php
function applyLookup(PDO $conn, int $id, array $r, ?int $analystId = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain the answer is for |
| `$r` | `array` |  | a successful `domainLookup()` result: registrar, dates, nameservers, locks, statuses, registrant, source |
| `$analystId` | `?int` | `null` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<int,array{field:string,old:?string,new:?string}>

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 421 | `DomainsService::applyLookup` | **update** [domains](/code/tables/domains.md) |  |

**Called from:** [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

### recordLookupError

*public static* - line 436

Records that the last registration lookup for a domain failed, and why.

```php
function recordLookupError(PDO $conn, int $id, string $error): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain |
| `$error` | `string` |  | why the lookup failed (cut to 255 characters) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 438 | `DomainsService::recordLookupError` | **update** [domains](/code/tables/domains.md) (`last_lookup_datetime`, `last_lookup_error`) |  |

**Called from:** [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

### runChecks

*public static* - line 453

Run the health/security checks for one domain, store the result, and compare against the last run. Returns the check result plus 'changes'.

```php
function runChecks(PDO $conn, ?ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `?ActorContext` |  | null when the scheduled run calls it |
| `$id` | `int` |  | the domain to check |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 464 | `DomainsService::runChecks` | **update** [domains](/code/tables/domains.md) (`check_results`, `security_score`, `security_grade`, `ssl_expiry_date`, `ssl_issuer`, `watch_baseline`, `last_check_datetime`) |  |

**Called from:** [api/domains/process.php](/code/files/api/domains/process.php.md), [api/domains/save.php](/code/files/api/domains/save.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

### revealAuthCode

*public static* - line 522

The decrypted auth code, for somebody the adapter has already checked holds Cap::DOMAINS_AUTH_CODES. Recorded in the domain's history unless the operator switched that off.

```php
function revealAuthCode(PDO $conn, ActorContext $ctx, int $id): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain whose auth code is shown |

**Called from:** [api/domains/auth_code.php](/code/files/api/domains/auth_code.php.md)

### saveStatus

*public static* - line 543

Adds or updates a domain status (name, colour, alerts on or off, active, order); names must be unique.

```php
function saveStatus(PDO $conn, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (0 or absent = new), name (unique), colour (#rrggbb), alerts_enabled, is_active, display_order |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 553 | `DomainsService::saveStatus` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 558 | `DomainsService::saveStatus` | **update** [domain_statuses](/code/tables/domain_statuses.md) (`name`, `colour`, `alerts_enabled`, `is_active`, `display_order`) |  |
| 562 | `DomainsService::saveStatus` | **insert** [domain_statuses](/code/tables/domain_statuses.md) (`name`, `colour`, `alerts_enabled`, `is_active`, `display_order`, `created_datetime`) |  |

**Called from:** [api/domains/statuses.php](/code/files/api/domains/statuses.php.md)

### deleteStatus

*public static* - line 572

Deletes a domain status; refuses while any domain uses it.

```php
function deleteStatus(PDO $conn, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain_statuses row to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 574 | `DomainsService::deleteStatus` | read [domains](/code/tables/domains.md) |  |
| 579 | `DomainsService::deleteStatus` | **delete** [domain_statuses](/code/tables/domain_statuses.md) |  |

**Called from:** [api/domains/statuses.php](/code/files/api/domains/statuses.php.md)

### saveRegistrarAccount

*public static* - line 592

Adds or updates a registrar account (the login at a registrar) for a company.

```php
function saveRegistrarAccount(PDO $conn, ActorContext $ctx, array $in, ?int $tenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (0 or absent = new), account_name (required), supplier_id, account_reference, login_url, owner_analyst_id, two_factor_holder, notes |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 608 | `DomainsService::saveRegistrarAccount` | **update** [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) (`supplier_id`, `account_name`, `account_reference`, `login_url`, `owner_analyst_id`, `two_factor_holder`, `notes`) |  |
| 616 | `DomainsService::saveRegistrarAccount` | **insert** [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) (`supplier_id`, `account_name`, `account_reference`, `login_url`, `owner_analyst_id`, `two_factor_holder`, `notes`, `tenant_id`, `created_by`, `created_datetime`) |  |

**Called from:** [api/domains/accounts.php](/code/files/api/domains/accounts.php.md)

### deleteRegistrarAccount

*public static* - line 627

Deletes a registrar account, unlinking it from its domains first.

```php
function deleteRegistrarAccount(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain_registrar_accounts row to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 630 | `DomainsService::deleteRegistrarAccount` | **update** [domains](/code/tables/domains.md) (`registrar_account_id`) |  |
| 631 | `DomainsService::deleteRegistrarAccount` | **delete** [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |

**Called from:** [api/domains/accounts.php](/code/files/api/domains/accounts.php.md)

### loadRow

*public static* - line 643

Loads one domain; throws not found when it does not exist.

```php
function loadRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 645 | `DomainsService::loadRow` | read [domains](/code/tables/domains.md) |  |

### loadForActor

*public static* - line 657

Load a domain the caller may touch — out of scope reads as not found.

```php
function loadForActor(PDO $conn, ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain to load |

**Called from:** [api/domains/links.php](/code/files/api/domains/links.php.md), [api/domains/process.php](/code/files/api/domains/process.php.md), [api/domains/watch.php](/code/files/api/domains/watch.php.md)

### loadAccountForActor

*private static* - line 669

Loads a registrar account the actor's companies can reach; throws not found otherwise.

```php
function loadAccountForActor(PDO $conn, ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain_registrar_accounts row to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 671 | `DomainsService::loadAccountForActor` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |

### assertScope

*private static* - line 684

Throws not found when the row's company is outside the actor's companies.

```php
function assertScope(PDO $conn, ActorContext $ctx, array $row): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | a domain or registrar account row; must include tenant_id |

### storeTenant

*private static* - line 694

The Default company is stored as NULL, the way every scoped table does.

```php
function storeTenant(PDO $conn, ?int $tenantId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### validateField

*private static* - line 714

Validates and cleans one domain field according to its type in `fieldMap()`, and returns the value to store.

```php
function validateField(PDO $conn, string $field, $v, array $def, array $row, string $domainName)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the `fieldMap()` key being set |
| `$v` |  |  | the value from the request |
| `$def` | `array` |  | that field's `fieldMap()` entry (type, table, values, max, min) |
| `$row` | `array` |  | the domain as stored (or ['tenant_id' => ...] on create), for company checks |
| `$domainName` | `string` |  | the domain's name, used to read ssl_hosts entries |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 730 | `DomainsService::validateField` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |

### fieldReady

*private static* - line 809

Is this field's column there yet? The #153 and #162 columns arrive with Database Verification.

```php
function fieldReady(PDO $conn, string $field): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | a `fieldMap()` key |

### normaliseParties

*private static* - line 828

#162: a domain has ONE customer - a person, or a supplier (optionally with one of its contacts) - and ONE technical contact - a supplier contact, or an analyst. Setting one kind clears the other, so a caller (the edit dialog, or a REST PATCH naming just one field) never has to know which kind was there before. Naming both kinds at once is refused rather than guessed. Clearing a field clears only that field, except that clearing the supplier also clears its contact.

```php
function normaliseParties(PDO $conn, array $in, array $cur): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; the customer and technical contact keys are adjusted |
| `$cur` | `array` |  | the stored domain row on update; [] on create |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 850 | `DomainsService::normaliseParties` | read [contacts](/code/tables/contacts.md) |  |
| 865 | `DomainsService::normaliseParties` | read [contacts](/code/tables/contacts.md) |  |

### lookup

*private static* - line 882

A validated id from a lookup table, or null for none; throws for an unknown id.

```php
function lookup(PDO $conn, string $table, $value, string $label): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the lookup table, written in code - never user input |
| `$value` |  |  | the id from the request; null, '', 0 or '0' gives null |
| `$label` | `string` |  | what the table holds, for the error message |

### requireAnalyst

*private static* - line 897

Throws unless the id is an active analyst.

```php
function requireAnalyst(PDO $conn, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the analyst to check |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 899 | `DomainsService::requireAnalyst` | read [analysts](/code/tables/analysts.md) |  |

### str

*private static* - line 910

Trimmed text cut to `$max` characters, or null when blank.

```php
function str($v, int $max): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the value from the request |
| `$max` | `int` |  | the most characters to keep |

### same

*private static* - line 922

True when two values are equal, treating null and '' as the same.

```php
function same($a, $b): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` |  |  | the stored value |
| `$b` |  |  | the new value |

### matchSupplier

*private static* - line 933

A supplier whose legal or trading name matches the registry's registrar name.

```php
function matchSupplier(PDO $conn, string $registrar): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$registrar` | `string` |  | the registrar's name as the registry gives it |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 939 | `DomainsService::matchSupplier` | read [suppliers](/code/tables/suppliers.md) |  |

### audit

*public static* - line 963

Records one change in a domain's history; a failure is logged, never thrown.

```php
function audit(
    PDO $conn,
    int $domainId,
    ?int $analystId,
    string $field,
    $old,
    $new,
    string $source = 'app'
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the entry belongs to |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the field that changed, or an event such as 'domain_created' or 'auth_code_viewed' |
| `$old` |  |  | the value before, as shown (cut to 1000 characters); null for none |
| `$new` |  |  | the value after, as shown (cut to 1000 characters); null for none |
| `$source` | `string` | `'app'` | what made the change: 'app', 'api', 'lookup', 'check' or 'monitor' |

**Writes:** domain_audit - the domain's change history

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 966 | `DomainsService::audit` | **insert** [domain_audit](/code/tables/domain_audit.md) (`domain_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |

**Called from:** [api/domains/watch.php](/code/files/api/domains/watch.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

### assertContractVisible

*private static* - line 985

A domain may only be linked to a contract its editor can see - one in a company outside their reach is "unknown", as everywhere else - and, since contracts belong to a company (3.5.0), only to one in the DOMAIN's company. `$domainTenant` is the domain's stored company (NULL = Default).

```php
function assertContractVisible(
    PDO $conn,
    ActorContext $ctx,
    $contractId,
    ?int $domainTenant = null
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` |  |  | the contract being linked; null does nothing |
| `$domainTenant` | `?int` | `null` | the domain's stored company - null for the Default company |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 990 | `DomainsService::assertContractVisible` | read [contracts](/code/tables/contracts.md) |  |

### auditDisplay

*private static* - line 1008

Ids stored as names, so the history reads as English a year later.

```php
function auditDisplay(PDO $conn, string $field, $v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the field the value belongs to |
| `$v` |  |  | the stored value - an id for the lookup fields |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1012 | `DomainsService::auditDisplay` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 1013 | `DomainsService::auditDisplay` | read [suppliers](/code/tables/suppliers.md) |  |
| 1014 | `DomainsService::auditDisplay` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 1015 | `DomainsService::auditDisplay` | read [analysts](/code/tables/analysts.md) |  |
| 1016 | `DomainsService::auditDisplay` | read [contacts](/code/tables/contacts.md) |  |
| 1017 | `DomainsService::auditDisplay` | read [analysts](/code/tables/analysts.md) |  |
| 1018 | `DomainsService::auditDisplay` | read [suppliers](/code/tables/suppliers.md) |  |
| 1019 | `DomainsService::auditDisplay` | read [contacts](/code/tables/contacts.md) |  |
| 1020 | `DomainsService::auditDisplay` | read [contracts](/code/tables/contracts.md) |  |
| 1021 | `DomainsService::auditDisplay` | read [users](/code/tables/users.md) |  |

### eventPayload

*public static* - line 1036

The payload every domain.* event carries.

```php
function eventPayload(array $row): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | the domains row |

### dispatchCrud

*private static* - line 1060

Fires the domain.<verb> workflow event for a domain; a failure is logged, never thrown.

```php
function dispatchCrud(PDO $conn, string $verb, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$verb` | `string` |  | 'created' or 'updated' - the event is domain.<verb> |
| `$id` | `int` |  | the domain |

### afterDateChange

*private static* - line 1071

Expiry dates feed the calendar; keep it in step. Never fails the write.

```php
function afterDateChange(PDO $conn): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

