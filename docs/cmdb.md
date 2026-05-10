# CMDB Module — Design & Roadmap

Configuration Management Database. Models the IT estate as a graph of typed objects with a strict containment hierarchy and a separate lightweight relationships layer.

This document captures the design decisions made during planning so future work can continue without re-litigating them. Anything not listed here is undecided.

---

## Core concepts

**Class** — the *type* of thing. e.g. Server, SQL Instance, Database, Stored Procedure, SQL Job. Defined by an analyst in a settings UI.

**Object** — an *instance* of a class. e.g. an object with class `Database` and name `FREEITSM`. Each object belongs to exactly one class.

**Property** — a named field on a class. All properties are user-defined per class. Property *types* include:
- Scalar values (text, number, date, boolean, dropdown)
- **Typed reference** — a property whose value is another object (e.g. a `Database` has an `Owner` property pointing at a `Person` object). 1:1 — a single linked object per property.

**Hierarchy (parent / child)** — strict tree. Each object has 0 or 1 parent and 0+ children. Parent semantics are **ontological dependency**: the parent is required for the child to exist. If you delete the parent, the children must go too (cascade delete).

Worked example:

```
Server (DBPROD01)
└── SQL Instance (MSSQLSERVER)
    └── Database (FREEITSM)
        └── Stored Procedure (sp_archive_tickets)
            └── SQL Job (Nightly archive)
```

**Relationship** — a named, many-to-many link between any two objects. User-defined verbs (e.g. *depends on*, *connects to*, *managed by*, *replicates to*). This sits *alongside* the hierarchy, not inside it. The hierarchy answers "what owns this"; relationships answer everything else.

**Why split hierarchy from relationships?** A single graph with no distinction becomes a hairball within ~20 objects. Forcing every object into one parent gives you a navigable tree; keeping all the M:N links in their own table keeps the picture readable.

---

## UX principles

The point of a CMDB is to *tell you something useful*, not just to record data. Most CMDBs in the wild become write-only — analysts dutifully enter records, then nobody opens the module again because every screen shows fields instead of answers. The patterns below are the v1 antidotes.

### 1. Lead with synthesis, not fields

The top of every object detail page is a **2-3 sentence AI-generated summary** stating what this thing is, where it sits, who owns it, and what depends on it. Structured properties go below. The summary is what tells you something useful in 5 seconds; the fields are for when you need specifics. Example:

> *Production FREEITSM database on MSSQLSERVER / DBPROD01, owned by IT Ops. 3 stored procedures and 2 SQL jobs depend on it, and it's referenced by 4 open tickets.*

Regenerated on demand (or cached and refreshed when properties/relationships change).

### 2. Impact panel, always visible

On every object: a panel showing what would be affected if this object were taken offline — its descendants in the hierarchy plus everything linked through inverse relationships. Pure graph traversal, no AI required for v1. (V2 layers a prose AI summary on top: *"Taking this offline would interrupt the nightly archive job and break the analyst dashboard's ticket counts widget."*)

### 3. Activity panel — cross-module visibility

On every object: open tickets touching this object, recent changes affecting it, related KB articles. This is what makes the CMDB feel woven into the rest of the product instead of inert.

### 4. Inline mini-graph

A small CSS-only visualisation on every object detail page showing parent above, this object centre, children below, related objects to the side. Gives a sense of place without leaving the page. Full force-directed graph viz is v2; this is the v1 stopgap.

### 5. Info card on every object reference

Anywhere the CMDB is mentioned elsewhere — in tickets, changes, search results, audit logs — render the reference as a card showing name + class + parent + owner, not just a text link. Context without clicking through.

### 6. Inline editing

Click a property value on the detail page to edit in place. No modal-per-edit friction. Save on blur or Enter.

### 7. Two browse modes

