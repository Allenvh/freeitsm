# assets/js/system-roles.js

> JavaScript - 225 lines

System → Roles (RBAC Layer 2).

Create roles, tick the settings capabilities they grant, and assign them to
analysts and teams. Everything a role touches is saved as one unit by role.php.
The capability list, analyst list and team list are embedded server-side
(window.RBAC_*) — the page is admin-only, so there's nothing sensitive in
shipping them to the browser, and it saves a round-trip.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `Roles(()` | 10 | System → Roles (RBAC Layer 2). |
| `esc(s)` | 17 | HTML-escapes a value through a text node; null and undefined become an empty string. |
| `init()` | 22 | Loads the roles and wires the create and edit forms. |
| `load()` | 29 | Loads every role from roles.php and renders the table. |
| `render()` | 43 | Renders the roles table - capability count, who it is assigned to, active state and Edit / Delete. |
| `openCreate()` | 80 | Opens the empty New role dialog. |
| `create(e)` | 90 | Creates the role through roles.php, reloads the list and opens the new role for editing so it can be granted and assigned. |
| `openEdit(id)` | 107 | Loads one role from role.php and opens the edit dialog with its capabilities, analysts and teams ticked. |
| `renderCapabilities(selected)` | 126 | Renders the capability ticks grouped by module, marking umbrella and sensitive capabilities. |
| `renderPicker(elId, items, selectedIds, showAdmin)` | 147 | Renders a tick list of analysts or teams, noting analysts who are already administrators. |
| `save(e)` | 162 | Saves the role's name, description, active flag, capabilities, analysts and teams as one PUT to role.php, then reloads. |
| `remove(id, name)` | 185 | Deletes a role through role.php after a confirmation that warns its holders lose the access. |
| `post(endpoint, body)` | 204 | POSTs JSON to a System API endpoint and returns the reply; a network failure becomes {success: false, error}. |
| `open(id)` | 218 | Shows a modal by id. |
| `close(id)` | 220 | Hides a modal by id. |

## API endpoints it calls

- [api/system/roles.php](/code/files/api/system/roles.php.md)

## Loaded by

- [system/roles/index.php](/code/files/system/roles/index.php.md)

