# JsonSchema4

> Class in [api/v1/dev/jsonschema4.php](/code/files/api/v1/dev/jsonschema4.php.md), line 17

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Keeps the root schema, which `$ref` pointers are resolved against. |
| [validate()](#validate) | Validates data against a schema (the root schema by default), adding any failures to `$errors`. |
| [err()](#err) | Records one validation failure as "path: message". |
| [resolveRef()](#resolveref) | Follows a local JSON pointer (`$ref` starting #/) through the root schema, decoding ~1 and ~0, and returns the schema it points at - null when it is external or missing. |
| [check()](#check) | Checks one value against one schema and records every failure: `$ref`, allOf / anyOf / oneOf / not, type, enum, then the object, array, string and number keywords the OpenAPI 3.0 meta-schema uses. |
| [isList()](#islist) | Whether a PHP array is a JSON list - an empty array counts as both list and object. |
| [isAssoc()](#isassoc) | Whether a PHP array is a non-empty JSON object (not a list). |
| [isObject()](#isobject) | Whether a value is a JSON object - an associative array, or an empty array. |
| [typeOf()](#typeof) | The JSON Schema type name of a PHP value, for error messages. |
| [matchesAnyType()](#matchesanytype) | Whether a value matches at least one of the given JSON Schema types. |
| [matchesType()](#matchestype) | Whether a value matches one JSON Schema type - integer is a PHP int only, number an int or float. |
| [inEnum()](#inenum) | Whether a value is one of an enum's values, comparing strictly and then by JSON encoding (so arrays and objects match). |

### __construct

*public* - line 25

Keeps the root schema, which `$ref` pointers are resolved against.

```php
function __construct(array $root)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$root` | `array` |  | the whole decoded schema document, e.g. the OpenAPI 3.0 meta-schema |

### validate

*public* - line 36

Validates data against a schema (the root schema by default), adding any failures to `$errors`.

```php
function validate($data, ?array $schema = null, string $path = '#'): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` |  |  | the decoded JSON value to check |
| `$schema` | `?array` | `null` | the schema to check against; null uses the root schema |
| `$path` | `string` | `'#'` | where `$data` sits in the document, as a JSON pointer, for error messages |

**Returns:** bool  true when this call added no errors

### err

*private* - line 49

Records one validation failure as "path: message".

```php
function err(string $path, string $msg)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | where the failure is, as a JSON pointer |
| `$msg` | `string` |  | what is wrong |

### resolveRef

*private* - line 58

Follows a local JSON pointer (`$ref` starting #/) through the root schema, decoding ~1 and ~0, and returns the schema it points at - null when it is external or missing.

```php
function resolveRef(string $ref): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ref` | `string` |  | the `$ref` value, e.g. '#/definitions/Schema' |

### check

*private* - line 82

Checks one value against one schema and records every failure: `$ref`, allOf / anyOf / oneOf / not, type, enum, then the object, array, string and number keywords the OpenAPI 3.0 meta-schema uses.

A type mismatch stops the remaining keyword checks for that value, since they
would only repeat it.

```php
function check($data, array $schema, string $path): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` |  |  | the decoded JSON value to check |
| `$schema` | `array` |  | the schema to check it against |
| `$path` | `string` |  | where `$data` sits in the document, as a JSON pointer, for error messages |

### isList

*private* - line 184

Whether a PHP array is a JSON list - an empty array counts as both list and object.

```php
function isList($d): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |

### isAssoc

*private* - line 190

Whether a PHP array is a non-empty JSON object (not a list).

```php
function isAssoc($d): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |

### isObject

*private* - line 196

Whether a value is a JSON object - an associative array, or an empty array.

```php
function isObject($d): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |

### typeOf

*private* - line 203

The JSON Schema type name of a PHP value, for error messages.

```php
function typeOf($d): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |

### matchesAnyType

*private* - line 218

Whether a value matches at least one of the given JSON Schema types.

```php
function matchesAnyType($d, array $types): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |
| `$types` | `array` |  | JSON Schema type names, e.g. ['string', 'null'] |

### matchesType

*private* - line 229

Whether a value matches one JSON Schema type - integer is a PHP int only, number an int or float.

```php
function matchesType($d, string $t): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |
| `$t` | `string` |  | one JSON Schema type name, e.g. 'integer'; an unknown name never matches |

### inEnum

*private* - line 248

Whether a value is one of an enum's values, comparing strictly and then by JSON encoding (so arrays and objects match).

```php
function inEnum($d, array $enum): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | any decoded JSON value |
| `$enum` | `array` |  | the schema's enum values |

