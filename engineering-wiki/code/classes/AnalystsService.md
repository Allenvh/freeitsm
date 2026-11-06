# AnalystsService

> Class in [includes/services/analysts.php](/code/files/includes/services/analysts.php.md), line 35

## Methods

| Method | What it does |
|---|---|
| [load()](#load) | The analyst row, or not_found. |
| [create()](#create) | Create an analyst. `$in`: email (required), full_name, username (made unique), auth_provider_id. Returns the new id. |
| [update()](#update) | Change an analyst. `$in`: any of full_name, email, is_active, auth_provider_id. Setting auth_provider_id switches off "Follow team" (GH #41), which would otherwise replace it on the next team change. |
| [access()](#access) | What the analyst has been given directly, plus (when `$effective`) what they end up with once team grants and hand-assigned roles are counted: effective.modules null = every module effective.companies every company id they can reach effective.capabilities ⚠️ The effective helpers cache per request, so only ask after any write. |
| [setAccess()](#setaccess) | Set access. `$in`: any of is_admin, all_modules, modules[], all_companies, companies[], capabilities[]. Each list REPLACES what the analyst has in that part; anything not sent stays as it is. |
| [managedRoleId()](#managedroleid) | The REST-API-managed role's id for an analyst, or null. |
| [assertNotLastAdmin()](#assertnotlastadmin) | Refuse when this analyst is the last ACTIVE administrator - removing them would leave nobody able to open System. Same rule as System -> Analysts. |
| [writeManagedRole()](#writemanagedrole) | Sets the capabilities of the analyst's API-managed role, creating the role the first time and assigning it to them. |
| [moduleKeys()](#modulekeys) | Validates a list of module keys against the module registry. |
| [companyIds()](#companyids) | Validates a list of company ids against the known companies. |
| [capabilityKeys()](#capabilitykeys) | Validates a list of capability keys, mapping retired keys to their current ones and removing duplicates. |
| [stringList()](#stringlist) | A list of unique trimmed strings; throws when the value is not an array. |
| [flag()](#flag) | A true/false input as a bool; accepts true, false, 0, 1, '0' or '1' and throws on anything else. |
| [cleanEmail()](#cleanemail) | A lower-case, validated email address, or null when blank. |
| [assertEmailFree()](#assertemailfree) | Throws a conflict when another analyst already uses the email. |
| [providerId()](#providerid) | A validated sign-in provider id, or null for none. |
| [uniqueUsername()](#uniqueusername) | The asked-for username, else the email's local part, made unique - the SSO sign-up rule. |

### load

*public static* - line 42

The analyst row, or not_found.

```php
function load(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the analyst to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `AnalystsService::load` | read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md)

### create

*public static* - line 59

Create an analyst. `$in`: email (required), full_name, username (made unique), auth_provider_id. Returns the new id.

```php
function create(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields: email (required), full_name, username, auth_provider_id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 77 | `AnalystsService::create` | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `can_access_all_modules`, `can_access_all_tenants`, `created_datetime`, `last_modified_datetime`) |  |

**Called from:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md)

### update

*public static* - line 92

Change an analyst. `$in`: any of full_name, email, is_active, auth_provider_id. Setting auth_provider_id switches off "Follow team" (GH #41), which would otherwise replace it on the next team change.

```php
function update(PDO $conn, ActorContext $ctx, int $id, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the analyst to change |
| `$in` | `array` |  | the fields to change, any of full_name, email, is_active, auth_provider_id; absent keys are left alone |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 139 | `AnalystsService::update` | **update** [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md)

### access

*public static* - line 154

What the analyst has been given directly, plus (when `$effective`) what they end up with once team grants and hand-assigned roles are counted: effective.modules null = every module effective.companies every company id they can reach effective.capabilities ⚠️ The effective helpers cache per request, so only ask after any write.

```php
function access(PDO $conn, int $id, bool $effective = true): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the analyst whose access to read |
| `$effective` | `bool` | `true` | true to add the 'effective' block (team grants and hand-assigned roles included) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 157 | `AnalystsService::access` | read [analyst_modules](/code/tables/analyst_modules.md) |  |
| 159 | `AnalystsService::access` | read [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 163 | `AnalystsService::access` | read [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |

**Called from:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md)

### setAccess

*public static* - line 193

Set access. `$in`: any of is_admin, all_modules, modules[], all_companies, companies[], capabilities[]. Each list REPLACES what the analyst has in that part; anything not sent stays as it is.

```php
function setAccess(PDO $conn, ActorContext $ctx, int $id, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the analyst whose access to set |
| `$in` | `array` |  | any of is_admin, all_modules, modules[], all_companies, companies[], capabilities[]; absent parts stay |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 219 | `AnalystsService::setAccess` | **update** [analysts](/code/tables/analysts.md) (`is_admin`, `can_access_all_modules`, `can_access_all_tenants`, `last_modified_datetime`) |  |
| 224 | `AnalystsService::setAccess` | **delete** [analyst_modules](/code/tables/analyst_modules.md) |  |
| 225 | `AnalystsService::setAccess` | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |
| 229 | `AnalystsService::setAccess` | **delete** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 230 | `AnalystsService::setAccess` | **insert** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) (`analyst_id`, `tenant_id`) |  |

**Called from:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md)

### managedRoleId

*public static* - line 244

The REST-API-managed role's id for an analyst, or null.

```php
function managedRoleId(PDO $conn, int $analystId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 247 | `AnalystsService::managedRoleId` | read [rbac_roles](/code/tables/rbac_roles.md) |  |

### assertNotLastAdmin

*public static* - line 262

Refuse when this analyst is the last ACTIVE administrator - removing them would leave nobody able to open System. Same rule as System -> Analysts.

```php
function assertNotLastAdmin(PDO $conn, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the administrator about to be demoted or deactivated |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 264 | `AnalystsService::assertNotLastAdmin` | read [analysts](/code/tables/analysts.md) |  |

### writeManagedRole

*private static* - line 283

Sets the capabilities of the analyst's API-managed role, creating the role the first time and assigning it to them.

```php
function writeManagedRole(PDO $conn, ActorContext $ctx, array $a, array $caps): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$a` | `array` |  | the analyst row, as loaded by `load()` |
| `$caps` | `array` |  | the validated capability keys the role should hold, replacing any it has; [] empties it |

**Writes:** rbac_roles, rbac_role_capabilities, rbac_analyst_roles - the role the REST API keeps for this analyst

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 289 | `AnalystsService::writeManagedRole` | **insert** [rbac_roles](/code/tables/rbac_roles.md) (`name`, `description`, `is_active`, `created_by_id`, `managed_for_analyst_id`, `created_datetime`, `updated_datetime`) |  |
| 299 | `AnalystsService::writeManagedRole` | **delete** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |
| 300 | `AnalystsService::writeManagedRole` | **insert** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) (`role_id`, `capability_key`) |  |
| 302 | `AnalystsService::writeManagedRole` | **insert** [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) (`analyst_id`, `role_id`, `created_datetime`) |  |
| 304 | `AnalystsService::writeManagedRole` | **update** [rbac_roles](/code/tables/rbac_roles.md) (`updated_datetime`) |  |

### moduleKeys

*private static* - line 312

Validates a list of module keys against the module registry.

```php
function moduleKeys($v): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the 'modules' value from the request - must be an array of module keys |

### companyIds

*private static* - line 328

Validates a list of company ids against the known companies.

```php
function companyIds(PDO $conn, $v): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$v` |  |  | the 'companies' value from the request - must be an array of tenant ids |

### capabilityKeys

*private static* - line 348

Validates a list of capability keys, mapping retired keys to their current ones and removing duplicates.

```php
function capabilityKeys($v): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the 'capabilities' value from the request - must be an array of capability keys |

### stringList

*private static* - line 368

A list of unique trimmed strings; throws when the value is not an array.

```php
function stringList($v, string $field): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the request value that should be an array |
| `$field` | `string` |  | the request field name, used in the error message |

### flag

*private static* - line 382

A true/false input as a bool; accepts true, false, 0, 1, '0' or '1' and throws on anything else.

```php
function flag($v, string $field): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the request value to read as true or false |
| `$field` | `string` |  | the request field name, used in the error message |

### cleanEmail

*private static* - line 393

A lower-case, validated email address, or null when blank.

```php
function cleanEmail($v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the email address from the request; null or blank gives null |

### assertEmailFree

*private static* - line 409

Throws a conflict when another analyst already uses the email.

```php
function assertEmailFree(PDO $conn, string $email, int $exceptId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `string` |  | the lower-cased email address to check |
| `$exceptId` | `int` |  | the analyst allowed to hold it already (the one being changed); 0 on create |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 411 | `AnalystsService::assertEmailFree` | read [analysts](/code/tables/analysts.md) |  |

### providerId

*private static* - line 423

A validated sign-in provider id, or null for none.

```php
function providerId(PDO $conn, $v): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$v` |  |  | the auth_provider_id from the request; null, '', 0 or '0' means none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 427 | `AnalystsService::providerId` | read [auth_providers](/code/tables/auth_providers.md) |  |

### uniqueUsername

*private static* - line 441

The asked-for username, else the email's local part, made unique - the SSO sign-up rule.

```php
function uniqueUsername(PDO $conn, string $want, string $email): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$want` | `string` |  | the username asked for; '' falls back to the email's local part |
| `$email` | `string` |  | the analyst's email address |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 445 | `AnalystsService::uniqueUsername` | read [analysts](/code/tables/analysts.md) |  |