Tree view for navigating the hierarchy from a starting object; flat list view filtered by class for bulk inspection (sortable columns built from the class's properties). Same data, different lenses.

### 8. Empty states do work

When you create a new class, show AI-suggested properties inline (see V1 features). When you create your first object of a class, prompt for required fields with examples drawn from the class definition.

---

## V1 scope

### Data model (sketch — not final column types)

Every entity (classes, properties, objects, relationship types) gets an immutable auto-increment `id`. User-facing names and labels are editable freely; storage and references always go via the `id`. This is the safety net that makes renaming risk-free.

```
cmdb_classes
  id, class_key, name, description, icon, display_order, is_active
  -- class_key: immutable slug auto-generated from initial name; used by integrations
  --            and AI prompts. Editable only via a "rename key" advanced action.

cmdb_class_properties
  id, class_id, property_key, label, property_type,
  is_required, display_order
  -- property_key: immutable slug. Storage rows in cmdb_object_properties always
  --               reference id (not key), so even renaming the key is safe.
  -- label:        editable display name; renaming is free.
  -- property_type: text | number | date | boolean | dropdown | object_ref
  -- when object_ref: extra column referencing the target class_id

cmdb_class_property_options
  id, property_id, option_value, display_order
  -- only used by dropdown-type properties

cmdb_objects
  id, class_id, name, parent_id, created_datetime, updated_datetime
  -- parent_id is FK to cmdb_objects with ON DELETE CASCADE
  -- name is freely editable; integrations and references always use id.

cmdb_object_properties
  id, object_id, property_id, value_text, value_number,
  value_date, value_boolean, value_object_id
  -- only one value_* column populated per row, matching the property_type

cmdb_relationship_types
  id, verb, inverse_verb, description, is_active
  -- e.g. verb="depends on", inverse_verb="is depended on by"
  -- both verbs editable; cmdb_object_relationships stores the type id, not the verb.

cmdb_object_relationships
  id, from_object_id, to_object_id, relationship_type_id, created_datetime
```

AI settings are stored in the existing `system_settings` table under prefixed keys (`cmdb_ai_provider`, `cmdb_ai_api_key` (encrypted), `cmdb_ai_model`, `cmdb_ai_verify_ssl`) — same pattern as RFP AI / Knowledge AI / Forms AI / Reply Cleanup. No new settings tables needed.

### V1 features

**Settings page** with the following tabs (mirrors the layout of Tickets → Settings):
- **Classes** — CRUD for classes; shows class list with object counts
- **Properties** — managed inline on the Classes tab (per-class property editor)
- **Relationship types** — CRUD for verbs and inverse verbs
- **AI integration** — provider (Anthropic / OpenAI), API key (encrypted, masked), model dropdown, verify-SSL toggle, Test connection button. Separate key from RFP AI / Knowledge AI / Forms AI / Reply Cleanup so usage shows on its own line in the Anthropic billing console (matches the established per-feature key pattern). A Custom Instructions textarea is appended to the system prompt at runtime so analysts can shape AI output to their environment.

**Object management:**
- Objects CRUD with class picker, parent picker (must be a valid class — see open questions), and dynamic property form rendered from the class definition
- Inline editing on the object detail page (click a value, edit in place, save on blur)
- Required-property validation on save
- Relationships UI on each object's detail view — add/remove links with verb, grouped by direction (incoming / outgoing) and verb

**Browse and surface:**
- Object detail page laid out per the [UX principles](#ux-principles) above — AI summary header, quick facts row, properties, relationships, activity panel, impact panel, inline mini-graph
- Tree view: navigate the parent/child hierarchy from any object
- Flat list view per class: sortable, filterable table built dynamically from the class's property definitions
- Global search bar (text match against object names and key property values)
- **Info card** rendered everywhere a CMDB object is referenced (including from other modules in v2)

**V1 AI features** (all reusing the existing Anthropic streaming helper):
- **Object summary** at the top of every detail page — short prose synthesis of class, hierarchy, owner, and what depends on it
- **Suggest properties for this class** — when creating a new class (e.g. *Firewall*), the AI proposes 6-10 sensible properties with types. Analyst ticks the ones they want.
- **Suggest a relationship** — on the object detail view, an AI button scans the rest of the CMDB and proposes plausible relationships the analyst may have missed (e.g. "It looks like FREEITSM might also depend on the *Auth Service* — add this relationship?")

### V1 explicit non-goals

These are **deliberately out** of v1 — see V2 below.

- **Class inheritance.** Classes are flat. Five separate classes for the SQL chain example, with their own duplicated common fields where necessary. Pain of duplication is small at the start; complexity of inheritance is significant.
- **Discovery / auto-sync** from external sources (vCenter, InTune, network scans). All v1 objects are entered manually.
- **Force-directed graph visualisation.** V1 ships only the inline mini-graph and the tree view; full D3-style dependency-graph viz is v2.
- **Versioning / change history** of object properties.
- **Permissions / RBAC** beyond the existing module-level access control.
- **Natural-language search and impact Q&A in prose.** V1 AI is targeted assistance (summarise, suggest); free-form chat is v2.

### Design decisions that shape v1 (and protect v2)

- **Human-readable vocabulary from day one.** Labels, relationship verbs, and class names should read like natural language — `"depends on"` not `"DEP"`, `Database Owner` not `DBO`. The v1 AI features feed these strings straight into the prompt; obscure abbreviations would force a glossary layer later. Flag this in the settings UI ("Use plain English — this is what AI features see").
- **Typed reference properties exist in v1.** Object-to-object property links mean the AI sees a rich graph from day one, not a thin one.
- **Cascade delete on parent_id.** Because parent semantics are ontological dependency, the FK enforces it. No orphan handling logic needed.
- **AI features are first-class in v1, not bolted on later.** Building the AI summary, property suggestions, and relationship suggestions into v1 means the data model and UI are designed *with* AI in mind from the first commit — the alternative is retrofitting prompts onto a UX that wasn't built to surface them.

---

## V2 additive improvements

Each of these can be added later without breaking v1 data or schema. Listed roughly by likely value.

### 1. Deeper AI features

V1 already ships object summaries, property suggestions, and relationship suggestions. V2 adds the larger, more open-ended features that need the v1 base data and AI plumbing in place first:

- **Free-form chat panel** on the CMDB module — natural-language search and exploration ("Show me servers Bob owns that haven't been edited in 6 months", "Which databases are missing a backup_schedule property?").
- **Impact analysis Q&A in prose** — "If I take DBPROD01 down for 2 hours tonight, what's the blast radius?" The LLM walks the hierarchy and relationships graph and answers in plain English, naming affected services, owners to notify, and tickets/changes potentially impacted. (V1 already shows the raw impact panel; v2 prose-summarises it.)
- **Stale-data nudges** — proactive suggestions on the dashboard when an object hasn't been edited in N months, or when a class has objects with required-but-missing fields.
- **Bulk import / classification** — paste or upload a CSV; the AI proposes a mapping into existing classes and properties (with new class suggestions where nothing fits).

**Non-breaking** — pure additive. No schema changes; reuses the v1 AI settings and key.

### 2. Class inheritance

Add an optional `parent_class_id` to `cmdb_classes`. A child class inherits all properties of its parent and can add its own. Object queries gain an "include subclasses" toggle.

Migration path: take a flat v1 class with many properties (e.g. `Server`) and split it into `Server` (common props) + `Physical Server` / `VM` / `Cloud Instance` (specific props). Existing objects get reassigned to the most appropriate child class.

**Non-breaking** — `parent_class_id` defaults to NULL (no inheritance), and existing flat classes work unchanged.

### 3. Discovery / sync feeds

Pipe data from existing modules into the CMDB:

- vCenter VMs become `VM` objects automatically
- InTune devices become `Endpoint` objects
- Asset records become `Hardware` objects

Each feed needs a class mapping and a "source of truth" rule (manual edits vs sync overwrite). Likely a `cmdb_object_sources` table tracking which external system owns which property values.

**Non-breaking** — sync layer adds rows to existing tables.

### 4. Visualisation

Dependency graph view — D3 or vis.js force-directed graph showing an object plus everything it relates to (hierarchy + relationships) within N hops. Click a node to navigate.

**Non-breaking** — read-only view over existing data.

### 5. Versioning / change history

Audit log of property changes per object, like the existing ticket audit pattern.

**Non-breaking** — new `cmdb_object_audit` table.

### 6. CMDB-aware features in other modules

- Tickets: link a ticket to an affected CMDB object; view "open tickets affecting this object" on the object detail page.
- Change Management: scope a change to one or more CMDB objects; impact analysis (v2 AI) shows what else might be affected.
- Knowledge: link a KB article to a class or object ("Runbook for FREEITSM database").

**Non-breaking** — additive FKs in the consuming modules.

---

## Decisions locked

- **Immutable IDs everywhere.** Every entity (class, property, object, relationship type) has an immutable auto-increment `id`. All references — between rows, from other modules, from AI prompts — go via `id`. User-facing names and labels are freely editable.
- **Property key + label split.** `property_key` is an immutable slug auto-generated from the initial label; `label` is the editable display name. Same for `class_key` on classes.
- **No object-name uniqueness constraint.** Two databases can both be called `master` on different SQL instances. Can be tightened later if needed; relaxing the other way would require a dedup migration.
- **Relationships are symmetric via `inverse_verb`.** Creating "X depends on Y" auto-surfaces "Y is depended on by X" when viewing Y. No duplicate row needed.

## Open questions (cross when we come to them)

These can all be deferred to when they bite. None of them block starting v1.

1. **Parent picker scoping.** Should a child's parent be constrained to a specific class (a Database's parent must be a SQL Instance), or freeform? Adding the constraint later is additive (new `cmdb_class_allowed_parents` table). Risk of deferring: some users may build odd hierarchies that need cleanup later.

2. **Required-property defaults.** When marking an existing property `is_required`, what happens to existing objects that don't have a value? Block the change, prompt for a default, or leave them invalid until next edit? Pure UX decision, no schema impact.

3. **Module placement.** Top-level entry in the waffle menu (likely), sub-module under Asset Management, or standalone tab? Cosmetic — easy to move.
