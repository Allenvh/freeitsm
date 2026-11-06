# cmdb/object.php

> PHP - 939 lines

CMDB Object Detail Page — PROTOTYPE (v2 layout)

A side-by-side alternative to object.php, built to test whether the CMDB
can lead with answers rather than fields. Same object, same endpoints, same
data — different information architecture:

  - a hero band with the class icon (cmdb_icons has always had one per
    class; object.php never rendered it) and the object's own signal colour
  - a strip of headline numbers, so "what does this thing matter to?" is
    answered before any scrolling
  - the blast radius drawn as a left-to-right chain of hops instead of a
    grouped list
  - Impact / Map / Hierarchy / Relationships merged into ONE connections
    panel, because they are four framings of a single question and on a
    sparse object they produced four separate empty states
  - properties with a value shown first; blank ones collapsed behind a
    single line rather than each occupying a row saying "(not set)"
  - Delete demoted out of the header into a danger zone at the foot

⚠️ Merging those four panels for READING broke the mapping to WRITING: the
four kinds are created four different ways, so the connection tally counts
by KIND (descendants / relationships / property links / parent) rather than
by direction, and each kind carries its own control. A single
"+ Add relationship" button over the merged panel implied it could create
all four when it can only ever create one.

Editing is at full parity with object.php, through the same endpoints:
name, parent (set/change/detach), planned toggle, relationship add/remove,
every property type including object_ref, and the property-DEFINITION modal
(reusing the shared options-editor.js). The add-relationship modal goes one
further with a direction switch — object.php can only create outgoing
relationships, so "SolarWinds monitors this" cannot be recorded from the
server's own page there.

Strings are English-only on purpose — this is a prototype for a layout
decision, and translating ~70 keys into two locales before that decision is
made would be waste. If this layout wins, i18n comes with the merge.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [cmdb/includes/header.php](/code/files/cmdb/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/network-mapper-icons.js](/code/files/assets/js/network-mapper-icons.js.md), [cmdb/options-editor.js](/code/files/cmdb/options-editor.js.md), [assets/js/domain-links.js](/code/files/assets/js/domain-links.js.md), [assets/js/project-links.js](/code/files/assets/js/project-links.js.md), [cmdb/object.js](/code/files/cmdb/object.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [entityVisit()](/code/functions/entityVisit.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

