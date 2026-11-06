# CMDB Module — Design & Roadmap

Configuration Management Database. Models the IT estate as a graph of typed objects with a strict containment hierarchy and a separate lightweight relationships layer.

This document captures the design decisions made during planning so future work can continue without re-litigating them. Anything not listed here is undecided.

⚠️ **This is a design doc, not a status report.** It was written before the module was built and describes intent. Items are tagged where reality has since diverged:

- ✅ **shipped** — built and in the product
- ⬜ **not built** — described in V1 scope below, never implemented
- 🔀 **changed** — built differently to the sketch here

For what the module actually does today, read the [CMDB wiki page](https://github.com/edmozley/freeitsm/wiki/CMDB) and the three developer guides ([foundations](https://github.com/edmozley/freeitsm/wiki/CMDB-Developer-Guide), [impact analysis](https://github.com/edmozley/freeitsm/wiki/CMDB-Impact-Analysis-Developer-Guide), [data quality](https://github.com/edmozley/freeitsm/wiki/CMDB-Data-Quality-Developer-Guide)), which are kept current with the code.

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

**Update — transitive blast radius.** The panel originally stopped one hop out, which answers "what is attached to this?" but not "what actually breaks?". It now leads with a **blast radius** grouped by hop distance, so a server failure can be traced through its VM and its application to the customer-facing service. The three original buckets remain below it as the direct-connection detail, and their shape is unchanged because `GET /cmdb/objects/{id}/impact` publishes it.

Two rules make this safe rather than noisy:

- **Not every edge carries impact.** Following everything would report that a server failure affects the building it sits in. `cmdb_relationship_types.impact_direction` says whether a failure travels along a relationship and which way (`none` / `to_from` — *"A depends on B"*, B breaking affects A / `from_to` — *"A hosts B"*, A breaking affects B). Object-reference properties opt in separately via `cmdb_class_properties.spreads_impact`, because plenty of estates record a dependency as a field rather than a relationship. Containment always carries impact, since parent semantics here are ontological dependency.
- **Everything defaults to "does not spread"**, so an upgraded install reports exactly what it did before until someone configures it. The panel distinguishes "nothing depends on this" from "nothing is configured to spread impact yet" — those look identical otherwise and call for opposite responses.

The walk is breadth-first (so a hop count is the *shortest* path, not an artefact of traversal order), bounded by node and depth caps that surface as a `truncated` flag rather than silently capping, and scoped to the root object's company rather than trusting the no-cross-company-links invariant. It lives in `includes/cmdb_impact.php` — the internal endpoint and the REST API had each grown their own copy of the descendants walk, and both now share one.

The panel itself ships as a grouped list. ✅ **Open as diagram** (#973) then hands that blast radius to the **Network Mapper** — `api/cmdb/create_impact_diagram.php` lays the affected set out in rings by hop distance and creates a real diagram through `NetworkMapperService::createDiagram()`. That deliberately settles the "graph visualisation" item in V2 §4 below by *handing off to the renderer that already exists* rather than building a second one inside this module.

### 3. Activity panel — cross-module visibility

On every object: open tickets touching this object, recent changes affecting it, related KB articles. This is what makes the CMDB feel woven into the rest of the product instead of inert.

🔀 **Tickets only, so far.** The panel ships with live open tickets plus recent closed ones (capped at 20, total shown), each deep-linking into the tickets module, and the ticket counts feed the AI summary prompt. ⬜ Changes and KB articles are not wired in — see V2 §6.

### 4. Inline mini-graph

A small CSS-only visualisation on every object detail page showing parent above, this object centre, children below, related objects to the side. Gives a sense of place without leaving the page. Full force-directed graph viz is v2; this is the v1 stopgap.

### 5. Info card on every object reference

Anywhere the CMDB is mentioned elsewhere — in tickets, changes, search results, audit logs — render the reference as a card showing name + class + parent + owner, not just a text link. Context without clicking through.

🔀 **Tickets only.** The reading pane's *Affected CMDB Objects* section renders linked CIs as cards with class + parent context. Changes and audit logs still have no CMDB reference to render.

### 6. Inline editing

Click a property value on the detail page to edit in place. No modal-per-edit friction. Save on blur or Enter.

### 7. Two browse modes

Tree view for navigating the hierarchy from a starting object; flat list view filtered by class for bulk inspection (sortable columns built from the class's properties). Same data, different lenses.

⬜ **Only the flat list was built.** `cmdb/index.php` ships a class sidebar with per-class counts and a filterable object table showing name, parent, child count and last updated. There is no tree view. Hierarchy navigation happens instead on the object detail page — the parent breadcrumb, the children cards and the inline mini-graph — which turned out to cover most of the need, so the tree has never been missed enough to build.

### 8. Empty states do work

When you create a new class, show AI-suggested properties inline (see V1 features). When you create your first object of a class, prompt for required fields with examples drawn from the class definition.

---

## V1 scope

### Data model (sketch — not final column types)

🔀 **This sketch is the original plan and has drifted.** It is kept because the *reasoning* under each comment still holds, but the shipped schema has since gained `cmdb_icons` (an 8th table), `cmdb_objects.is_planned` / `ai_summary` / `ai_summary_generated_at` / `tenant_id`, `cmdb_class_properties.spreads_impact` and `target_class_id`, `cmdb_relationship_types.impact_direction`, a colour on `cmdb_class_property_options`, and the `ticket_cmdb_objects` link table. `database/freeitsm.sql` is the authority; the [foundations guide](https://github.com/edmozley/freeitsm/wiki/CMDB-Developer-Guide) §3 explains what each addition is for.

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
- **AI integration** — provider (🔀 Anthropic / OpenAI / **OpenRouter**, via the shared AI Providers panel), API key (encrypted, masked), model dropdown, Test connection button. Separate key from RFP AI / Knowledge AI / Forms AI / Reply Cleanup so usage shows on its own line in the provider's billing console (matches the established per-feature key pattern). A Custom Instructions textarea is appended to the system prompt at runtime so analysts can shape AI output to their environment.

**Object management:**
- Objects CRUD with class picker, parent picker (must be a valid class — see open questions), and dynamic property form rendered from the class definition
- Inline editing on the object detail page (click a value, edit in place, save on blur)
- Required-property validation on save
- Relationships UI on each object's detail view — add/remove links with verb, grouped by direction (incoming / outgoing) and verb

**Browse and surface:**
- ✅ Object detail page laid out per the [UX principles](#ux-principles) above — AI summary header, quick facts row, properties, relationships, activity panel, impact panel, inline mini-graph
- ⬜ Tree view: navigate the parent/child hierarchy from any object — **not built**, see UX principle 7
- ✅ Flat list view per class: filterable table built dynamically from the class's property definitions
- ✅ Global search bar (text match against object names and key property values)
- 🔀 **Info card** rendered everywhere a CMDB object is referenced — tickets only, see UX principle 5

**V1 AI features** (all reusing the shared provider layer — see [AI Providers](https://github.com/edmozley/freeitsm/wiki/AI-Providers); Anthropic / OpenAI / OpenRouter):
- ✅ **Object summary** at the top of every detail page — short prose synthesis of class, hierarchy, owner, and what depends on it. Cached on `cmdb_objects.ai_summary` so page views cost nothing.
- ✅ **Suggest properties for this class** — the AI proposes sensible properties with types and the analyst ticks the ones they want. 🔀 Built as a **two-stage wizard**: it asks 3-5 clarifying questions about the environment first (`ai_suggest_questions.php`), then proposes 6-12 properties with a one-line rationale each (`ai_suggest_properties.php`). It also auto-creates a missing target class when it suggests an `object_ref`.
- ⬜ **Suggest a relationship** — on the object detail view, an AI button scans the rest of the CMDB and proposes plausible relationships the analyst may have missed (e.g. "It looks like FREEITSM might also depend on the *Auth Service* — add this relationship?"). **Never built.** There is no such endpoint. It remains a good idea and the plumbing is all in place, but it is a candidate for future work rather than something the module does.

### V1 explicit non-goals

These are **deliberately out** of v1 — see V2 below.

- **Class inheritance.** Classes are flat. Five separate classes for the SQL chain example, with their own duplicated common fields where necessary. Pain of duplication is small at the start; complexity of inheritance is significant.
- **Discovery / auto-sync** from external sources (vCenter, InTune, network scans). All v1 objects are entered manually.
- **Force-directed graph visualisation.** V1 ships only the inline mini-graph (the tree view was never built either). 🔀 The v2 item was ultimately answered by handing off to the Network Mapper rather than adding a graph library — see V2 §4.
- **Versioning / change history** of object properties.
- ~~**Permissions / RBAC** beyond the existing module-level access control.~~ 🔀 **This changed.** The module now has four capabilities in `includes/capabilities.php` — `cmdb.manage` (umbrella), `cmdb.classes` (the CI schema), `cmdb.relationship_types` (the verb library) and `cmdb.ai` (provider + key) — on top of the `requireModuleAccessJson('cmdb')` check every endpoint still makes. Settings tabs are filtered through `settingsManifestFor('cmdb')` so a tab an analyst may not use is never rendered. Object-level permissions remain out of scope.
- **Natural-language search and impact Q&A in prose.** V1 AI is targeted assistance (summarise, suggest); free-form chat is v2.

### Design decisions that shape v1 (and protect v2)

- **Human-readable vocabulary from day one.** Labels, relationship verbs, and class names should read like natural language — `"depends on"` not `"DEP"`, `Database Owner` not `DBO`. The v1 AI features feed these strings straight into the prompt; obscure abbreviations would force a glossary layer later. Flag this in the settings UI ("Use plain English — this is what AI features see").
- **Typed reference properties exist in v1.** Object-to-object property links mean the AI sees a rich graph from day one, not a thin one.
- **Cascade delete on parent_id.** Because parent semantics are ontological dependency, deleting a parent must take the whole descendant subtree. 🔀 ⚠️ **But the FK does not enforce it, and code must not assume it does.** `freeitsm.sql` declares the cascade, yet installs grown through Database Verification have **no CMDB foreign keys at all** — `db_verify_schema.php` generates columns and primary keys, not FKs. `CmdbService::deleteObject()` therefore walks the subtree and cleans up by hand: nulls inbound `object_ref` values, deletes properties, removes Network Mapper nodes and connectors, deletes relationships both directions, removes ticket links, then deletes the objects. Any cleanup that trusts the cascade works on a fresh install and silently leaves orphans on an upgraded one.
- **AI features are first-class in v1, not bolted on later.** Building the AI summary, property suggestions, and relationship suggestions into v1 means the data model and UI are designed *with* AI in mind from the first commit — the alternative is retrofitting prompts onto a UX that wasn't built to surface them.

---

## V2 additive improvements

Each of these can be added later without breaking v1 data or schema. Listed roughly by likely value.

### 1. Deeper AI features

V1 already ships object summaries, property suggestions, and relationship suggestions. V2 adds the larger, more open-ended features that need the v1 base data and AI plumbing in place first:

- **Free-form chat panel** on the CMDB module — natural-language search and exploration ("Show me servers Bob owns that haven't been edited in 6 months", "Which databases are missing a backup_schedule property?").
- **Impact analysis Q&A in prose** — "If I take DBPROD01 down for 2 hours tonight, what's the blast radius?" The LLM walks the hierarchy and relationships graph and answers in plain English, naming affected services, owners to notify, and tickets/changes potentially impacted. (V1 already shows the raw impact panel; v2 prose-summarises it.) **The graph walk this needs now exists** — `cmdbBlastRadius()` returns the affected set with hop counts and how each was reached, so this becomes a prompt change rather than a new traversal.
- **Stale-data nudges** — proactive suggestions on the dashboard when an object hasn't been edited in N months, or when a class has objects with required-but-missing fields. 🔀 **Both of those exact checks shipped as #972**, in the [Data Quality audit](https://github.com/edmozley/freeitsm/wiki/CMDB-Data-Quality-Developer-Guide) (`cmdb/audit.php`) — *not touched in a while* and *required fields left empty*, alongside four others. What is still missing is the **proactive** half: the audit is a page you choose to open, not a nudge that finds you. No AI is involved either, deliberately — every check is derived from a declaration the install already made.
- ⬜ **Bulk import / classification** — paste or upload a CSV; the AI proposes a mapping into existing classes and properties (with new class suggestions where nothing fits). Still the single biggest adoption blocker: there is no CSV import at all, only the demo-data importer.

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

🔀 **Answered differently, and better.** No charting library was added. **Network Mapper** already draws, saves, versions and exports diagrams whose nodes are bound to real `cmdb_objects` rows, so the CMDB hands off to it instead of growing a second renderer:

- **Open as diagram** on the impact panel (#973) builds a Network Mapper diagram from the blast radius — root at the centre, one ring per hop, connectors along the path the engine actually took, solid for relationships and dashed for containment and property links.
- Network Mapper's own *add related objects* flow walks the same three buckets for freehand exploration.

The difference from the sketch is that the result is a **durable artefact** you can rearrange, annotate and attach to a change or an incident review, rather than a view that evaporates on navigation. ⬜ An ephemeral in-page force-directed graph is still not built, and there is now little reason to.

### 5. Versioning / change history

Audit log of property changes per object, like the existing ticket audit pattern.

**Non-breaking** — new `cmdb_object_audit` table.

### 6. CMDB-aware features in other modules

- ✅ Tickets: link a ticket to an affected CMDB object; view "open tickets affecting this object" on the object detail page. **Shipped** via the `ticket_cmdb_objects` table — an *Affected CMDB Objects* search-and-link section in the ticket reading pane, and a reciprocal Activity panel on the object. Both ends are company-checked. The ticket counts also feed the AI summary prompt, which is what turns the CMDB from a static inventory into a live operational map.
- ⬜ Change Management: scope a change to one or more CMDB objects; impact analysis shows what else might be affected. **The engine is now the missing half** — `cmdbBlastRadius()` returns the affected set with hop counts, so this is wiring rather than new traversal.
- ⬜ Knowledge: link a KB article to a class or object ("Runbook for FREEITSM database").

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

3. ~~**Module placement.**~~ ✅ **Settled** — CMDB is a top-level entry in the waffle menu, with its own `cmdb/` folder and module header.

### Questions the build has since raised

4. **How does an estate get *into* the CMDB?** Everything today is typed in by hand. The two candidates are a **CSV import** (V2 §1) and **promoting existing Assets** — the Assets module already has fresh, automatically-fed records for much of the same hardware, while a hand-maintained CMDB rots. They are not mutually exclusive but they imply very different models of where truth lives. Undecided, and the biggest open question in the module.

5. **Does a CI ever move between companies?** Deliberately not possible today: a CI sits in a tree with links hanging off it, so a move must decide the fate of its parent, children and every relationship, and either default is wrong. A misfiled CI is deleted and recreated. See the multi-tenancy progress tracker for the shape this would take.
