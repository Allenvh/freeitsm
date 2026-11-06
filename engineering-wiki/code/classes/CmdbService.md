# CmdbService

> Class in [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md), line 42

## Methods

| Method | What it does |
|---|---|
| [assertScope()](#assertscope) | Multi-tenancy gate: refuse to touch a CI outside the actor's companies. companyScope null = all companies (single-company install or an all-access actor) → no gate. Framed as not-found so it never reveals another company's CI. `$row` must include tenant_id. |
| [resolveTenantForCreate()](#resolvetenantforcreate) | Resolve the company a new CI should belong to, and refuse one the actor can't reach. Callers may pass an explicit company; otherwise it's the actor's current company context. |
| [assertSameCompany()](#assertsamecompany) | The same-company invariant. A CI belongs to exactly one company, so every link between two CIs — parent/child, a relationship, an object_ref property — must have both ends in the same company. Without this a link becomes a side channel: you can't read the other CI, but you can still discover it exists and bind your own records to it. |
| [saveObject()](#saveobject) | Create (no id) or update (id present) an object. Returns ['id','created']. |
| [createObject()](#createobject) | Creates a CI: validates the name, class, parent, company and properties, inserts it with its property values and fires the cmdb.object.created workflow event. |
| [updateObject()](#updateobject) | Updates a CI's name, parent, planned flag and properties; the class never changes. Fires the cmdb.object.updated workflow event. |
| [deleteObject()](#deleteobject) | Delete an object + its whole descendant tree (explicit, not FK cascade). Returns ['id','deleted_descendants']. |
| [createRelationship()](#createrelationship) | Create a relationship from `$fromId`. Returns ['id','to_object_id','verb']. |
| [deleteRelationship()](#deleterelationship) | Delete a relationship by row id. If `$objectId` is given (API), the relationship must involve that object and a miss is a 404; if null (UI), it's an unscoped delete that succeeds regardless. Returns the relationship id. |
| [tenantOf()](#tenantof) | The company id a CI effectively belongs to (NULL stored = Default's). |
| [assertObjectRefsInCompany()](#assertobjectrefsincompany) | object_ref properties point at another CI, so they're a third way to link across companies — alongside parent/child and relationships. Every object_ref value being written must resolve to a CI in the same company as the CI being written. |
| [loadObjectRow()](#loadobjectrow) | Loads one CI with its class name and key; throws not found when it does not exist. |
| [classDefs()](#classdefs) | Property definitions for a class, keyed by property_key, in the canonical TypedFields shape. |
| [valueSchema()](#valueschema) | Where CMDB property values live, for TypedFields. |
| [normaliseProperties()](#normaliseproperties) | Normalise the two accepted property shapes to a { property_key: value } map. The API's `properties` map passes through (unknown keys -> 422 later); the UI's `property_values` id-list is translated, dropping ids not in the class. |
| [propertyOptionValues()](#propertyoptionvalues) | Dropdown option values for one property. |
| [writeProperties()](#writeproperties) | Validate + write property values (keyed by property_key). Unknown keys -> 422. |
| [checkRequired()](#checkrequired) | Required-property enforcement — create/update asymmetry. |
| [validateParent()](#validateparent) | Parent validation incl. the cycle walk. |
| [descendantIds()](#descendantids) | All descendant ids of an object (excluding itself), cycle-safe. |

### assertScope

*private static* - line 52

Multi-tenancy gate: refuse to touch a CI outside the actor's companies. companyScope null = all companies (single-company install or an all-access actor) → no gate. Framed as not-found so it never reveals another company's CI. `$row` must include tenant_id.

```php
function assertScope(PDO $conn, ActorContext $ctx, array $row): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the cmdb_objects row; must include tenant_id |

### resolveTenantForCreate

*private static* - line 74

Resolve the company a new CI should belong to, and refuse one the actor can't reach. Callers may pass an explicit company; otherwise it's the actor's current company context.

Returns the value to STORE: the Default company normalises to NULL so
UI-created and API-created CIs store the same thing (every read treats
NULL as Default's), matching AssetsService.

```php
function resolveTenantForCreate(PDO $conn, ActorContext $ctx, array $in): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; tenant_id or company_id names the company, else the actor's active one |

### assertSameCompany

*private static* - line 104

The same-company invariant. A CI belongs to exactly one company, so every link between two CIs — parent/child, a relationship, an object_ref property — must have both ends in the same company. Without this a link becomes a side channel: you can't read the other CI, but you can still discover it exists and bind your own records to it.

```php
function assertSameCompany(PDO $conn, int $aId, int $bId, string $what): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$aId` | `int` |  | one cmdb_objects id |
| `$bId` | `int` |  | the other cmdb_objects id |
| `$what` | `string` |  | the kind of link, for the error message, e.g. 'parent' or 'relationship' |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 109 | `CmdbService::assertSameCompany` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

### saveObject

*public static* - line 134

Create (no id) or update (id present) an object. Returns ['id','created'].

```php
function saveObject(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields: id (absent = new) plus those `createObject()` / `updateObject()` take |

**Called from:** [api/cmdb/save_object.php](/code/files/api/cmdb/save_object.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md)

### createObject

*private static* - line 148

Creates a CI: validates the name, class, parent, company and properties, inserts it with its property values and fires the cmdb.object.created workflow event.

```php
function createObject(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | name, class_id or class_key, parent_id, is_planned, tenant_id or company_id, properties or property_values |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 160 | `CmdbService::createObject` | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 163 | `CmdbService::createObject` | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 201 | `CmdbService::createObject` | **insert** [cmdb_objects](/code/tables/cmdb_objects.md) (`class_id`, `name`, `parent_id`, `is_planned`, `tenant_id`, `created_datetime`, `updated_datetime`) |  |

### updateObject

*private static* - line 230

Updates a CI's name, parent, planned flag and properties; the class never changes. Fires the cmdb.object.updated workflow event.

```php
function updateObject(PDO $conn, ActorContext $ctx, int $objectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `int` |  | the CI to update |
| `$in` | `array` |  | any of name, parent_id, is_planned, properties or property_values; the class cannot change |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 267 | `CmdbService::updateObject` | **update** [cmdb_objects](/code/tables/cmdb_objects.md) (`name`, `parent_id`, `is_planned`, `updated_datetime`) |  |
| 270 | `CmdbService::updateObject` | **update** [cmdb_objects](/code/tables/cmdb_objects.md) (`name`, `parent_id`, `updated_datetime`) |  |

### deleteObject

*public static* - line 290

Delete an object + its whole descendant tree (explicit, not FK cascade). Returns ['id','deleted_descendants'].

```php
function deleteObject(PDO $conn, ActorContext $ctx, int $objectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `int` |  | the CI to delete, with every descendant |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 301 | `CmdbService::deleteObject` | **update** [cmdb_object_properties](/code/tables/cmdb_object_properties.md) (`value_object_id`) |  |
| 302 | `CmdbService::deleteObject` | **delete** [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |
| 304 | `CmdbService::deleteObject` | **update** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`cmdb_relationship_id`), read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 307 | `CmdbService::deleteObject` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 310 | `CmdbService::deleteObject` | **delete** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 311 | `CmdbService::deleteObject` | **delete** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 312 | `CmdbService::deleteObject` | **delete** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |
| 314 | `CmdbService::deleteObject` | **delete** [cmdb_objects](/code/tables/cmdb_objects.md) |  |

**Called from:** [api/cmdb/delete_object.php](/code/files/api/cmdb/delete_object.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md)

### createRelationship

*public static* - line 335

Create a relationship from `$fromId`. Returns ['id','to_object_id','verb'].

```php
function createRelationship(PDO $conn, ActorContext $ctx, int $fromId, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$fromId` | `int` |  | the CI the relationship starts from |
| `$in` | `array` |  | to_object_id (required), and relationship_type_id or verb |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 354 | `CmdbService::createRelationship` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 357 | `CmdbService::createRelationship` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 369 | `CmdbService::createRelationship` | **insert** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) (`from_object_id`, `to_object_id`, `relationship_type_id`, `created_datetime`) |  |

**Called from:** [api/cmdb/save_object_relationship.php](/code/files/api/cmdb/save_object_relationship.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md)

### deleteRelationship

*public static* - line 389

Delete a relationship by row id. If `$objectId` is given (API), the relationship must involve that object and a miss is a 404; if null (UI), it's an unscoped delete that succeeds regardless. Returns the relationship id.

```php
function deleteRelationship(PDO $conn, ActorContext $ctx, int $relId, ?int $objectId = null): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$relId` | `int` |  | the cmdb_object_relationships row to delete |
| `$objectId` | `?int` | `null` | the CI it must involve (API), or null to check only its 'from' end (UI) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 393 | `CmdbService::deleteRelationship` | **delete** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 401 | `CmdbService::deleteRelationship` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 408 | `CmdbService::deleteRelationship` | **delete** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |

**Called from:** [api/cmdb/delete_object_relationship.php](/code/files/api/cmdb/delete_object_relationship.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md)

### tenantOf

*private static* - line 422

The company id a CI effectively belongs to (NULL stored = Default's).

```php
function tenantOf(PDO $conn, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the cmdb_objects id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 424 | `CmdbService::tenantOf` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

### assertObjectRefsInCompany

*private static* - line 442

object_ref properties point at another CI, so they're a third way to link across companies — alongside parent/child and relationships. Every object_ref value being written must resolve to a CI in the same company as the CI being written.

`$ownTenant` is the STORED value (NULL = Default's).

```php
function assertObjectRefsInCompany(
    PDO $conn,
    ActorContext $ctx,
    int $classId,
    array $values,
    ?int $ownTenant
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$classId` | `int` |  | the CI's class, whose object_ref properties are checked |
| `$values` | `array` |  | [property_key => value] about to be written |
| `$ownTenant` | `?int` |  | the CI's company as stored - null for the Default company |

### loadObjectRow

*private static* - line 478

Loads one CI with its class name and key; throws not found when it does not exist.

```php
function loadObjectRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the cmdb_objects id to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 481 | `CmdbService::loadObjectRow` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

### classDefs

*private static* - line 506

Property definitions for a class, keyed by property_key, in the canonical TypedFields shape.

🔑 Resolving WHICH fields a thing has is the module's job, not the
engine's — the CMDB answers it from a class, where Assets answers it from
the field sets attached to a type plus any attached to the one asset.
Only the shape is shared.

The CMDB's `object_ref` is the engine's `ref` with a `cmdb_object` kind,
and `target_class_id` is that kind's target discriminator.

```php
function classDefs(PDO $conn, int $classId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$classId` | `int` |  | the cmdb_classes id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 509 | `CmdbService::classDefs` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

### valueSchema

*private static* - line 539

Where CMDB property values live, for TypedFields.

`self_kind` says which reference kind counts as "the same table as the
owner", so the no-self-reference rule fires for a CI pointing at itself
and NOT for, say, an asset whose linked user happens to share its id.

```php
function valueSchema(int $classId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$classId` | `int` |  | the CI's class, used only in the 'unknown property' hint |

### normaliseProperties

*private static* - line 570

Normalise the two accepted property shapes to a { property_key: value } map. The API's `properties` map passes through (unknown keys -> 422 later); the UI's `property_values` id-list is translated, dropping ids not in the class.

```php
function normaliseProperties(PDO $conn, int $classId, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$classId` | `int` |  | the CI's class, to translate property ids to keys |
| `$in` | `array` |  | the request fields; 'properties' (API, keyed by property_key) or 'property_values' (UI, [{property_id, value}]) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 577 | `CmdbService::normaliseProperties` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

### propertyOptionValues

*private static* - line 599

Dropdown option values for one property.

```php
function propertyOptionValues(PDO $conn, int $propertyId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$propertyId` | `int` |  | the cmdb_class_properties id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 601 | `CmdbService::propertyOptionValues` | read [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md) |  |

### writeProperties

*private static* - line 617

Validate + write property values (keyed by property_key). Unknown keys -> 422.

The typing, validation and storage rules live in TypedFields (see
includes/typed_fields.php); this decides only WHICH properties apply and
WHERE they are stored.

```php
function writeProperties(PDO $conn, int $objectId, int $classId, array $values): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `int` |  | the CI the values belong to |
| `$classId` | `int` |  | the CI's class |
| `$values` | `array` |  | [property_key => value] |

### checkRequired

*private static* - line 635

Required-property enforcement — create/update asymmetry.

```php
function checkRequired(PDO $conn, int $classId, array $values, bool $isCreate): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$classId` | `int` |  | the CI's class |
| `$values` | `array` |  | [property_key => value] being written |
| `$isCreate` | `bool` |  | true on create, when every required property must be present; on update only those sent are checked |

### validateParent

*private static* - line 646

Parent validation incl. the cycle walk.

```php
function validateParent(PDO $conn, ?int $objectId, ?int $parentId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `?int` |  | the CI being saved, or null when it is new (no cycle check) |
| `$parentId` | `?int` |  | the proposed parent, or null for none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 654 | `CmdbService::validateParent` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 666 | `CmdbService::validateParent` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

### descendantIds

*private static* - line 680

All descendant ids of an object (excluding itself), cycle-safe.

```php
function descendantIds(PDO $conn, int $rootId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rootId` | `int` |  | the CI whose children, grandchildren and so on are wanted |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 686 | `CmdbService::descendantIds` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

