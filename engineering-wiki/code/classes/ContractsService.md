# ContractsService

> Class in [includes/services/contracts.php](/code/files/includes/services/contracts.php.md), line 30

## Methods

| Method | What it does |
|---|---|
| [saveContract()](#savecontract) | Create (no id) or update (id present) a contract. Returns ['id','created']. |
| [deleteContract()](#deletecontract) | Delete a contract (+ its term values; nullify referrers explicitly). Returns the id. |
| [saveTerms()](#saveterms) | Bulk-upsert a contract's term values (per tab). Idempotent; returns nothing. |
| [saveSupplier()](#savesupplier) | Create (no id) or update (id present) a supplier. Returns ['id','created']. |
| [deleteSupplier()](#deletesupplier) | Delete a supplier — nullify/clean every referrer, then delete. Returns the id. |
| [effectiveTenant()](#effectivetenant) | A contract row's company, with NULL read as the Default company. |
| [ownerTenant()](#ownertenant) | The company a contract being saved belongs to. A named company must exist and be one the caller can reach - refused, never quietly replaced with another. Not named: the current one on an update, Default on a create. |
| [assertNumberFree()](#assertnumberfree) | Contract numbers are unique within a company - two clients can both have C-001. |
| [assertMovable()](#assertmovable) | Moving a contract to another company is refused while anything of the old company hangs off it - its assets, projects or RFPs would otherwise link across companies, which nothing here allows. Say what is in the way. |
| [costCentreFor()](#costcentrefor) | The cost centre a contract is charged to (GH #160), and what is left of the free text from before 3.5.0. Returns [cost_centre_id, legacy text]. |
| [writeCostCentre()](#writecostcentre) | Sets a contract's cost centre, once that column exists. |
| [writeTenant()](#writetenant) | Sets a contract's company, once that column exists. |
| [assertVisible()](#assertvisible) | A contract in a company the actor cannot reach is reported as not found, never forbidden, so ids cannot be probed. By the ACTOR's company scope, not the analyst's: an API key can be narrower than the analyst it acts as. |
| [partyFields()](#partyfields) | Who the contract is with. Fields not sent keep their current value, so an update that only changes the title leaves a customer contract a customer contract. A supplier id is checked against suppliers as before. |
| [writeParty()](#writeparty) | Sets who a contract is with (supplier or customer), once those columns exist. |
| [partyPayload()](#partypayload) | The party fields of a contract as an array. |
| [loadRow()](#loadrow) | Loads one row from the given table by id; throws not found with `$notFoundMsg` when it does not exist. |
| [contractParams()](#contractparams) | Ordered contract column values for INSERT/UPDATE (matches both statements' column order). |
| [contractFields()](#contractfields) | Collect + validate contract fields (mirrors the API resource's apiContractFieldsFromBody). |
| [lookup()](#lookup) | Validate a nullable lookup id against a table; 422 with the label on unknown. |
| [resolveAnalyst()](#resolveanalyst) | Validate an analyst id exists + is active (throwing twin of apiResolveAnalyst). |
| [parseDateOnly()](#parsedateonly) | Validate an optional YYYY-MM-DD date (throwing twin of apiParseDateOnly). |

### saveContract

*public static* - line 41

Create (no id) or update (id present) a contract. Returns ['id','created'].

```php
function saveContract(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new) plus contract fields, party fields, tenant_id, cost_centre_id or cost_centre (a code) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `ContractsService::saveContract` | **update** [contracts](/code/tables/contracts.md) (`contract_number`, `title`, `description`, `supplier_id`, `contract_owner_id`, `contract_status_id`, `contract_start`, `contract_end`, `notice_period_days`, `notice_date`, `contract_value`, `currency`) |  |
| 85 | `ContractsService::saveContract` | **insert** [contracts](/code/tables/contracts.md) (`contract_number`, `title`, `description`, `supplier_id`, `contract_owner_id`, `contract_status_id`, `contract_start`, `contract_end`, `notice_period_days`, `notice_date`, `contract_value`, `currency`) |  |

**Called from:** [api/contracts/save_contract.php](/code/files/api/contracts/save_contract.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md)

### deleteContract

*public static* - line 105

Delete a contract (+ its term values; nullify referrers explicitly). Returns the id.

```php
function deleteContract(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contract to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 111 | `ContractsService::deleteContract` | **delete** [contract_term_values](/code/tables/contract_term_values.md) |  |
| 117 | `ContractsService::deleteContract` | **delete** [contracts](/code/tables/contracts.md) |  |

**Called from:** [api/contracts/delete_contract.php](/code/files/api/contracts/delete_contract.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md)

### saveTerms

*public static* - line 136

Bulk-upsert a contract's term values (per tab). Idempotent; returns nothing.

```php
function saveTerms(PDO $conn, ActorContext $ctx, int $contractId, $terms): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | the contract whose terms are saved |
| `$terms` |  |  | [{term_tab_id, content}] - one entry per tab to write; must be an array |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 151 | `ContractsService::saveTerms` | read [contract_term_values](/code/tables/contract_term_values.md) |  |
| 155 | `ContractsService::saveTerms` | **update** [contract_term_values](/code/tables/contract_term_values.md) (`content`, `updated_datetime`) |  |
| 158 | `ContractsService::saveTerms` | **insert** [contract_term_values](/code/tables/contract_term_values.md) (`contract_id`, `term_tab_id`, `content`, `created_datetime`) |  |

**Called from:** [api/contracts/save_contract_terms.php](/code/files/api/contracts/save_contract_terms.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md)

### saveSupplier

*public static* - line 173

Create (no id) or update (id present) a supplier. Returns ['id','created'].

```php
function saveSupplier(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new), legal_name (required), trading_name, type and status ids, address, questionnaire dates... |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 212 | `ContractsService::saveSupplier` | **update** [suppliers](/code/tables/suppliers.md) (`legal_name`, `trading_name`, `reg_number`, `vat_number`, `supplier_type_id`, `supplier_status_id`, `address_line_1`, `address_line_2`, `city`, `county`, `postcode`, `country`) |  |
| 222 | `ContractsService::saveSupplier` | **insert** [suppliers](/code/tables/suppliers.md) (`legal_name`, `trading_name`, `reg_number`, `vat_number`, `supplier_type_id`, `supplier_status_id`, `address_line_1`, `address_line_2`, `city`, `county`, `postcode`, `country`) |  |

**Called from:** [api/contracts/save_supplier.php](/code/files/api/contracts/save_supplier.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md)

### deleteSupplier

*public static* - line 238

Delete a supplier — nullify/clean every referrer, then delete. Returns the id.

```php
function deleteSupplier(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the supplier to delete; every reference to it is cleared first |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 243 | `ContractsService::deleteSupplier` | **update** [contacts](/code/tables/contacts.md) (`supplier_id`) |  |
| 244 | `ContractsService::deleteSupplier` | **update** [contracts](/code/tables/contracts.md) (`supplier_id`) |  |
| 246 | `ContractsService::deleteSupplier` | **update** [assets](/code/tables/assets.md) (`supplier_id`) |  |
| 247 | `ContractsService::deleteSupplier` | **update** [rfps](/code/tables/rfps.md) (`chosen_supplier_id`) |  |
| 248 | `ContractsService::deleteSupplier` | **delete** [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 249 | `ContractsService::deleteSupplier` | **delete** [rfp_scores](/code/tables/rfp_scores.md) |  |
| 255 | `ContractsService::deleteSupplier` | **delete** [suppliers](/code/tables/suppliers.md) |  |

**Called from:** [api/contracts/delete_supplier.php](/code/files/api/contracts/delete_supplier.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md)

### effectiveTenant

*private static* - line 274

A contract row's company, with NULL read as the Default company.

```php
function effectiveTenant(PDO $conn, array $row): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the contracts row |

### ownerTenant

*private static* - line 288

The company a contract being saved belongs to. A named company must exist and be one the caller can reach - refused, never quietly replaced with another. Not named: the current one on an update, Default on a create.

```php
function ownerTenant(PDO $conn, ActorContext $ctx, array $in, array $current): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; tenant_id names the company |
| `$current` | `array` |  | the stored contract row on update; [] on create |

### assertNumberFree

*private static* - line 318

Contract numbers are unique within a company - two clients can both have C-001.

```php
function assertNumberFree(PDO $conn, string $number, int $tenant, ?int $exceptId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$number` | `string` |  | the contract number to check |
| `$tenant` | `int` |  | the company it must be unique in (a real id, never null) |
| `$exceptId` | `?int` |  | the contract allowed to hold it (the one being saved), or null on create |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 321 | `ContractsService::assertNumberFree` | read [contracts](/code/tables/contracts.md) |  |
| 324 | `ContractsService::assertNumberFree` | read [contracts](/code/tables/contracts.md) |  |

### assertMovable

*private static* - line 343

Moving a contract to another company is refused while anything of the old company hangs off it - its assets, projects or RFPs would otherwise link across companies, which nothing here allows. Say what is in the way.

```php
function assertMovable(PDO $conn, int $id, int $to): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contract being moved |
| `$to` | `int` |  | the company it is moving to |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 347 | `ContractsService::assertMovable` | read [contract_assets](/code/tables/contract_assets.md), read [assets](/code/tables/assets.md) |  |
| 348 | `ContractsService::assertMovable` | read [project_contracts](/code/tables/project_contracts.md), read [projects](/code/tables/projects.md) |  |
| 349 | `ContractsService::assertMovable` | read [rfps](/code/tables/rfps.md) |  |

### costCentreFor

*private static* - line 381

The cost centre a contract is charged to (GH #160), and what is left of the free text from before 3.5.0. Returns [cost_centre_id, legacy text].

- cost_centre_id: an ACTIVE cost centre of the contract's company, or the
   one it already holds (costCentreCheck). Choosing one clears the old text.
 - cost_centre (text): what the API always took. Now a CODE in the
   contract's company - linked if it exists, refused if not; '' clears.
 - Neither sent: unchanged - but a contract moving company cannot take its
   old company's cost centre with it, so that is refused with a reason.

```php
function costCentreFor(PDO $conn, array $in, array $current, int $tenant): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; cost_centre_id, or cost_centre as a code |
| `$current` | `array` |  | the stored contract row on update; [] on create |
| `$tenant` | `int` |  | the company the contract will belong to |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 400 | `ContractsService::costCentreFor` | read [cost_centres](/code/tables/cost_centres.md) |  |

### writeCostCentre

*private static* - line 415

Sets a contract's cost centre, once that column exists.

```php
function writeCostCentre(PDO $conn, int $id, ?int $ccId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contract |
| `$ccId` | `?int` |  | the cost centre to charge it to, or null for none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 418 | `ContractsService::writeCostCentre` | **update** [contracts](/code/tables/contracts.md) (`cost_centre_id`) |  |

### writeTenant

*private static* - line 427

Sets a contract's company, once that column exists.

```php
function writeTenant(PDO $conn, int $id, int $tenant): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contract |
| `$tenant` | `int` |  | the owning company as a real id; the Default company is stored as NULL |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 430 | `ContractsService::writeTenant` | **update** [contracts](/code/tables/contracts.md) (`tenant_id`) |  |

### assertVisible

*private static* - line 440

A contract in a company the actor cannot reach is reported as not found, never forbidden, so ids cannot be probed. By the ACTOR's company scope, not the analyst's: an API key can be narrower than the analyst it acts as.

```php
function assertVisible(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contract to check |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 443 | `ContractsService::assertVisible` | read [contracts](/code/tables/contracts.md) |  |

### partyFields

*private static* - line 458

Who the contract is with. Fields not sent keep their current value, so an update that only changes the title leaves a customer contract a customer contract. A supplier id is checked against suppliers as before.

```php
function partyFields(PDO $conn, ActorContext $ctx, array $in, array $current): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; party_type, supplier_id, customer_tenant_id, customer_user_id |
| `$current` | `array` |  | the stored contract row on update; [] on create |

### writeParty

*private static* - line 485

Sets who a contract is with (supplier or customer), once those columns exist.

```php
function writeParty(PDO $conn, int $id, array $p): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contract |
| `$p` | `array` |  | the party from `partyFields()`: party_type, customer_tenant_id, customer_user_id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 488 | `ContractsService::writeParty` | **update** [contracts](/code/tables/contracts.md) (`party_type`, `customer_tenant_id`, `customer_user_id`) |  |

### partyPayload

*private static* - line 497

The party fields of a contract as an array.

```php
function partyPayload(array $p): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the party from `partyFields()` |

### loadRow

*private static* - line 509

Loads one row from the given table by id; throws not found with `$notFoundMsg` when it does not exist.

```php
function loadRow(PDO $conn, string $table, int $id, string $notFoundMsg): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table to read, written in code - never user input |
| `$id` | `int` |  | the row's id |
| `$notFoundMsg` | `string` |  | the error message when it does not exist |

### contractParams

*private static* - line 525

Ordered contract column values for INSERT/UPDATE (matches both statements' column order).

```php
function contractParams(array $f): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$f` | `array` |  | the validated fields from `contractFields()`, with supplier_id and cost_centre set |

### contractFields

*private static* - line 541

Collect + validate contract fields (mirrors the API resource's apiContractFieldsFromBody).

```php
function contractFields(PDO $conn, array $in, array $current = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; only keys present are taken from here |
| `$current` | `array` | `[]` | the stored contract row on update, for absent keys; [] on create |

### lookup

*private static* - line 609

Validate a nullable lookup id against a table; 422 with the label on unknown.

```php
function lookup(PDO $conn, string $table, $value, string $label): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the lookup table, written in code - never user input |
| `$value` |  |  | the id from the request; null or '' gives null |
| `$label` | `string` |  | what the table holds, for the error message, e.g. 'supplier' |

### resolveAnalyst

*private static* - line 628

Validate an analyst id exists + is active (throwing twin of apiResolveAnalyst).

```php
function resolveAnalyst(PDO $conn, int $analystId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | the analyst named as the contract's owner - not the actor |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 630 | `ContractsService::resolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |

### parseDateOnly

*private static* - line 643

Validate an optional YYYY-MM-DD date (throwing twin of apiParseDateOnly).

```php
function parseDateOnly($value, string $field): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the date from the request, Y-m-d; null or '' gives null |
| `$field` | `string` |  | the request field name, used in the error message |

