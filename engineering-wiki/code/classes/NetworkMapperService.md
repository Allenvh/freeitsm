# NetworkMapperService

> Class in [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md), line 33

## Methods

| Method | What it does |
|---|---|
| [createDiagram()](#creatediagram) | Create a new diagram (v1 of a chain), optionally with contents. Returns the id. |
| [saveDiagram()](#savediagram) | Save a diagram: partial metadata; sending nodes/connectors replaces BOTH sets. Returns the id. |
| [deleteDiagram()](#deletediagram) | Delete a diagram version (leaf-only) or the whole chain. Returns ['id','versions_deleted']. |
| [createVersion()](#createversion) | Fork the leaf into a new version (clones nodes + connectors forward). Returns the new id. |
| [loadDiagram()](#loaddiagram) | Loads one diagram with a count of its newer versions; throws not found when it does not exist. |
| [requireLeaf()](#requireleaf) | Throws when the diagram is a historical version, which is read-only. |
| [chainIds()](#chainids) | The whole version chain containing `$diagramId`: root first. |
| [validatePaper()](#validatepaper) | paper_size / paper_orientation whitelists. |
| [brandingSlot()](#brandingslot) | Read a branding slot from in.branding.{band}.{slot}. NULL = inherit, '' = explicit blank. |
| [replaceContents()](#replacecontents) | Full contents replace (delete + reinsert; ref-based connector wiring, strict validation). |
| [validateNodeInput()](#validatenodeinput) | Validate one node input row. Returns [objectId, x\|null, y\|null, size, icon, ref]. |
| [autoPlacer()](#autoplacer) | A function that hands out positions for new nodes, in columns of eight to the right of what is already on the diagram. |
| [resolveRelationshipId()](#resolverelationshipid) | The CMDB relationship a connector stands for: null, 'auto' (any relationship between the two nodes' CIs) or a validated id. |
| [validateObjectExists()](#validateobjectexists) | Throws unless the CI exists and is in a company the actor can reach. |
| [validateSize()](#validatesize) | Validates a node size against NODE_SIZES. |
| [validateLineStyle()](#validatelinestyle) | Validates a connector line style against LINE_STYLES. |
| [validateIcon()](#validateicon) | Validates an icon key against cmdb_icons. |

### createDiagram

*public static* - line 47

Create a new diagram (v1 of a chain), optionally with contents. Returns the id.

```php
function createDiagram(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | title (required), description, version_label, paper_size, paper_orientation, branding, nodes, connectors |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `NetworkMapperService::createDiagram` | **insert** [network_diagrams](/code/tables/network_diagrams.md) (`parent_diagram_id`, `title`, `description`, `version_label`, `created_by_analyst_id`, `paper_size`, `paper_orientation`, `header_left`, `header_center`, `header_right`, `footer_left`, `footer_center`) |  |

**Called from:** [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md), [api/network-mapper/create_diagram.php](/code/files/api/network-mapper/create_diagram.php.md), [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md)

### saveDiagram

*public static* - line 98

Save a diagram: partial metadata; sending nodes/connectors replaces BOTH sets. Returns the id.

```php
function saveDiagram(PDO $conn, ActorContext $ctx, int $diagramId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the diagram (current version) to save |
| `$in` | `array` |  | any of title, description, version_label, paper_*, branding; nodes and/or connectors replace both sets |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 115 | `NetworkMapperService::saveDiagram` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`title`, `description`, `version_label`, `paper_size`, `paper_orientation`, `header_left`, `header_center`, `header_right`, `footer_left`, `footer_center`, `footer_right`, `updated_datetime`) |  |

**Called from:** [api/network-mapper/save_diagram.php](/code/files/api/network-mapper/save_diagram.php.md), [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md)

### deleteDiagram

*public static* - line 158

Delete a diagram version (leaf-only) or the whole chain. Returns ['id','versions_deleted'].

```php
function deleteDiagram(PDO $conn, ActorContext $ctx, int $diagramId, bool $chain = false): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the diagram version to delete |
| `$chain` | `bool` | `false` | true to delete every version, false for this (leaf) version only |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 169 | `NetworkMapperService::deleteDiagram` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 170 | `NetworkMapperService::deleteDiagram` | **delete** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 172 | `NetworkMapperService::deleteDiagram` | **delete** [network_diagrams](/code/tables/network_diagrams.md) |  |

**Called from:** [api/network-mapper/delete_diagram.php](/code/files/api/network-mapper/delete_diagram.php.md), [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md)

### createVersion

*public static* - line 189

Fork the leaf into a new version (clones nodes + connectors forward). Returns the new id.

```php
function createVersion(PDO $conn, ActorContext $ctx, int $parentId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$parentId` | `int` |  | the current (leaf) version to copy forward |
| `$in` | `array` |  | optional title, description and version_label for the new version; the rest is copied |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 198 | `NetworkMapperService::createVersion` | **insert** [network_diagrams](/code/tables/network_diagrams.md) (`parent_diagram_id`, `title`, `description`, `version_label`, `created_by_analyst_id`, `paper_size`, `paper_orientation`, `header_left`, `header_center`, `header_right`, `footer_left`, `footer_center`) |  |
| 221 | `NetworkMapperService::createVersion` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 224 | `NetworkMapperService::createVersion` | **insert** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) (`diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override`) |  |
| 230 | `NetworkMapperService::createVersion` | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 233 | `NetworkMapperService::createVersion` | **insert** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`diagram_id`, `from_node_id`, `to_node_id`, `cmdb_relationship_id`, `label`, `line_style`) |  |

**Called from:** [api/network-mapper/create_version.php](/code/files/api/network-mapper/create_version.php.md), [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md)

### loadDiagram

*private static* - line 260

Loads one diagram with a count of its newer versions; throws not found when it does not exist.

```php
function loadDiagram(PDO $conn, int $diagramId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the diagram to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 263 | `NetworkMapperService::loadDiagram` | read [network_diagrams](/code/tables/network_diagrams.md) |  |

### requireLeaf

*private static* - line 280

Throws when the diagram is a historical version, which is read-only.

```php
function requireLeaf(array $diagram): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$diagram` | `array` |  | the diagram row from `loadDiagram()`, with child_count |

### chainIds

*private static* - line 292

The whole version chain containing `$diagramId`: root first.

```php
function chainIds(PDO $conn, int $diagramId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | any version in the chain |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 296 | `NetworkMapperService::chainIds` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 307 | `NetworkMapperService::chainIds` | read [network_diagrams](/code/tables/network_diagrams.md) |  |

### validatePaper

*private static* - line 322

paper_size / paper_orientation whitelists.

```php
function validatePaper(array $in, ?array $current): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$in` | `array` |  | the request fields; paper_size and paper_orientation, '' or null meaning none |
| `$current` | `?array` |  | the stored diagram for keys not sent, or null on create |

### brandingSlot

*private static* - line 348

Read a branding slot from in.branding.{band}.{slot}. NULL = inherit, '' = explicit blank.

```php
function brandingSlot(array $in, string $band, string $slot, ?string $current): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$in` | `array` |  | the request fields; the value is read from branding.<band>.<slot> |
| `$band` | `string` |  | 'header' or 'footer' |
| `$slot` | `string` |  | 'left', 'center' or 'right' |
| `$current` | `?string` |  | the stored value, kept when the slot is not sent |

### replaceContents

*private static* - line 366

Full contents replace (delete + reinsert; ref-based connector wiring, strict validation).

```php
function replaceContents(
    PDO $conn,
    ActorContext $ctx,
    int $diagramId,
    array $nodesIn,
    array $connectorsIn
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the diagram whose nodes and connectors are replaced |
| `$nodesIn` | `array` |  | [{cmdb_object_id, x?, y?, size?, icon_override?, ref?}] - nodes with no x/y are placed automatically |
| `$connectorsIn` | `array` |  | [{from_ref \| from_object_id, to_ref \| to_object_id, cmdb_relationship_id?, label?, line_style?}] |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 368 | `NetworkMapperService::replaceContents` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 369 | `NetworkMapperService::replaceContents` | **delete** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 375 | `NetworkMapperService::replaceContents` | **insert** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) (`diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override`) |  |
| 399 | `NetworkMapperService::replaceContents` | **insert** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`diagram_id`, `from_node_id`, `to_node_id`, `cmdb_relationship_id`, `label`, `line_style`) |  |

### validateNodeInput

*private static* - line 446

Validate one node input row. Returns [objectId, x|null, y|null, size, icon, ref].

```php
function validateNodeInput(PDO $conn, ActorContext $ctx, array $n, int $index): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$n` | `array` |  | one node from the request |
| `$index` | `int` |  | its position in the nodes list, for error messages |

### autoPlacer

*private static* - line 476

A function that hands out positions for new nodes, in columns of eight to the right of what is already on the diagram.

```php
function autoPlacer(PDO $conn, int $diagramId): callable
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the diagram whose existing nodes the new ones go to the right of |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 478 | `NetworkMapperService::autoPlacer` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |

### resolveRelationshipId

*private static* - line 499

The CMDB relationship a connector stands for: null, 'auto' (any relationship between the two nodes' CIs) or a validated id.

```php
function resolveRelationshipId(PDO $conn, $raw, int $fromNodeId, int $toNodeId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$raw` |  |  | the connector's cmdb_relationship_id: null or '' for none, 'auto' to find one, or an id |
| `$fromNodeId` | `int` |  | the connector's start node (network_diagram_nodes.id) |
| `$toNodeId` | `int` |  | the connector's end node |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 506 | `NetworkMapperService::resolveRelationshipId` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 519 | `NetworkMapperService::resolveRelationshipId` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |

### validateObjectExists

*private static* - line 532

Throws unless the CI exists and is in a company the actor can reach.

```php
function validateObjectExists(PDO $conn, ActorContext $ctx, int $objectId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `int` |  | the CI a node places |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 534 | `NetworkMapperService::validateObjectExists` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

### validateSize

*private static* - line 558

Validates a node size against NODE_SIZES.

```php
function validateSize(string $size): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$size` | `string` |  | a NODE_SIZES key: 'small', 'medium' or 'large' |

### validateLineStyle

*private static* - line 571

Validates a connector line style against LINE_STYLES.

```php
function validateLineStyle(string $style): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$style` | `string` |  | 'solid' or 'dashed' |

### validateIcon

*private static* - line 584

Validates an icon key against cmdb_icons.

```php
function validateIcon(PDO $conn, string $iconKey): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$iconKey` | `string` |  | a cmdb_icons icon_key |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 586 | `NetworkMapperService::validateIcon` | read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

