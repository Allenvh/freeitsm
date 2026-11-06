# CostCentresService

> Class in [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md), line 42

## Methods

| Method | What it does |
|---|---|
| [ready()](#ready) | Has Database Verification created the table yet? |
| [companiesFor()](#companiesfor) | The companies this caller may manage cost centres for: [[id, name, is_default], ...]. |
| [canReach()](#canreach) | True when the actor may reach the company. |
| [assertCompany()](#assertcompany) | The company exists and this caller may use it - or a ServiceError. |
| [listForCompany()](#listforcompany) | Every cost centre of one company, active and inactive, ordered by code, each with its parent's code and how many children it has. |
| [load()](#load) | One cost centre, or not_found (also when it is in a company the caller cannot reach). |
| [create()](#create) | Add one. `$in`: code, name (required); description, parent_id OR parent_code, is_active (optional). |
| [update()](#update) | Change one. Only the keys present in `$in` are touched (PATCH semantics). |
| [delete()](#delete) | Delete one - refused while anything sits below it. Make it inactive instead. |
| [sync()](#sync) | Bring a company's list into line with an incoming one, matched on CODE. |
| [cleanCode()](#cleancode) | Trimmed, never otherwise changed: "0010" stays "0010". |
| [cleanName()](#cleanname) | A cost centre name with control characters removed; throws when blank or too long. |
| [cleanDescription()](#cleandescription) | A cost centre description with control characters removed, or null when blank; throws when too long. |
| [cleanBool()](#cleanbool) | true/false, 1/0, yes/no, y/n, active/inactive - what a spreadsheet or an ERP export says. |
| [codeTaken()](#codetaken) | Throws the "code already used" conflict. |
| [assertCodeFree()](#assertcodefree) | Throws when another cost centre in the company has the same code, ignoring case. |
| [resolveParentInput()](#resolveparentinput) | parent_id or parent_code from the input -> a parent id or null, checked: same company, not itself, not below itself. |

### ready

*public static* - line 49

Has Database Verification created the table yet?

```php
function ready(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `CostCentresService::ready` | read [cost_centres](/code/tables/cost_centres.md) |  |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md)

### companiesFor

*public static* - line 64

The companies this caller may manage cost centres for: [[id, name, is_default], ...].

```php
function companiesFor(PDO $conn, ActorContext $actor): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting; their companyScope decides which companies are listed |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)

### canReach

*private static* - line 82

True when the actor may reach the company.

```php
function canReach(ActorContext $actor, int $tenantId): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$actor` | `ActorContext` |  | who is acting; a null companyScope reaches every company |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### assertCompany

*public static* - line 92

The company exists and this caller may use it - or a ServiceError.

```php
function assertCompany(PDO $conn, ActorContext $actor, int $tenantId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### listForCompany

*public static* - line 109

Every cost centre of one company, active and inactive, ordered by code, each with its parent's code and how many children it has.

```php
function listForCompany(PDO $conn, ActorContext $actor, int $tenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting; must reach `$tenantId` |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 113 | `CostCentresService::listForCompany` | read [cost_centres](/code/tables/cost_centres.md) |  |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)

### load

*public static* - line 130

One cost centre, or not_found (also when it is in a company the caller cannot reach).

```php
function load(PDO $conn, ActorContext $actor, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting; a cost centre outside their companies reads as not found |
| `$id` | `int` |  | the cost_centres row to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `CostCentresService::load` | read [cost_centres](/code/tables/cost_centres.md), read [tenants](/code/tables/tenants.md) |  |

### create

*public static* - line 159

Add one. `$in`: code, name (required); description, parent_id OR parent_code, is_active (optional).

```php
function create(PDO $conn, ActorContext $actor, int $tenantId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting; must reach `$tenantId` |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | code and name (required); description, parent_id or parent_code, is_active |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 169 | `CostCentresService::create` | **insert** [cost_centres](/code/tables/cost_centres.md) (`tenant_id`, `code`, `name`, `description`, `parent_id`, `is_active`) |  |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md)

### update

*public static* - line 186

Change one. Only the keys present in `$in` are touched (PATCH semantics).

```php
function update(PDO $conn, ActorContext $actor, int $id, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting |
| `$id` | `int` |  | the cost_centres row to change |
| `$in` | `array` |  | any of code, name, description, is_active, parent_id or parent_code; absent keys are left alone |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 205 | `CostCentresService::update` | **update** [cost_centres](/code/tables/cost_centres.md) |  |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md)

### delete

*public static* - line 220

Delete one - refused while anything sits below it. Make it inactive instead.

```php
function delete(PDO $conn, ActorContext $actor, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting |
| `$id` | `int` |  | the cost_centres row to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 237 | `CostCentresService::delete` | **delete** [cost_centres](/code/tables/cost_centres.md) |  |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md)

### sync

*public static* - line 267

Bring a company's list into line with an incoming one, matched on CODE.

`$rows`: [['line' => n, 'code' => ..., 'name' => ..., 'description' => ...,
         'parent_code' => ..., 'is_active' => bool|string], ...]
A key that is ABSENT means "leave that field as it is" - so an API caller
can send only codes and names. A key present but empty means empty:
parent_code '' = top level, description '' = none.

`$opts`: dry_run (bool)            work it all out, write nothing
       deactivate_missing (bool) make active cost centres that are NOT in
                                 `$rows` inactive - for a full list from the
                                 finance system. Never deletes.

🔑 ALL OR NOTHING. If any row has a problem, nothing is written and every
problem is reported, with its line. A half-applied nightly sync is worse
than none: the next one cannot tell what the last one did.

```php
function sync(PDO $conn, ActorContext $actor, int $tenantId, array $rows, array $opts = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actor` | `ActorContext` |  | who is acting; must reach `$tenantId` |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$rows` | `array` |  | the incoming list, each {line?, code, name?, description?, parent_code?, is_active?} - see above |
| `$opts` | `array` | `[]` | dry_run and deactivate_missing, both bool - see above |

**Returns:** array{applied: bool, dry_run: bool, counts: array, errors: array, rows: array}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 399 | `CostCentresService::sync` | **insert** [cost_centres](/code/tables/cost_centres.md) (`tenant_id`, `code`, `name`, `description`, `is_active`) |  |
| 407 | `CostCentresService::sync` | **update** [cost_centres](/code/tables/cost_centres.md) |  |
| 414 | `CostCentresService::sync` | **update** [cost_centres](/code/tables/cost_centres.md) (`parent_id`) |  |
| 421 | `CostCentresService::sync` | **update** [cost_centres](/code/tables/cost_centres.md) (`is_active`) |  |

**Called from:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md)

### cleanCode

*public static* - line 442

Trimmed, never otherwise changed: "0010" stays "0010".

```php
function cleanCode($v): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the code as sent |

### cleanName

*private static* - line 456

A cost centre name with control characters removed; throws when blank or too long.

```php
function cleanName($v): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the name as sent |

### cleanDescription

*private static* - line 469

A cost centre description with control characters removed, or null when blank; throws when too long.

```php
function cleanDescription($v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the description as sent; blank gives null |

### cleanBool

*public static* - line 483

true/false, 1/0, yes/no, y/n, active/inactive - what a spreadsheet or an ERP export says.

```php
function cleanBool($v, string $field): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | a bool, 0/1, or a word such as yes, no, active, inactive (English or German) |
| `$field` | `string` |  | the field name, used in the error message |

### codeTaken

*private static* - line 499

Throws the "code already used" conflict.

```php
function codeTaken(string $code): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `string` |  | the code that clashed, for the message |

### assertCodeFree

*private static* - line 510

Throws when another cost centre in the company has the same code, ignoring case.

```php
function assertCodeFree(PDO $conn, int $tenantId, string $code, ?int $exceptId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$code` | `string` |  | the code to check, compared ignoring case |
| `$exceptId` | `?int` |  | the cost centre allowed to hold it (the one being changed), or null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 513 | `CostCentresService::assertCodeFree` | read [cost_centres](/code/tables/cost_centres.md) |  |

### resolveParentInput

*private static* - line 525

parent_id or parent_code from the input -> a parent id or null, checked: same company, not itself, not below itself.

```php
function resolveParentInput(PDO $conn, int $tenantId, array $in, ?int $selfId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; parent_id, or parent_code within the same company - blank or 0 means top level |
| `$selfId` | `?int` |  | the cost centre being changed, for the loop check; null on create |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 531 | `CostCentresService::resolveParentInput` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 536 | `CostCentresService::resolveParentInput` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 549 | `CostCentresService::resolveParentInput` | read [cost_centres](/code/tables/cost_centres.md) |  |

