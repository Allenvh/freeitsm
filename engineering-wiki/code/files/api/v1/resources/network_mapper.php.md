# api/v1/resources/network_mapper.php

> API endpoint - 1022 lines

FreeITSM REST API v1 — network-mapper resource (diagrams, nodes, connectors, versions, suggestions).

Built for programmatic diagram maintenance — the target consumer is a
discovery tool or AI agent keeping network documentation alive, so the
surface is deliberately richer than the module's internal endpoints:

  - reads are fully hydrated for machine understanding: every node carries
    its CMDB object's name/class/effective icon/planned flag (and, with
    ?include_properties=true, the object's full typed property values);
    connectors carry both endpoints' object ids/names and the CMDB
    relationship verb they represent; a computed layout block gives the
    canvas bounding box and the pixel size of each node size class;
  - writes come in BOTH granularities: incremental node/connector
    operations with STABLE ids (the UI editor can only full-replace, which
    regenerates every id on save), and a full-replace mode on PATCH for
    wholesale redraws;
  - connectors can be addressed by *object* ids, not just node ids, and
    `cmdb_relationship_id: "auto"` binds the connector to the existing
    CMDB relationship between the two objects — an agent thinks in CIs,
    the API translates;
  - GET .../suggestions is the "what's missing" tool: CMDB neighbours
    (relationships in both directions + object_ref property links) of
    on-diagram objects that aren't drawn yet.

Versioning parity with the module: a linear chain via parent_diagram_id;
the LEAF (no children) is the only editable version, ancestors are frozen
history (writes to them are a 409). POST .../versions clones the leaf
forward exactly like the editor's "New version". DELETE is leaf-only
(deleting the leaf resurfaces its parent as current) or ?chain=true for
the whole chain — stricter than the UI, which lets a mid-chain delete
corrupt the version chain.

Deliberate improvements over the internal endpoints, documented:
  - unknown cmdb_object_id / relationship ids / sizes / line styles /
    icon keys are a 422 (save_diagram.php silently SKIPS bad nodes and
    connectors — machines deserve the error);
  - adding an object already on the diagram is a 409 unless
    allow_duplicate=true (the editor allows silent duplicates).

MULTI-TENANCY: the network tables themselves are install-wide (no tenant_id)
— but the CMDB objects they reference are NOT, as of the CMDB slice. So a key
may only place a CI it can reach onto a diagram (apiNmValidateObjectExists),
mirroring NetworkMapperService. Node/connector READS deliberately do not
filter by the CI's company, matching api/network-mapper/get_diagram.php:
diagrams have no company of their own, so filtering would make CIs silently
vanish from existing diagrams. Both routes onto a canvas are scoped, so the
residual is historical data only. Giving diagrams a company is the first job
of Network Mapper's own multi-tenancy slice.
No audit trail exists for diagrams and none was invented.

