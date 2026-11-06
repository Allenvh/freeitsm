# assets/js/domain-links.js

> JavaScript - 201 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Domains Developer Guide](https://github.com/edmozley/freeitsm/wiki/Domains-Developer-Guide)

The Domains panel other modules show (3.0.0): the domains linked to a CMDB object, a knowledge article or a contract, with unlink and "link a domain".

DomainLinks.mount(hostEl, {
      kind:  'cmdb' | 'article' | 'contract',
      id:    the record's id,
      base:  BASE_URL,
      cardClass / headClass / titleClass: the host page's own card classes,
      editable: false to list only
      hideEmpty: true to show nothing at all when nothing is linked
      onChange: called after a link is made or removed
      bare: true when the host page draws its own heading - just the body
  });

Every rule - who may see or make a link, same company, the other module's own
permissions - is server-side in includes/domains/links.php. A host page only
mounts this for an analyst who can open Domains, so nothing here has to ask.

Labels come from the 'domains' translation namespace, which the host page
exports alongside its own.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 30 | Translates a key from the 'domains' namespace, or returns the key when no translator is loaded. |
| `esc(v)` | 32 | HTML-escapes a value; null and undefined become an empty string. |
| `fmt(d)` | 34 | Formats a stored date as a calendar date in the analyst's format, or an empty string. |
| `call(base, path, body)` | 46 | Calls an api/domains/ endpoint - a GET, or a JSON POST when a body is given - and throws on failure. |
| `toast(m, k)` | 54 | Shows a toast through the host page's showToast, when it has one. |
| `pill(days)` | 57 | Draws the expiry countdown pill - red once expired, amber within 30 days, green after that. |
| `money(amount, cur)` | 67 | Formats an amount in a currency (GBP when none is given), falling back to plain digits for an unknown code. |
| `mount(host, o)` | 78 | Draws the Domains panel into a host element and wires its search, link and unlink controls. |
| `load()` | 86 | Fetches the linked domains and redraws the panel - nothing at all when the analyst cannot use Domains, or when nothing is linked and hideEmpty is set. A contract also gets a yearly cost total per currency. |
| `search(input)` | 125 | Fills the results list with domains matching the search box that can be linked here. |
| `link(domainId, add)` | 148 | Links or unlinks one domain, then reloads the panel and calls onChange. |

## Loaded by

- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [knowledge/index.php](/code/files/knowledge/index.php.md)

