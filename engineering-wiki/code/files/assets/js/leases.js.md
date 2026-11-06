# assets/js/leases.js

> JavaScript - 473 lines

Assets -> Leases (3.5.0). Drives both pages: asset-management/leases/ the list + Due back (LEASES_CFG.page = 'list') asset-management/leases/view.php one lease (LEASES_CFG.page = 'lease')

Rules live server-side in includes/leases.php; this file only renders and
posts. Modals are built here so both pages share one copy.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `t(k, p)` | 16 | Translates a key from the asset-management.leases language section. |
| `tc(k, p)` | 18 | Translates a key from the shared asset-management.common language section (Save, Cancel, Edit...). |
| `esc(s)` | 20 | Escapes a value for safe use in HTML; null and undefined become an empty string. |
| `d(v)` | 26 | Formats a plain date for display, escaped, or a dimmed dash when there is none. |
| `toast(msg, type)` | 28 | Shows a toast message (success unless a type is given). |
| `today()` | 30 | Today's date as YYYY-MM-DD in the user's time zone. |
| `addDays(ymd, n)` | 32 | Adds n days to a YYYY-MM-DD date and returns the new YYYY-MM-DD. |
| `money(v, cur)` | 37 | Formats an amount in the given currency for the page language, or as a plain two-decimal number when there is no valid currency. |
| `get(path, params)` | 47 | GETs a Leases API endpoint with optional query parameters and returns the JSON, throwing when success is false. |
| `post(path, body)` | 55 | POSTs a JSON body to a Leases API endpoint and returns the JSON, throwing when success is false. |
| `fail(e)` | 62 | Shows an error as an error toast. |
| `endBadge(end)` | 65 | "Ends" with how near it is: overdue / within 90 days. |
| `modal(title, bodyHtml, onSave, saveLabel)` | 76 | Modals (one generic builder) |
| `close()` | 85 | Removes the modal from the page. |
| `field(label, inner, hint)` | 101 | HTML for one labelled form group, with an optional hint line under it. |
| `opts(list, sel, blank)` | 105 | HTML option list from {id, name} rows, marking the selected id and adding an optional blank first option. |
| `val(w, name)` | 110 | The trimmed value of the named field inside a modal, or an empty string when the field is missing. |
| `leaseForm(lease)` | 118 | Opens the Add / Edit lease modal and saves it through save.php. Loads the options (companies, suppliers, cost centres, contracts) first; changing the company reloads that company's cost centres and contracts. A new lease opens its own page once saved. |
| `ccList(list)` | 123 | Turns cost centre rows into {id, name} options labelled "code - name", marking inactive ones. |
| `scheduleForm(leaseId, s)` | 161 | Opens the Add / Edit schedule modal (dates, cost and period, currency, order number) and saves it through schedule_save.php. |
| `assetForm(row, sched)` | 190 | Opens the modal for one asset on a schedule - its outcome, its own start and end dates and the outcome date - and saves it through asset_update.php. |
| `addAssetsForm(sched)` | 213 | Opens the modal that searches assets and puts the ticked ones on a schedule through assets_add.php. Assets already on another lease are shown but cannot be ticked; the toast reports any the server refused. |
| `count()` | 229 | Shows how many assets are ticked in the Add assets modal. |
| `initList()` | 259 | Sets up the list page - the Leases / Due back tabs, Add, the search box and the days filter - and opens the tab named in the URL hash. |
| `show(which)` | 262 | Switches the list page to the Leases or Due back tab, updates the URL hash and loads that tab's data. |
| `loadList()` | 282 | Loads the leases matching the search box from list.php and renders the table, or the empty state when there are none. |
| `dueTable(rows, withLease)` | 312 | HTML table of assets due back (asset, holder, location, end, notice-by date, outcome), with a lease column when asked for; overdue rows are flagged. |
| `loadDue()` | 331 | Loads the assets due back within the chosen number of days from due_back.php, renders them and points the Export link at the CSV of the same list. |
| `loadLease()` | 345 | One lease |
| `renderLease(l)` | 359 | Renders one lease page - the header card with its facts, then a card per schedule with its assets, outcomes and actions - and wires up its buttons. |
| `wireLease(box, l)` | 424 | Wires the buttons and outcome drop-downs on a lease page (edit / delete lease, add / edit / delete schedule, add / edit / remove assets, change outcome), all through one click and one change listener. |
| `sched(el)` | 426 | The schedule whose card holds the clicked element. |
| `row(el, s)` | 428 | The asset row of the schedule that holds the clicked element. |

## Loaded by

- [asset-management/leases/index.php](/code/files/asset-management/leases/index.php.md)
- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)