Depends on cmdb.php (apiCmdbClassDefs) — index.php requires it earlier.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/network_mapper.php` |
| Protected by | REST API key |
| Reads | `GET all_versions`, `GET q`, `GET contains_object_id`, `GET created_by`, `GET updated_since`, `GET include_properties`, `GET chain`, `GET object_id`, `GET limit` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiNmDiagramSelect()](/code/functions/apiNmDiagramSelect.md) | 74 | The SELECT for diagrams, with the author's name and counts of child versions, nodes and connectors - child_count 0 marks the current version. |
| [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md) | 88 | Loads one diagram row (apiNmDiagramSelect), or answers 404 and stops. |
| [apiNmRequireLeaf()](/code/functions/apiNmRequireLeaf.md) | 103 | Writes are leaf-only — a version with children is frozen history. |
| [apiNmLoadNode()](/code/functions/apiNmLoadNode.md) | 115 | Loads one node row that belongs to the given diagram, or answers 404 and stops. |
| [apiNmLoadConnector()](/code/functions/apiNmLoadConnector.md) | 131 | Loads one connector row that belongs to the given diagram, or answers 404 and stops. |
| [apiNmChainIds()](/code/functions/apiNmChainIds.md) | 146 | The whole version chain containing `$diagramId`: root first. Caps mirror the UI (200 up / 500 total). |
| [apiSerializeNmDiagram()](/code/functions/apiSerializeNmDiagram.md) | 180 | Shapes a diagram row for the API - ids, title, version, is_current, author, dates and counts, plus paper and header/footer branding when `$full`. |
| [apiNmNodeSelect()](/code/functions/apiNmNodeSelect.md) | 210 | The SELECT for diagram nodes, joined to their CMDB object, its class and the class icon. |
| [apiSerializeNmNode()](/code/functions/apiSerializeNmNode.md) | 225 | Shapes a node row for the API - position, size (and its pixel size), the icon that actually renders, and its CMDB object with class. |
| [apiNmConnectorsForDiagram()](/code/functions/apiNmConnectorsForDiagram.md) | 248 | Connectors with endpoint objects + the CMDB relationship verb they represent. |
| [apiSerializeNmConnector()](/code/functions/apiSerializeNmConnector.md) | 273 | Shapes a connector row for the API - both endpoints (node and object), the CMDB relationship and its verbs when bound to one, label and line style. |
| [apiNmConnectorHydrated()](/code/functions/apiNmConnectorHydrated.md) | 302 | Load one connector by id fully hydrated (for single-op responses). |
| [apiNmValidateObjectExists()](/code/functions/apiNmValidateObjectExists.md) | 321 | Answers 422 and stops unless the CMDB object exists and, when a key is given, belongs to a company the key can reach - the same message either way, so another company's object is never revealed. |
| [apiNmValidateSize()](/code/functions/apiNmValidateSize.md) | 343 | Returns the node size when it is a known size class, otherwise answers 422 listing the valid ones. |
| [apiNmValidateLineStyle()](/code/functions/apiNmValidateLineStyle.md) | 356 | Returns the connector line style when it is a known one, otherwise answers 422 listing the valid ones. |
| [apiNmValidateIcon()](/code/functions/apiNmValidateIcon.md) | 368 | Returns the icon key when it is in cmdb_icons, otherwise answers 422 pointing at GET /cmdb-icons. |
| [apiNmResolveEndpoint()](/code/functions/apiNmResolveEndpoint.md) | 385 | Resolve a connector endpoint to a node id on this diagram. Accepts {prefix}_node_id or {prefix}_object_id (422 if the object isn't on the diagram, or is on it more than once — use node ids to disambiguate). |
| [apiNmResolveRelationshipId()](/code/functions/apiNmResolveRelationshipId.md) | 416 | Resolve cmdb_relationship_id input: null clears, an int is validated, 'auto' finds the existing CMDB relationship between the two objects (either direction; null if none). |
| [apiNmValidateNodeInput()](/code/functions/apiNmValidateNodeInput.md) | 452 | Validate + normalise one node input row. Returns [cmdb_object_id, x\|null, y\|null, size, icon_override, ref] — null x/y means auto-place. |
| [apiNmAutoPlacer()](/code/functions/apiNmAutoPlacer.md) | 482 | Auto-placement for nodes sent without coordinates: a fresh column to the right of the current bounding box, stacking downward — legible enough for an agent drop; a human tidies the layout in the editor. |
| [apiNmDiagramsList()](/code/functions/apiNmDiagramsList.md) | 501 | GET /network-diagrams — current versions by default; ?all_versions=true for everything. |
| [apiNmDiagramsGet()](/code/functions/apiNmDiagramsGet.md) | 550 | GET /network-diagrams/{id} — fully hydrated; ?include_properties=true adds each object's CI properties. |
| [apiNmObjectProperties()](/code/functions/apiNmObjectProperties.md) | 596 | Typed property values for the distinct objects behind a diagram's nodes, keyed by object id. |
| [apiNmDiagramsCreate()](/code/functions/apiNmDiagramsCreate.md) | 639 | POST /network-diagrams — create, optionally with initial contents in one call. |
| [apiNmRespondFull()](/code/functions/apiNmRespondFull.md) | 653 | Respond with the full hydrated diagram (shared by create/update/version). |
| [apiNmDiagramsUpdate()](/code/functions/apiNmDiagramsUpdate.md) | 663 | PATCH /network-diagrams/{id} — partial metadata; sending nodes/connectors = full contents replace. |
| [apiNmDiagramsDelete()](/code/functions/apiNmDiagramsDelete.md) | 671 | DELETE /network-diagrams/{id} — leaf-only (parent resurfaces as current); ?chain=true deletes the whole chain. |
| [apiNmVersionsList()](/code/functions/apiNmVersionsList.md) | 684 | GET /network-diagrams/{id}/versions — the whole chain, oldest first. |
| [apiNmVersionsCreate()](/code/functions/apiNmVersionsCreate.md) | 696 | POST /network-diagrams/{id}/versions — clone the leaf forward (the editor's "New version"). |
| [apiNmSuggestionsList()](/code/functions/apiNmSuggestionsList.md) | 708 | GET /network-diagrams/{id}/suggestions — CMDB neighbours of on-diagram objects not yet drawn. |
| [apiNmNodesCreate()](/code/functions/apiNmNodesCreate.md) | 800 | POST /network-diagrams/{id}/nodes — add one node (object) or a batch ({"nodes": [...]}). |
| [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md) | 862 | PATCH /network-diagrams/{id}/nodes/{node_id} — move / resize / re-icon. |
| [apiNmNodesDelete()](/code/functions/apiNmNodesDelete.md) | 896 | DELETE /network-diagrams/{id}/nodes/{node_id} — the node and its connectors. |
| [apiNmConnectorsCreate()](/code/functions/apiNmConnectorsCreate.md) | 923 | POST /network-diagrams/{id}/connectors — endpoints by node id or object id. |
| [apiNmConnectorsUpdate()](/code/functions/apiNmConnectorsUpdate.md) | 959 | PATCH /network-diagrams/{id}/connectors/{connector_id} — label / style / provenance. |
| [apiNmConnectorsDelete()](/code/functions/apiNmConnectorsDelete.md) | 992 | DELETE /network-diagrams/{id}/connectors/{connector_id} |
| [apiCmdbIconsList()](/code/functions/apiCmdbIconsList.md) | 1010 | GET /cmdb-icons - the icon catalogue (key, label, active), the valid values for a node's icon_override. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 | `apiNmDiagramSelect` | read [network_diagrams](/code/tables/network_diagrams.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [network_diagram_connectors](/code/tables/network_diagram_connectors.md), read [analysts](/code/tables/analysts.md) |  |
| 116 | `apiNmLoadNode` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 132 | `apiNmLoadConnector` | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 149 | `apiNmChainIds` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 160 | `apiNmChainIds` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 211 | `apiNmNodeSelect` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 250 | `apiNmConnectorsForDiagram` | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 322 | `apiNmValidateObjectExists` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 369 | `apiNmValidateIcon` | read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 393 | `apiNmResolveEndpoint` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 422 | `apiNmResolveRelationshipId` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 435 | `apiNmResolveRelationshipId` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 483 | `apiNmAutoPlacer` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 505 | `apiNmDiagramsList` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 514 | `apiNmDiagramsList` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 529 | `apiNmDiagramsList` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 605 | `apiNmObjectProperties` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 710 | `apiNmSuggestionsList` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 733 | `apiNmSuggestionsList` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 775 | `apiNmSuggestionsList` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 820 | `apiNmNodesCreate` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 833 | `apiNmNodesCreate` | **insert** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) (`diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override`) |  |
| 845 | `apiNmNodesCreate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |
| 880 | `apiNmNodesUpdate` | **update** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) (`x`, `y`, `size`, `icon_override`) |  |
| 888 | `apiNmNodesUpdate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |
| 903 | `apiNmNodesDelete` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 906 | `apiNmNodesDelete` | **delete** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 907 | `apiNmNodesDelete` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |
| 934 | `apiNmConnectorsCreate` | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 946 | `apiNmConnectorsCreate` | **insert** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`diagram_id`, `from_node_id`, `to_node_id`, `cmdb_relationship_id`, `label`, `line_style`) |  |
| 953 | `apiNmConnectorsCreate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |
| 975 | `apiNmConnectorsUpdate` | **update** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`cmdb_relationship_id`, `label`, `line_style`) |  |
| 986 | `apiNmConnectorsUpdate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |
| 997 | `apiNmConnectorsDelete` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 998 | `apiNmConnectorsDelete` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |
| 1012 | `apiCmdbIconsList` | read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiCmdbClassDefs()](/code/functions/apiCmdbClassDefs.md), [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md)

