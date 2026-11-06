# assets/js/tasks-priority.js

> JavaScript - 110 lines

How a task's priority is drawn — the one home for it.

🔴 The colour comes from `task_priorities.colour`, NEVER from the name.

Every renderer used to build a CSS class out of the priority's display name
(`priority-dot ${t.priority.toLowerCase()}`) against four hardcoded English
rules — urgent / high / medium / low. Rename a priority, translate it, or add
a fifth, and the class matched nothing: the element still rendered, as an 8px
transparent circle. No error, no fallback, no dot. Reported from a German
install (discussion #108) where the priorities had been renamed to Hoch /
Mittel / Niedrig, which is the first thing a German admin would do.

Same disease as GH #79, where every ticket intake path looked the starting
status up by the word 'Open' and a German site that had renamed it to Offen
got tickets with no status at all. The cure is the same: the display name is
the user's to change, so nothing may be derived from it.

It was also wrong in English. The seeded colours and the CSS never agreed —
Low is configured #16a34a green in Settings and was drawn #999 grey — so the
swatch an admin picked was not the colour they got.

Loaded by the board, timeline and table pages; keep it dependency-free so it
can sit ahead of any of them.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `safeColour(value)` | 45 | Only a literal hex value may reach a style attribute. Mirrors `inboxSafeColour()` in inbox.js — the colour is admin-editable free text, so it is untrusted input on its way into markup. |
| `esc(s)` | 51 | HTML-escapes a value; null and undefined become an empty string. |
| `normaliseStyle(value)` | 62 | Coerce a stored value to a known style. Legacy installs hold the old on/off boolean: 1 becomes a dot, which is exactly what they see today, so nobody's board changes under them. |
| `markup(name, colour, style)` | 73 | The inline indicator — a dot, or a pill that also carries the name. Returns '' for 'off', for 'border' (which is drawn by the container, not here) and when the task has no priority at all. |
| `accentAttrs(name, colour, style, extraStyle)` | 97 | The left-edge accent, as attributes for the container element. |

## Loaded by

- [tasks/index.php](/code/files/tasks/index.php.md)
- [tasks/settings/index.php](/code/files/tasks/settings/index.php.md)
- [tasks/timeline/index.php](/code/files/tasks/timeline/index.php.md)

