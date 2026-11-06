# assets/js/domains-view.js

> JavaScript - 697 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Domains Developer Guide](https://github.com/edmozley/freeitsm/wiki/Domains-Developer-Guide)

Domains — one domain's page (domains/view.php).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `load()` | 25 | Fetches the domain and redraws every tab, then loads its connections; on failure shows the error in the hero. |
| `loadLookups()` | 36 | Fetches the drop-down lists the Edit dialog needs; leaves them null when that fails. |
| `renderHero()` | 41 | ---------------------------------------------------------------- hero |
| `row(k, v, mono)` | 71 | ---------------------------------------------------------------- overview |
| `yn(v)` | 76 | Draws a lock or DNSSEC flag as a green on pill, a red off pill, or grey unknown. |
| `renderOverview()` | 82 | Draws the Overview tab: the top three checks worth fixing, then the registration, DNS and web, and people and money cards. |
| `authCodeCell()` | 137 | Draws the auth code field: hidden with Reveal and Change links for analysts allowed auth codes, otherwise just whether one is set. |
| `renderSecurity()` | 146 | Draws the Security tab - the grade and score, then the findings grouped by area, worst first. |
| `renderCerts()` | 164 | ---------------------------------------------------------------- certificates |
| `renderLookalikes()` | 190 | ---------------------------------------------------------------- look-alikes |
| `renderHistory()` | 212 | ---------------------------------------------------------------- history |
| `loadConnections()` | 245 | Fetches what the domain is linked to and redraws the Connections tab. |
| `connIcon(paths)` | 252 | Wraps icon paths in an SVG for a connections card heading. |
| `riskHtml()` | 260 | Draws the banner warning that linked services are at risk from an expired domain or certificate, with the open incident, the Service Status mode, or a Raise incident button. |
| `contractHtml()` | 282 | Draws the Contract card - the linked contract or none, and a button that opens Edit to set it. |
| `rowHtml(kind, r)` | 293 | Draws one linked record with its link, a status or inactive pill, and an unlink button. |
| `renderConnections()` | 306 | Draws the Connections tab: the risk banner, the contract card and one card per kind the analyst may open, and sets the tab badge (red when services are at risk with no incident). |
| `connSearch(input)` | 335 | Searches records of one kind that can be linked to this domain and shows the results under the box. |
| `wireConnections()` | 352 | Wires the Connections tab once: search as you type, link on pick, unlink, Edit for the contract, Raise incident, and keyboard movement through the results. |
| `switchTab(tab)` | 408 | ---------------------------------------------------------------- actions |
| `busy(btn, fn)` | 417 | Runs an action with its button disabled and showing "Working", then puts the button back. Errors become a toast. |
| `wire()` | 426 | Wires the page once: tabs, Refresh, Check, Edit, Delete, the auth code links and dialog, certificate and look-alike scans, and dismissing look-alikes. |
| `peopleLink(page, id, name)` | 475 | ------------------------------------- who: tech contact + customer (#162) The technical contact is a supplier contact OR one of your analysts; the customer is a person OR a supplier, optionally with one of its contacts. |
| `techCell()` | 481 | Draws the technical contact - an analyst, or a supplier contact linked to People. |
| `customerCell()` | 490 | Draws the customer - a person, a supplier with or without one of its contacts, or a contact - linked to People where the analyst can open it. |
| `customerLabel()` | 504 | Returns the customer as plain text for the Edit dialog's search box. |
| `fillTech()` | 517 | Fills the technical contact drop-down with analysts and supplier contacts, keeping the current one even when it is no longer in either list, and remembers the value it opened with. |
| `opt(v, label)` | 520 | Creates one option element. |
| `group(label, items)` | 522 | Creates an option group holding the given options. |
| `openEdit()` | 546 | Fills the Edit dialog from the domain - drop-downs, every data-f field, the technical contact and the customer - and opens it. |
| `setCustomer(key, label)` | 581 | --------------------------------- customer: person (#153) or supplier (#162) eCustomerId holds "user:5", "supplier:3" or "contact:9" - one kind only. |
| `hideCustomers()` | 588 | Hides and empties the customer search results. |
| `searchCustomers()` | 594 | Searches people and suppliers for the customer box after a short pause and lists them, grouped when both kinds match. Typing clears the chosen customer; a reply to an older search is ignored. |
| `item(key, label, sub)` | 614 | Builds one customer result that sets the customer when picked. |
| `heading(text)` | 623 | Builds a non-selectable group heading for the customer results. |
| `customerKeys(e)` | 637 | Arrow keys move through the customer results, Enter picks one and Escape closes the list. |
| `saveEdit()` | 665 | Saves the Edit dialog through save.php and reloads the page. |

## Loaded by

- [domains/view.php](/code/files/domains/view.php.md)

