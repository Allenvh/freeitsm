# assets/js/network-mapper-icons.js

> JavaScript - 302 lines

Network Mapper — icon library.

Maps icon_key → inner SVG markup (paths/shapes). Each entry expects a 24×24
viewBox and uses currentColor + stroke-width 1.8 for crisp rendering at the
palette tile (40px) and on-canvas node sizes (small=48 / medium=72 /
large=96). Style is intentionally Feather-flavoured to match the rest of the
UI's icon vocabulary.

Two consumers:
  1. CMDB class default icons — `cmdb_classes.icon_id` FK to `cmdb_icons`,
     whose `icon_key` matches a key here. Class icons render across CMDB +
     Network Mapper. Adding an icon here but not seeding the `cmdb_icons`
     row means class settings can't pick it — but per-node override still
     works since that's a free-text VARCHAR.
  2. Per-node icon override on Network Mapper diagrams
     (`network_diagram_nodes.icon_override`). Free-form string, any key
     below is fair game even if not in `cmdb_icons`.

Category metadata drives the per-node icon picker UI — icons grouped under
their category in the modal. Adding a new icon: append to ICONS + META, and
(if it should also be class-pickable) add to the cmdb_icons seed in
api/system/db_verify.php and database/freeitsm.sql.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `nm(key, english)` | 162 | Translate, or the English written right here. Every label below is a GETTER rather than a value: this file is evaluated the moment it loads, which can be before the host page has set window.translations, and a value read once at load time would be English for the rest of the session. A getter is read when the palette is drawn. |
| `renderIcon(key, size, extra)` | 288 | Build a full <svg> element string for a given icon_key. key — icon_key (e.g. 'server'); unknown keys fall back to box size — pixel width/height (defaults to 24) extra — extra attributes string for the <svg> (e.g. 'class="nm-icon"') |

## Loaded by

- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)
- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [network-mapper/diagram.php](/code/files/network-mapper/diagram.php.md)

