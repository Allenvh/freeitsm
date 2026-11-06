# assets/js/record-preview.js

> JavaScript - 296 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Record Previews Developer Guide](https://github.com/edmozley/freeitsm/wiki/Record-Previews-Developer-Guide)

At-a-glance previews of a linked record (discussion #91).

dschipfel asked to see what is on the other end of a link without leaving the
page he is on, and asked specifically for "a small information badge/icon"
next to the link rather than a right-click menu, because right-click is not
discoverable and does not exist on a tablet.

So the badge is a button rather than a hover target: hovering is the same
problem as right-clicking wearing a different hat — invisible until you
happen to do it, and unavailable on touch. It opens on click, on Enter and on
Space.

⚠️ It is a SPAN with role="button", not a <button>. Half the places a linked
record appears are pills that are themselves anchors, and a <button> inside an
<a> is invalid — browsers recover from it differently, and the badge would be
the one element whose behaviour depended on which screen you found it on. The
cost is handling Enter and Space by hand, below.

⚠️ ONE popover for the whole page. Two open previews would be two answers to
"what is this", and the second would be read as belonging to the first badge.

🔴 Every failure looks the same on screen. The server deliberately gives one
answer for "does not exist" and "you may not see it" — see
includes/record_preview.php — and a UI that distinguished them (an empty card
here, an error there) would leak exactly what the server withheld. A network
failure lands in the same place for the same reason.

Usage from a module:
    html += FreeITSMPreview.badge('ticket', 42);
and, after changing a record whose preview may be cached:
    FreeITSMPreview.forget('ticket', 42);

## Functions

| Function | Line | What it does |
|---|--:|---|
| `esc(s)` | 60 | HTML-escapes a value; null and undefined become an empty string. |
| `rt(key, fallback)` | 67 | A `common.preview.*` string, with an English fallback if i18n is absent. |
| `badge(type, id)` | 83 | The badge, as an HTML string — modules build their rows with template literals, so handing back a node would mean every caller changed shape. |
| `ensurePopover()` | 92 | Returns the page's one popover element, creating it hidden on first use. |
| `close()` | 103 | Hides the popover, drops the phone sheet mode and resets the badge it belonged to. |
| `place(anchor)` | 122 | Anchor the card to its badge, flipping when there is no room. |
| `renderLoading(anchor)` | 155 | Shows the loading message in the popover next to the badge. |
| `renderUnavailable(anchor, message)` | 165 | Shows the single "cannot be shown" card - used alike for not found, not allowed and network failure. |
| `render(anchor, p)` | 176 | Draws a record preview into the popover - heading, label / value rows (with a colour dot where given), lead text and an Open link. |
| `show(anchor)` | 208 | Opens the popover for a badge, from the cache when possible, otherwise fetching record_preview.php and caching the answer (or "gone"). |
| `forget(type, id)` | 290 | Drop a cached preview after the record behind it has been edited. |

## API endpoints it calls

- [api/system/record_preview.php](/code/files/api/system/record_preview.php.md)

## Loaded by

- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [tasks/index.php](/code/files/tasks/index.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)

