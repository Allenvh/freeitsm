# assets/js/project-links.js

> JavaScript - 127 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 8 Connections and Templates](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-8-Connections-and-Templates)

The Projects panel other modules show (3.2.0): the projects a record - an asset, a change, a ticket, a contract, a CMDB item, a knowledge article or (3.3.0) a problem - is part of, each in its own colour with its health and progress, plus "Add to a project" and unlink.

ProjectLinks.mount(hostEl, {
      kind:  'asset' | 'change' | 'ticket' | 'contract' | 'cmdb' | 'article' | 'problem',
      id:    the record's id,
      base:  BASE_URL,
      cardClass / headClass / titleClass: the host page's own card classes,
      bare:  true when the host draws its own heading - just the body,
      editable: false to list only,
      hideEmpty: true to draw nothing at all when there is nothing linked,
  });

Every rule - Projects plus the other module, both ends visible, same company,
and Projects -> Settings -> General for who may change a project - lives in
includes/projects/links.php. A host page only mounts this for an analyst who
can open Projects, so nothing here has to ask.

Words use window.tf() with English fallbacks, so a host page does not have to
export the whole 'projects' translation namespace to show one panel.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `L(k, en, p)` | 33 | Translates a projects.* key, falling back to the English given when the host page has not loaded it. |
| `esc(v)` | 35 | Escapes a value for safe use in HTML. |
| `toast(m, k)` | 37 | Shows a toast when the host page has the shared toaster. |
| `call(base, path, body)` | 45 | Calls an api/projects/ endpoint - a GET without a body, a JSON POST with one - and throws on failure. |
| `row(p, editable, base)` | 54 | One project row - colour dot, name, code, status, owner, health dot, progress bar and, when editable, the unlink button. |
| `mount(host, o)` | 69 | Draws the Projects panel for one record into host and wires its search-to-add box and unlink buttons. |
| `draw()` | 76 | Redraws the panel from the loaded rows - as a card, or just the body when bare, or nothing when hideEmpty and empty. |
| `load()` | 89 | Loads the projects this record belongs to and redraws. |

## Loaded by

- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [knowledge/index.php](/code/files/knowledge/index.php.md)

