# cmdb/object.js

> JavaScript - 1343 lines

CMDB Object Detail

Renders one configuration item and everything attached to it, and provides
inline editing of the lot: name, parent, planned state, every property type,
relationships in both directions, and the property DEFINITION modal.

Layout notes worth knowing before changing anything here are in the header
of object.php — in particular why Impact / Map / Hierarchy / Relationships
are one panel, and why its tally counts by KIND rather than by direction.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `esc(s)` | 53 | Escapes text for safe use in HTML. |
| `toast(msg, isError)` | 61 | showToast and showConfirm are the shared components, pulled in by includes/header.php -> waffle-menu.php. Not redefined here — a second toast implementation one module away from the real one is exactly the divergence worth avoiding. |
| `confirmAction(opts)` | 65 | Asks for confirmation through the shared showConfirm dialogue; resolves true when confirmed. |
| `postJson(url, body)` | 70 | Posts a JSON body and returns the parsed JSON reply. |
| `fmtWhen(s)` | 84 | Was a top-level `function fmtDateTime` that called itself - it shadowed tz.js's global (#1195) - until the stack overflowed, and then showed the raw UTC string. A different name, so the shared formatter is the one called. |
| `dateOnly(v)` | 91 | "2026-04-15 00:00:00" -> "2026-04-15". Never parsed as a Date: a date property is naive, and round-tripping it through a timezone shifts it. |
| `daysSince(sqlUtc)` | 97 | Whole days since a UTC timestamp, or null when there is none or it cannot be read. |
| `icon(key, size)` | 106 | Returns the SVG for an icon key from the Network Mapper icon set ('box' when none), or '' when that set is not loaded. |
| `iconForClassName(name, size)` | 110 | Returns the icon for a class by name, for related objects that only carry the class name. |
| `iconForClassId(id, size)` | 114 | Returns the icon for a class by id. |
| `loadRelationshipTypes()` | 135 | Loads the active relationship verbs for the add-relationship modal. |
| `reloadAndRender()` | 145 | Everything that reloads after a write goes through here, so no caller can refresh the object but forget the impact panel and leave the blast radius and the connection tally disagreeing. |
| `loadObject()` | 151 | Loads the object (properties, relationships, parent) from get_object.php; on failure the page shows the error instead. |
| `loadImpact()` | 163 | Loads the impact data (descendants, references) and the blast radius from get_object_impact.php. |
| `loadActivity()` | 170 | Loads the object's open and recently closed tickets from get_object_tickets.php. |
| `loadClasses()` | 177 | Loads the classes for icons by id and by name, and the active ones for the target-class picker. |
| `detectSignal()` | 193 | The object's own signal colour, read off its data rather than configured anywhere: an explicit option colour first, a severity word second. |
| `connectionTally()` | 219 | One place that counts each kind of connection, so the tally row, the headline number and the columns can never disagree with each other. `descendants` is the TRANSITIVE list from the impact endpoint, not just direct children — a Server → Instance → Database → Job chain must not report one descendant. |
| `render()` | 241 | Rebuilds the whole page - breadcrumb, hero, stats, AI summary, blast radius, connections, properties, documents, domains, projects, activity and delete - then wires editing and runs the animations. |
| `breadcrumbHtml()` | 299 | Returns the breadcrumb - Browse, the class, the parent if any, then this object. |
| `heroHtml(signal)` | 311 | Returns the hero - icon, editable name, chips for class, signal, planned state, parent and staleness, and the summary and diagram buttons. |
| `statsHtml()` | 359 | Returns the four stat tiles - what breaks, open tickets, connections and days since last touched - each jumping to its section. |
| `tile(val, unit, label, cls, target)` | 366 | Returns one stat tile; with a target it scrolls to that section when clicked. |
| `aiHtml()` | 386 | Returns the AI summary box - the saved summary with when it was written, or a prompt to generate one. |
| `blastHtml()` | 407 | The blast radius as a left-to-right chain: root, then one column per hop. The grouped list said the same thing; the chain shows the direction a failure travels, which is the point of the panel. |
| `viaText(n)` | 464 | Every row says how it was reached, so a surprising entry can be traced rather than doubted. |
| `connectionsHtml()` | 476 | ONE panel where object.php had four. Impact, Map, Hierarchy and Relationships are four framings of "what is this attached to", and on a sparse object they produced four separate empty states. |
| `tk(base, n)` | 525 | Picks the singular or plural tally label for a count. |
| `nodeRow(id, name, className, verb, relId)` | 587 | Returns one linked object row with its verb, and an unlink button when it is a relationship row. |
| `upArrow()` | 601 | Returns the up-arrow SVG drawn between the parent and this object. |
| `downArrow()` | 605 | Returns the down-arrow SVG drawn between this object and its descendants. |
| `propsHtml()` | 611 | Filled properties first as cards; blanks collapse to one line. A row per empty field is how the old page ended up mostly saying "(not set)". |
| `propCard(p)` | 659 | Returns one property card - label, required mark, the cog that edits the property definition, and the value (click to edit). |
| `propValueHtml(p)` | 680 | Returns a property value for display - a linked object, Yes/No, a coloured drop-down pill, a number, a date without time, or text. |
| `toggleBlanks()` | 703 | Shows or hides the empty properties, then re-renders. |
| `activityHtml()` | 709 | Returns the Activity card - open tickets against this object, then the recently closed ones. |
| `row(t, isOpen)` | 721 | Returns one ticket link with its number, subject, status and priority. |
| `dangerHtml()` | 740 | Returns the delete strip, warning when the object has children. |
| `animateStats()` | 753 | A detail page is opened occasionally, not hundreds of times a day, so the numbers can afford to count up. Kept to 460ms and skipped entirely under prefers-reduced-motion. |
| `step(now)` | 763 | Advances one stat's count-up for this animation frame (ease-out cubic over 460ms). |
| `animateMeter()` | 774 | Animates the details meter to the share of properties that are filled. |
| `wireName()` | 786 | Makes the hero name editable - Enter saves, Escape reverts, blur saves when changed. |
| `wireProps()` | 802 | Makes each property value open its inline editor on click (links inside it still navigate). |
| `beginEdit(cell)` | 815 | Swaps a property value for an inline editor by type (object references use their own search) and saves it on blur or Enter; Escape cancels. |
| `commit()` | 847 | Saves the edited value unless it is unchanged, then restores or re-renders the cell. |
| `saveProperty(p, raw, cell, before)` | 864 | Saves one property value through save_object.php and reloads the page data; puts the old value back on failure. |
| `savePartial(patch)` | 882 | Saves the name, parent or planned flag through save_object.php (no property values) and reloads the page data. |
| `generateSummary(btn)` | 905 | Asks generate_object_summary.php to write the object's AI summary, then reloads and re-renders. |
| `createImpactDiagram(btn)` | 927 | Builds a Network Mapper diagram of the blast radius through create_impact_diagram.php and opens it. |
| `beginEditObjectRef(cell, p)` | 949 | Search is scoped to the property's target class and excludes this object, so a field can never be pointed at the thing that holds it. |
| `finish(raw)` | 966 | Saves the chosen reference (or null to clear it), once. |
| `cancel()` | 968 | Puts the old value back without saving, once. |
| `wireAutocomplete(inputEl, resultsEl, params, onPick)` | 992 | Wires an input to search_objects.php (optionally limited to a class and excluding an object) with a keyboard-navigable result list; onPick gets the chosen object. |
| `draw()` | 1026 | Draws the look-up results (or "no matches") with the highlighted row marked. |
| `togglePlanned()` | 1047 | Flips the object between planned and in service. |
| `openParentModal()` | 1054 | Opens the parent picker with the current parent filled in and an object search that excludes this object. |
| `closeParentModal()` | 1073 | Closes the parent picker. |
| `saveParent()` | 1076 | Saves the picked parent (an emptied box clears it); the server rejects a parent that would make a cycle. |
| `clearParent()` | 1088 | Closes the parent picker and removes the parent, if there is one. |
| `openRelModal()` | 1108 | Opens the add-relationship modal with the verb list, outgoing direction and an object search; warns when no verbs are defined. |
| `closeRelModal()` | 1139 | Closes the add-relationship modal. |
| `setRelDirection(dir)` | 1142 | Sets whether this object is the subject ('out') or the other object is ('in'), and refreshes the sentence preview. |
| `updateRelPreview()` | 1151 | Writes out the sentence that will actually be stored, both ways round, so nobody has to hold the direction in their head while picking a verb. |
| `saveRelationship()` | 1166 | Saves the relationship through save_object_relationship.php, swapping from and to for the 'in' direction, then reloads. |
| `jumpToDetails()` | 1184 | Scrolls to the Details card, where property links are edited. |
| `deleteRelationship(id)` | 1190 | Asks for confirmation, then deletes a relationship through delete_object_relationship.php and reloads. |
| `initPropDefModalDrag()` | 1211 | Makes the property definition modal draggable by its header. |
| `openPropDefModal(propertyId)` | 1238 | Opens the property definition modal for one of this class's properties, filled with its label, key, type, order, flags, target class and options. |
| `closePropDefModal()` | 1264 | Closes the property definition modal. |
| `onPropDefTypeChange()` | 1269 | Shows the target-class and spreads-impact fields only for object references, and the options editor only for drop-downs. |
| `savePropDef()` | 1279 | Validates and saves the property definition through save_class_property.php, then reloads, since it changes every object of the class. |
| `deleteObject()` | 1314 | Confirms (counting the whole subtree that will go with it), deletes the object through delete_object.php, and returns to Browse. |

## API endpoints it calls

- [api/cmdb/get_relationship_types.php](/code/files/api/cmdb/get_relationship_types.php.md)
- [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md)
- [api/cmdb/get_object_impact.php](/code/files/api/cmdb/get_object_impact.php.md)
- [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md)
- [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md)
- [api/cmdb/save_object.php](/code/files/api/cmdb/save_object.php.md)
- [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md)
- [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md)
- [api/cmdb/search_objects.php](/code/files/api/cmdb/search_objects.php.md)
- [api/cmdb/save_object_relationship.php](/code/files/api/cmdb/save_object_relationship.php.md)
- [api/cmdb/delete_object_relationship.php](/code/files/api/cmdb/delete_object_relationship.php.md)
- [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md)
- [api/cmdb/delete_object.php](/code/files/api/cmdb/delete_object.php.md)

## Loaded by

- [cmdb/object.php](/code/files/cmdb/object.php.md)

