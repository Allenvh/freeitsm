# assets/js/workflow-executions.js

> JavaScript - 335 lines

Workflows — execution log.

Filters are held in the URL, so a filtered view is shareable, bookmarkable and
deep-linkable — which is what lets the Watchtower card and the status tallies
link straight to "the failures from today" rather than dropping you at an
unfiltered list and leaving you to find them.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `esc(s)` | 13 | Escapes text for safe use in HTML. |
| `fmtWhen(s)` | 33 | started_datetime is a server-stamped UTC instant → show in the analyst's zone. |
| `fmtTook(a, b)` | 40 | How long the run took. Both timestamps are UTC, so the difference is safe. |
| `statusPill(status, dry)` | 50 | Returns the coloured status badge for a run, plus the dry-run pill when it was one. |
| `readUrlIntoControls()` | 65 | Copies the filter values and page number from the URL query string into the filter controls. |
| `currentParams()` | 75 | Builds the query string from the filled filter controls and the current page. |
| `pushUrl()` | 86 | Replaces the page URL with the current filters, so the view can be bookmarked or shared. |
| `load()` | 96 | Fetches the filtered page of runs from executions.php and draws the rows, the status tallies and the pager (filling the drop-downs on the first load). |
| `fillFacets(f)` | 142 | Fills the workflow and trigger drop-downs from the log's facets, then re-applies the URL's filters now the options exist. |
| `renderTally(tally)` | 160 | Status counts across the whole log — and one-click filters into it. |
| `renderPager(d)` | 173 | Draws the pager - just a count on a single page, otherwise previous/next with the range shown. |
| `open(id)` | 192 | Opens the run detail modal and loads one run from execution_detail.php. |
| `renderDetail(d)` | 210 | Returns the run detail HTML - header, error, each step, the webhook deliveries it queued, and the payload snapshot. |
| `renderStep(s)` | 259 | Returns the HTML for one logged step - a condition with what it compared and the actual value, an action with its status and result, or an engine error. |

## API endpoints it calls

- [api/workflow/executions.php](/code/files/api/workflow/executions.php.md)
- [api/workflow/execution_detail.php](/code/files/api/workflow/execution_detail.php.md)

## Loaded by

- [workflow/executions.php](/code/files/workflow/executions.php.md)

