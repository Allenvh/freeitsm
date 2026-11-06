# assets/js/widget-editor.js

> JavaScript - 453 lines

WidgetEditor — shared form logic for creating/editing dashboard widgets. Used by both the library page (inline panel) and the dashboard page (modal).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `we(key, english, params)` | 9 | Translate, or the English written at the call site (i18n.js). |
| `propertyLabel(p)` | 15 | Read through functions, not constants: this file is evaluated at load time, and a constant would freeze whatever window.translations held then. |
| `propertyInSentence(p)` | 38 | The same property as it reads INSIDE a sentence - not a lower-cased title. |
| `seriesLabel(s)` | 56 | Returns the translated title for a series property (Status or Priority). |
| `seriesInSentence(s)` | 60 | Returns the series property as it reads inside a sentence ("status", "priority"). |
| `timeGroupingLabel(g)` | 65 | Returns the translated label for a time grouping (Daily, Monthly, Yearly). |
| `timeUnit(g)` | 74 | The unit as a noun: "per day". Was derived by stripping "ly" off "Daily", which gave "dai". |
| `dateRangeLabel(r)` | 83 | Returns the translated label for a date-range code ('' is All time, '7d' is Last 7 days, and so on). |
| `dateRangeInSentence(r)` | 96 | Returns a date-range code as it reads inside a sentence ("last 7 days"), falling back to the label. |
| `getValidChartTypes(aggProp, seriesProp)` | 135 | Returns the chart types that fit the choice - bar or line for a series or a time aggregate, otherwise bar, doughnut or pie. |
| `escapeHtml(str)` | 143 | Escapes text for safe use in HTML. |
| `generateDescription()` | 152 | Builds the automatic widget description from the form - property, series, time unit, date range and any department subset - as one translated sentence. |
| `autoFillDescription()` | 201 | Rewrites the description field with the generated sentence, unless the analyst has typed their own. |
| `buildDeptCheckboxes()` | 207 | Draws one checkbox per active department for the department filter. |
| `onPropertyChange()` | 221 | Reacts to a new aggregate property - rebuilds the allowed series options, shows or hides the series and time-grouping fields, then refreshes chart types, the filterable tick and the description. |
| `updateChartTypeOptions()` | 264 | Limits the chart-type list to the types valid for the property and series, keeping the current choice when it is still allowed. |
| `updateFilterableVisibility()` | 289 | Hides and unticks "status filterable" when the series is status; shows it otherwise. |
| `bindEventListeners()` | 301 | Wires the form fields so every change refreshes the dependent options and the automatic description. |
| `init(base)` | 321 | Sets the API base, loads the active departments for the filter checkboxes, and wires the form events. |
| `populateForm(w)` | 334 | Fills the form from an existing widget row, including its department filter (stored as JSON). |
| `resetForm()` | 362 | Clears the form to the defaults for a new widget. |
| `collectFormData()` | 382 | Reads the form into the payload save_ticket_dashboard_widget.php expects; time_grouping is sent only for time aggregates. |
| `validateForm()` | 403 | Checks the title is filled and a time aggregate has a grouping, toasting the first problem. |
| `saveWidget()` | 417 | Validates, then posts the widget to save_ticket_dashboard_widget.php and returns the JSON reply. |

## Loaded by

- [tickets/dashboard/index.php](/code/files/tickets/dashboard/index.php.md)
- [tickets/dashboard/library.php](/code/files/tickets/dashboard/library.php.md)

