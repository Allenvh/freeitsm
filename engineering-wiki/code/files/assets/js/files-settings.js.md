# assets/js/files-settings.js

> JavaScript - 108 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Files Developer Guide](https://github.com/edmozley/freeitsm/wiki/Files-Developer-Guide)

Files → Settings (files/settings/index.php). Both tabs read and write through api/files/settings.php; Take ownership is api/files/permissions.php.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `L(k, en, p)` | 11 | Translates a Files string (key under 'files.'), falling back to the English when `tf()` is not loaded. |
| `esc(s)` | 13 | Escapes a value for HTML, treating null as an empty string. |
| `api(path, body)` | 15 | Calls an api/files endpoint - a GET with no body, a JSON POST with one - and rejects on success: false. |
| `fmtSize(n)` | 21 | Formats a byte count as B, KB, MB, GB or TB. |
| `switchTab(id)` | 29 | Shows one settings tab and puts it in the address (?tab=) so a reload opens the same tab. |
| `loadFolders()` | 41 | Fetches every folder for the Folders tab and draws the table, or shows the error in it. |
| `renderFolders()` | 52 | Draws the Folders table, filtered by the path box. |
| `go()` | 74 | Takes ownership of the folder (Full control, audited by the server) once confirmed, then reloads the table. |

## Loaded by

- [files/settings/index.php](/code/files/files/settings/index.php.md)

