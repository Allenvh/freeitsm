# assets/js/tz.js

> JavaScript - 396 lines

Shared per-analyst timezone helpers (Phase 2 of the per-user timezone rollout).

`window.USER_TIMEZONE` is published server-side by Tz::scriptTag() (see
includes/timezone.php). Every datetime is stored UTC; these helpers render it
in the analyst's chosen zone. When USER_TIMEZONE is unset the `timeZone`
option is simply omitted, so dates fall back to the browser's own zone.

Load this file BEFORE any module JS that formats dates. It exposes globals
that mirror the proven tickets/inbox.js implementation, so a module's date
formatters become timezone-aware by:
  1. parsing the DB string as UTC:      const d = parseUTCDate(str);
  2. spreading `tzOpts()` into Intl opts:  d.toLocaleString(locale, tzOpts({ ... }))
  3. bucketing Today/Yesterday via:      ymdInZone(d) === ymdInZone(new `Date()`)

## Functions

| Function | Line | What it does |
|---|--:|---|
| `parseUTCDate(dateStr)` | 19 | Parse a DB datetime string as UTC (append Z if it carries no zone marker), returning an absolute-instant Date. Returns null for empty input. |
| `tzOpts(extra)` | 30 | Merge the analyst's display zone into an Intl.DateTimeFormat / toLocale* options object. Pass the options you'd normally use; the timeZone is added only when the analyst has chosen one. |
| `ymdInZone(date)` | 38 | 'YYYY-MM-DD' for a Date, evaluated in the analyst's display zone. Use for Today/Yesterday bucketing against the same zone the time is shown in. |
| `parseNaiveDate(str)` | 49 | Parse a NAIVE wall-clock datetime (a user-entered scheduling value stored WITHOUT a zone — change plan windows, ticket work-start, calendar events, PIR actuals) into a Date built from its literal components, with NO zone interpretation. Format the result WITHOUT tzOpts, so "2pm" reads 2pm for every analyst. These values are NOT UTC instants — never run them through parseUTCDate/tzOpts. See the "Timezones and Time Handling" design note. |
| `zoneOffsetMs(date, tz)` | 80 | Milliseconds to ADD to a UTC instant to get the wall clock in `tz`. Derived by formatting the instant in that zone and reading the parts back as if they were UTC — the only way to get a named zone's offset, DST and all, without shipping a timezone library. |
| `nowForInput()` | 95 | 'YYYY-MM-DDTHH:MM' for NOW in the analyst's display zone — the value to prefill a datetime-local input with. |
| `pad(n)` | 99 | Pads a number to two digits with a leading zero. |
| `inputToUTC(value)` | 117 | A datetime-local value ('YYYY-MM-DDTHH:MM'), read as a wall clock in the analyst's display zone, converted to an ISO-8601 UTC string ending in Z — which is what the server expects. Sending the raw input value instead hands the server a zone-less string it can only read as UTC, so the entry lands offset by the analyst's whole UTC offset (GH #116). |
| `utcToInput(dbStr)` | 132 | The inverse: a stored UTC datetime -> 'YYYY-MM-DDTHH:MM' in the analyst's display zone, for prefilling a picker when EDITING an existing value. |
| `pad(n)` | 137 | Pads a number to two digits with a leading zero. |
| `cfg()` | 185 | Returns window.DATE_FORMAT, or the built-in FALLBACK when the page did not publish one. |
| `toDate(value)` | 192 | Coerce a call-site argument (DB string, Date, or null) to a Date. |
| `partsInZone(d)` | 205 | Returns {year, month, day, hour, minute} for a Date as seen in the analyst's display zone (window.USER_TIMEZONE). |
| `partsNaive(d)` | 228 | Components of a NAIVE wall-clock Date exactly as parseNaiveDate built it — no zone interpretation, so "2pm" reads 2pm for every analyst. |
| `isoWeekday(p)` | 238 | ISO weekday for a y/m/d triple: 1 = Monday .. 7 = Sunday, matching the index order of DateFmt::weekdays(). Computed from the components rather than the Date so it agrees with whichever zone produced them. |
| `pad2(n)` | 244 | Pads a number to two digits with a leading zero. |
| `render(p, template)` | 253 | Renders date parts through a token template (DD, MON, YYYY, HH, mi...) using the analyst's localised month and weekday names. |
| `fmtDate(value)` | 285 | Formats a UTC timestamp (DB string or Date) as a date in the analyst's zone and chosen date format; '' for empty input. |
| `fmtTime(value)` | 291 | Formats a UTC timestamp as a time in the analyst's zone and chosen time format; '' for empty input. |
| `fmtDateTime(value)` | 297 | Formats a UTC timestamp as date then time in the analyst's zone and chosen formats; '' for empty input. |
| `fmtDayMonth(value)` | 304 | '25 Aug' — compact chips and list rows where the year is implied. |
| `fmtMonthYear(value)` | 310 | 'August 2026' — calendar headers. Always the full month name. |
| `fmtWeekday(value, short)` | 316 | 'Tuesday' / 'Tue' |
| `naive(value)` | 328 | Coerces a naive wall-clock value (DB string or Date) to a Date via parseNaiveDate, or null when empty or invalid. |
| `fmtNaiveDate(value)` | 335 | Formats a naive wall-clock value as a date in the chosen date format, with no zone conversion. |
| `fmtNaiveTime(value)` | 341 | Formats a naive wall-clock value as a time in the chosen time format, with no zone conversion. |
| `fmtNaiveDateTime(value)` | 347 | Formats a naive wall-clock value as date then time in the chosen formats, with no zone conversion. |
| `fmtNaiveDayMonth(value)` | 354 | Formats a naive wall-clock value as a compact day and month ('25 Aug'), with no zone conversion. |
| `fmtNaiveWeekday(value, short)` | 360 | 'Tuesday' / 'Tue' for a naive wall-clock value. |
| `fmtNaiveMonthYear(value)` | 367 | 'August 2026' for a naive wall-clock value — calendar headers. |
| `fmtTemplate(value, template)` | 385 | A few places need a shape none of the functions above produce - a calendar week title that compresses "5 - 11 May 2026", say. Before #105 those wrote their own toLocale* call with a hardcoded locale, which is exactly the drift this work removes. These render an arbitrary TOKEN TEMPLATE (see the token list in includes/timezone.php) through the same renderer and the same localised month and weekday names, so a bespoke label still follows the analyst's language. |
| `fmtNaiveTemplate(value, template)` | 391 | Renders a naive wall-clock value through a caller-chosen token template - the naive twin of fmtTemplate. |

## Loaded by

- [asset-management/dashboard/index.php](/code/files/asset-management/dashboard/index.php.md)
- [asset-management/dashboard/library.php](/code/files/asset-management/dashboard/library.php.md)
- [asset-management/help-custom-fields.php](/code/files/asset-management/help-custom-fields.php.md)
- [asset-management/help.php](/code/files/asset-management/help.php.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [asset-management/leases/index.php](/code/files/asset-management/leases/index.php.md)
- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)
- [asset-management/manager-access.php](/code/files/asset-management/manager-access.php.md)
- [asset-management/servers/index.php](/code/files/asset-management/servers/index.php.md)
- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)
- [asset-management/table.php](/code/files/asset-management/table.php.md)
- [asset-management/users.php](/code/files/asset-management/users.php.md)
- [calendar/help.php](/code/files/calendar/help.php.md)
- [calendar/index.php](/code/files/calendar/index.php.md)
- [calendar/settings/index.php](/code/files/calendar/settings/index.php.md)
- [calendar/table/index.php](/code/files/calendar/table/index.php.md)
- [change-management/approvals.php](/code/files/change-management/approvals.php.md)
- [change-management/calendar.php](/code/files/change-management/calendar.php.md)
- [change-management/help.php](/code/files/change-management/help.php.md)
- [change-management/settings/index.php](/code/files/change-management/settings/index.php.md)
- [change-management/table.php](/code/files/change-management/table.php.md)
- [checklists/help.php](/code/files/checklists/help.php.md)
- [cmdb/audit.php](/code/files/cmdb/audit.php.md)
- [cmdb/help.php](/code/files/cmdb/help.php.md)
- [cmdb/index.php](/code/files/cmdb/index.php.md)
- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md)
- [contracts/contacts/index.php](/code/files/contracts/contacts/index.php.md)
- [contracts/edit.php](/code/files/contracts/edit.php.md)
- [contracts/help.php](/code/files/contracts/help.php.md)
- [contracts/index.php](/code/files/contracts/index.php.md)
- [contracts/rfp-builder/audit.php](/code/files/contracts/rfp-builder/audit.php.md)
- [contracts/rfp-builder/compare.php](/code/files/contracts/rfp-builder/compare.php.md)
- [contracts/rfp-builder/consolidate.php](/code/files/contracts/rfp-builder/consolidate.php.md)
- [contracts/rfp-builder/coverage.php](/code/files/contracts/rfp-builder/coverage.php.md)
- [contracts/rfp-builder/document.php](/code/files/contracts/rfp-builder/document.php.md)
- [contracts/rfp-builder/documents.php](/code/files/contracts/rfp-builder/documents.php.md)
- [contracts/rfp-builder/extracted.php](/code/files/contracts/rfp-builder/extracted.php.md)
- [contracts/rfp-builder/help.php](/code/files/contracts/rfp-builder/help.php.md)
- [contracts/rfp-builder/index.php](/code/files/contracts/rfp-builder/index.php.md)
- [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md)
- [contracts/rfp-builder/scoring.php](/code/files/contracts/rfp-builder/scoring.php.md)
- [contracts/rfp-builder/suppliers.php](/code/files/contracts/rfp-builder/suppliers.php.md)
- [contracts/rfp-builder/view.php](/code/files/contracts/rfp-builder/view.php.md)
- [contracts/settings/index.php](/code/files/contracts/settings/index.php.md)
- [contracts/suppliers/index.php](/code/files/contracts/suppliers/index.php.md)
- [contracts/suppliers/view/index.php](/code/files/contracts/suppliers/view/index.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [domains/accounts/index.php](/code/files/domains/accounts/index.php.md)
- [domains/dashboard/index.php](/code/files/domains/dashboard/index.php.md)
- [domains/help.php](/code/files/domains/help.php.md)
- [domains/index.php](/code/files/domains/index.php.md)
- [domains/settings/index.php](/code/files/domains/settings/index.php.md)
- [domains/table/index.php](/code/files/domains/table/index.php.md)
- [domains/view.php](/code/files/domains/view.php.md)
- [files/help.php](/code/files/files/help.php.md)
- [files/index.php](/code/files/files/index.php.md)
- [files/settings/index.php](/code/files/files/settings/index.php.md)
- [forms/approvals.php](/code/files/forms/approvals.php.md)
- [forms/collection.php](/code/files/forms/collection.php.md)
- [forms/fill.php](/code/files/forms/fill.php.md)
- [forms/help.php](/code/files/forms/help.php.md)
- [forms/help/_top.php](/code/files/forms/help/_top.php.md)
- [forms/settings/index.php](/code/files/forms/settings/index.php.md)
- [forms/submissions.php](/code/files/forms/submissions.php.md)
- [knowledge/assistant/index.php](/code/files/knowledge/assistant/index.php.md)
- [knowledge/help.php](/code/files/knowledge/help.php.md)
- [knowledge/help/_top.php](/code/files/knowledge/help/_top.php.md)
- [knowledge/index.php](/code/files/knowledge/index.php.md)
- [knowledge/review/index.php](/code/files/knowledge/review/index.php.md)
- [knowledge/settings/index.php](/code/files/knowledge/settings/index.php.md)
- [lms/editor.php](/code/files/lms/editor.php.md)
- [lms/help.php](/code/files/lms/help.php.md)
- [lms/index.php](/code/files/lms/index.php.md)
- [lms/my-courses.php](/code/files/lms/my-courses.php.md)
- [lms/native-player.php](/code/files/lms/native-player.php.md)
- [lms/player.php](/code/files/lms/player.php.md)
- [morning-checks/help.php](/code/files/morning-checks/help.php.md)
- [morning-checks/index.php](/code/files/morning-checks/index.php.md)
- [morning-checks/settings/index.php](/code/files/morning-checks/settings/index.php.md)
- [network-mapper/diagram.php](/code/files/network-mapper/diagram.php.md)
- [network-mapper/help.php](/code/files/network-mapper/help.php.md)
- [network-mapper/index.php](/code/files/network-mapper/index.php.md)
- [process-mapper/help.php](/code/files/process-mapper/help.php.md)
- [process-mapper/index.php](/code/files/process-mapper/index.php.md)
- [process-mapper/settings/index.php](/code/files/process-mapper/settings/index.php.md)
- [projects/capacity.php](/code/files/projects/capacity.php.md)
- [projects/index.php](/code/files/projects/index.php.md)
- [projects/settings/index.php](/code/files/projects/settings/index.php.md)
- [projects/tutorial.php](/code/files/projects/tutorial.php.md)
- [projects/view.php](/code/files/projects/view.php.md)
- [reporting/help.php](/code/files/reporting/help.php.md)
- [reporting/index.php](/code/files/reporting/index.php.md)
- [reporting/intune/index.php](/code/files/reporting/intune/index.php.md)
- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)
- [reporting/packs/index.php](/code/files/reporting/packs/index.php.md)
- [reporting/tickets/index.php](/code/files/reporting/tickets/index.php.md)
- [service-status/help.php](/code/files/service-status/help.php.md)
- [service-status/index.php](/code/files/service-status/index.php.md)
- [service-status/settings/index.php](/code/files/service-status/settings/index.php.md)
- [software/dashboard/index.php](/code/files/software/dashboard/index.php.md)
- [software/dashboard/library.php](/code/files/software/dashboard/library.php.md)
- [software/help.php](/code/files/software/help.php.md)
- [software/index.php](/code/files/software/index.php.md)
- [software/licences/index.php](/code/files/software/licences/index.php.md)
- [software/settings/index.php](/code/files/software/settings/index.php.md)
- [system/analysts/index.php](/code/files/system/analysts/index.php.md)
- [system/api/docs.php](/code/files/system/api/docs.php.md)
- [system/api/index.php](/code/files/system/api/index.php.md)
- [system/branding/index.php](/code/files/system/branding/index.php.md)
- [system/colours/index.php](/code/files/system/colours/index.php.md)
- [system/companies/index.php](/code/files/system/companies/index.php.md)
- [system/date-formats/index.php](/code/files/system/date-formats/index.php.md)
- [system/db-verify/index.php](/code/files/system/db-verify/index.php.md)
- [system/demo-data/index.php](/code/files/system/demo-data/index.php.md)
- [system/docker/index.php](/code/files/system/docker/index.php.md)
- [system/email-routing-test/index.php](/code/files/system/email-routing-test/index.php.md)
- [system/encryption/index.php](/code/files/system/encryption/index.php.md)
- [system/help/index.php](/code/files/system/help/index.php.md)
- [system/help/_top.php](/code/files/system/help/_top.php.md)
- [system/index.php](/code/files/system/index.php.md)
- [system/modules/index.php](/code/files/system/modules/index.php.md)
- [system/security/index.php](/code/files/system/security/index.php.md)
- [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)
- [system/sso/index.php](/code/files/system/sso/index.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)
- [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)
- [tasks/calendar/index.php](/code/files/tasks/calendar/index.php.md)
- [tasks/help.php](/code/files/tasks/help.php.md)
- [tasks/index.php](/code/files/tasks/index.php.md)
- [tasks/settings/index.php](/code/files/tasks/settings/index.php.md)
- [tasks/table/index.php](/code/files/tasks/table/index.php.md)
- [tasks/timeline/index.php](/code/files/tasks/timeline/index.php.md)
- [tickets/activity.php](/code/files/tickets/activity.php.md)
- [tickets/calendar.php](/code/files/tickets/calendar.php.md)
- [tickets/csat/index.php](/code/files/tickets/csat/index.php.md)
- [tickets/dashboard/index.php](/code/files/tickets/dashboard/index.php.md)
- [tickets/dashboard/library.php](/code/files/tickets/dashboard/library.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)
- [tickets/manager-access.php](/code/files/tickets/manager-access.php.md)
- [tickets/rota.php](/code/files/tickets/rota.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)
- [tickets/triage/index.php](/code/files/tickets/triage/index.php.md)
- [tickets/users.php](/code/files/tickets/users.php.md)
- [war-room/help.php](/code/files/war-room/help.php.md)
- [war-room/index.php](/code/files/war-room/index.php.md)
- [war-room/settings/index.php](/code/files/war-room/settings/index.php.md)
- [watchtower/help.php](/code/files/watchtower/help.php.md)
- [watchtower/index.php](/code/files/watchtower/index.php.md)
- [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)
- [workflow/editor.php](/code/files/workflow/editor.php.md)
- [workflow/executions.php](/code/files/workflow/executions.php.md)
- [workflow/help-ssl.php](/code/files/workflow/help-ssl.php.md)
- [workflow/help-webhooks.php](/code/files/workflow/help-webhooks.php.md)
- [workflow/help.php](/code/files/workflow/help.php.md)
- [workflow/index.php](/code/files/workflow/index.php.md)
- [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

