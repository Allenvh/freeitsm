# TypedFields

> Class in [includes/typed_fields.php](/code/files/includes/typed_fields.php.md), line 57

## Methods

| Method | What it does |
|---|---|
| [storageClass()](#storageclass) | The storage class a field type is kept in (text, number, date, boolean or ref), or '' for an unknown type. |
| [isKnownType()](#isknowntype) | True when `$type` is one of the field types in TYPES. |
| [registerRefKind()](#registerrefkind) | Register a kind of thing a `ref` field may point at. |
| [refKind()](#refkind) | The handler registered for a ref kind by `registerRefKind()`, or null when none is. |
| [checkRequired()](#checkrequired) | Required-field enforcement, with the create/update asymmetry the CMDB settled on: required is checked on create, and on update only for fields actually being written. |
| [writeValues()](#writevalues) | Validate and store values for one owner. |
| [coerce()](#coerce) | Turn one raw input into [storage class => value], or null to clear. |
| [validateRef()](#validateref) | Existence + target check for a `ref` value, via the registry. |
| [readValues()](#readvalues) | Values for MANY owners in ONE query, pivoted to [ownerId => [key => value]]. |
| [castOut()](#castout) | Stored string -> the PHP type a caller expects. |
| [refLabels()](#reflabels) | Display labels for reference values, by kind. |
| [parseDate()](#parsedate) | Parse a date/time to 'Y-m-d H:i:s' UTC (throwing twin of apiParseDate; 400 on bad input). |

### storageClass

*public static* - line 99

The storage class a field type is kept in (text, number, date, boolean or ref), or '' for an unknown type.

```php
function storageClass(string $type): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | a field type, one of TYPES |

**Called from:** [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

### isKnownType

*public static* - line 109

True when `$type` is one of the field types in TYPES.

```php
function isKnownType(string $type): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the field type to check |

### registerRefKind

*public static* - line 138

Register a kind of thing a `ref` field may point at.

Same shape as the entity registry in includes/documents.php, and for the
same reason: adding "a headset can point at the person who has it" should
be a registry entry, not surgery on a switch statement.

A handler provides:
  'label'    => string                       — for error messages
  'exists'   => callable(PDO, int): ?int     — returns the row's "target"
                                               discriminator (e.g. class
                                               id), or null if no such row
  'target_label' => string                   — what the discriminator is
                                               called, for error messages

⚠️ A handler answers "is this a real row of the right kind?" and nothing
more. It must never answer "may this actor see it" — that is the calling
service's job, every time.

```php
function registerRefKind(string $kind, array $handler): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | the name a definition's ref_kind uses, e.g. 'cmdb_object', 'user', 'asset' |
| `$handler` | `array` |  | label, target_label, exists and (optionally) labels, as described above |

**Called from:** [includes/typed_fields.php](/code/files/includes/typed_fields.php.md)

### refKind

*public static* - line 148

The handler registered for a ref kind by `registerRefKind()`, or null when none is.

```php
function refKind(string $kind): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | the ref_kind name |

### checkRequired

*public static* - line 168

Required-field enforcement, with the create/update asymmetry the CMDB settled on: required is checked on create, and on update only for fields actually being written.

🔑 Without the asymmetry, ticking "required" on an existing field makes
every older record unsaveable — including by someone editing an unrelated
field. Pre-existing violations are surfaced by an audit view instead.

```php
function checkRequired(array $defs, array $values, bool $isCreate): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$defs` | `array` |  | Canonical definitions, keyed by field key. |
| `$values` | `array` |  | [field key => raw value] being written; a key present with null, '' or [] counts as missing |
| `$isCreate` | `bool` |  | true on create, when a required field absent from `$values` is also an error |

**Called from:** [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md)

### writeValues

*public static* - line 199

Validate and store values for one owner.

Delete-then-insert per field, so an absent or empty value CLEARS it:
🔑 no row means "not set", which is what lets ten televisions carry three
smart-TV fields on three of them and nothing at all on the other seven.

```php
function writeValues(PDO $conn, array $schema, int $ownerId, array $defs, array $values): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$schema` | `array` |  | where the values live (see SHAPE OF A SCHEMA) |
| `$ownerId` | `int` |  | the row the values belong to (e.g. the CMDB object or asset id) |
| `$defs` | `array` |  | Canonical definitions, keyed by field key. |
| `$values` | `array` |  | [field key => raw value]. Unknown keys throw. |

**Called from:** [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md)

### coerce

*public static* - line 254

Turn one raw input into [storage class => value], or null to clear.

Every message here is user-facing and deliberately unchanged from the
CMDB original — they are what the REST API has always returned.

```php
function coerce(PDO $conn, array $schema, array $def, $rawValue, int $ownerId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$schema` | `array` |  | where the values live (see SHAPE OF A SCHEMA); options and self_kind are read here |
| `$def` | `array` |  | one canonical definition (see SHAPE OF A DEFINITION) |
| `$rawValue` |  |  | the value as submitted, not yet trusted; never null or '' (those clear before this) |
| `$ownerId` | `int` |  | the row the value belongs to, so a ref cannot point at itself |

### validateRef

*private static* - line 322

Existence + target check for a `ref` value, via the registry.

```php
function validateRef(PDO $conn, array $def, int $refId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$def` | `array` |  | the ref field's canonical definition (ref_kind, ref_target, label) |
| `$refId` | `int` |  | the id the value points at, above 0 |

### readValues

*public static* - line 367

Values for MANY owners in ONE query, pivoted to [ownerId => [key => value]].

⚠️ There is no per-owner variant on purpose. A list of 500 assets must cost
one query, not 500 — see docs/design/flexible-asset-fields.md §4.4. If you
find yourself calling this in a loop, collect the ids and call it once.

🔑 Owners with no values simply do not appear in the result, and fields
with no value do not appear in an owner's map. Absent means NOT SET, and
callers must keep it distinguishable from "no" / zero / empty — never
`?? false` a boolean field into existence. See §4.5.

Reference fields come back as the raw target id. Turning that into a NAME
is the module's job, deliberately: the CMDB filters those joins by company,
and a generic labeller would happily read across one.

```php
function readValues(PDO $conn, array $schema, array $defs, array $ownerIds): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$schema` | `array` |  | where the values live (see SHAPE OF A SCHEMA) |
| `$defs` | `array` |  | Canonical definitions, keyed by field key. |
| `$ownerIds` | `array` |  | Owner row ids. |

**Returns:** array [ownerId => [field key => value]]

**Called from:** [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

### castOut

*private static* - line 415

Stored string -> the PHP type a caller expects.

```php
function castOut(string $type, $raw)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the field type |
| `$raw` |  |  | the stored column value, not null |

### refLabels

*public static* - line 437

Display labels for reference values, by kind.

🔒 Opt-in and caller-driven, NOT folded into `readValues()`: resolving a
reference to a name reads another table, and whether the caller may see
those rows is the caller's question. Pass only ids you have already
decided the actor may see.

```php
function refLabels(PDO $conn, string $kind, array $ids): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the ref_kind name; an unregistered kind, or one with no labels callable, gives [] |
| `$ids` | `array` |  | the referenced ids, already cleared as visible to the actor |

**Returns:** array [id => label] — ids with no row are simply absent.

**Called from:** [api/assets/get_asset_custom_fields.php](/code/files/api/assets/get_asset_custom_fields.php.md)

### parseDate

*public static* - line 456

Parse a date/time to 'Y-m-d H:i:s' UTC (throwing twin of apiParseDate; 400 on bad input).

```php
function parseDate(string $value, string $field): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the submitted date or date-time, anything DateTimeImmutable reads; no zone means UTC |
| `$field` | `string` |  | the field key, named in the error message |

