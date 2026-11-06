# SoftwareService

> Class in [includes/services/software.php](/code/files/includes/services/software.php.md), line 31

## Methods

| Method | What it does |
|---|---|
| [saveLicence()](#savelicence) | Create (no id) or update (id present) a licence. Returns ['id' => int, 'created' => bool]. |
| [deleteLicence()](#deletelicence) | Delete a licence (leaf table). Returns the id. 404 if it does not exist. |
| [resyncRenewalCalendar()](#resyncrenewalcalendar) | Push renewal dates back into the calendar after any licence write (#1551). |
| [loadLicenceRow()](#loadlicencerow) | Load a licence row for update/delete, or throw 404. |
| [collectFields()](#collectfields) | Collect + validate the writable licence fields (mirrors the API resource's apiSoftwareLicenceFields coercions). On update, absent keys fall back to the current row; on create they fall back to the column default. |
| [parseDateOnly()](#parsedateonly) | Validate an optional YYYY-MM-DD date (throwing version of apiParseDateOnly). |

### saveLicence

*public static* - line 40

Create (no id) or update (id present) a licence. Returns ['id' => int, 'created' => bool].

```php
function saveLicence(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (a licence to change; absent creates one), app_id, licence_type, licence_key, quantity, renewal_date, notice_period_days, portal_url, cost, currency, purchase_date, vendor_contact, notes, status |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `SoftwareService::saveLicence` | **update** [software_licences](/code/tables/software_licences.md) (`app_id`, `licence_type`, `licence_key`, `quantity`, `renewal_date`, `notice_period_days`, `portal_url`, `cost`, `currency`, `purchase_date`, `vendor_contact`, `notes`) |  |
| 67 | `SoftwareService::saveLicence` | **insert** [software_licences](/code/tables/software_licences.md) (`app_id`, `licence_type`, `licence_key`, `quantity`, `renewal_date`, `notice_period_days`, `portal_url`, `cost`, `currency`, `purchase_date`, `vendor_contact`, `notes`) |  |

**Called from:** [api/software/save_licence.php](/code/files/api/software/save_licence.php.md), [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md)

### deleteLicence

*public static* - line 88

Delete a licence (leaf table). Returns the id. 404 if it does not exist.

```php
function deleteLicence(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the licence (software_licences.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 91 | `SoftwareService::deleteLicence` | **delete** [software_licences](/code/tables/software_licences.md) |  |

**Called from:** [api/software/delete_licence.php](/code/files/api/software/delete_licence.php.md), [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md)

### resyncRenewalCalendar

*private static* - line 114

Push renewal dates back into the calendar after any licence write (#1551).

⚠️ NEVER ALLOWED TO FAIL THE WRITE. The licence is the record; its calendar
entries are a convenience derived from it. A calendar table that is missing,
mid-migration or momentarily locked must not turn "save this licence" into
an error — the licence is already committed by the time this runs, so
throwing here would report a failure for something that succeeded.

A cheap full resync, exactly as the asset warranty generator does it:
licence edits are rare and the event set is small, and regenerating the lot
is the only version with no drift in it.

```php
function resyncRenewalCalendar(PDO $conn): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

### loadLicenceRow

*private static* - line 130

Load a licence row for update/delete, or throw 404.

```php
function loadLicenceRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the licence (software_licences.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 132 | `SoftwareService::loadLicenceRow` | read [software_licences](/code/tables/software_licences.md) |  |

### collectFields

*private static* - line 149

Collect + validate the writable licence fields (mirrors the API resource's apiSoftwareLicenceFields coercions). On update, absent keys fall back to the current row; on create they fall back to the column default.

```php
function collectFields(PDO $conn, array $in, array $current = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the licence fields as sent (see `saveLicence()`) |
| `$current` | `array` | `[]` | the stored row when updating, so absent keys keep their value; empty when creating |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 160 | `SoftwareService::collectFields` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

### parseDateOnly

*private static* - line 209

Validate an optional YYYY-MM-DD date (throwing version of apiParseDateOnly).

```php
function parseDateOnly($value, string $field): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the date as sent (YYYY-MM-DD); null or '' gives null |
| `$field` | `string` |  | the field name, used in the error message |

