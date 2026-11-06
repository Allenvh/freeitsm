# DateFmt

> Class in [includes/timezone.php](/code/files/includes/timezone.php.md), line 156

Per-user date & time FORMAT (GH #105).

Deliberately SEPARATE from Tz above, because they answer different questions:
  Tz      -> WHICH INSTANT      ('14:30' vs '15:30')
  DateFmt -> WHAT IT LOOKS LIKE ('14:30' vs '2:30 PM')
Changing one must never move the other. DateFmt receives a datetime that Tz
has already placed in the right zone and only decides how to write it down.

Format is NOT inferred from the interface language. German speakers at a UK
MSP may want German labels and 25/08/2026, and inside one country people
disagree about 25/08/2026 vs 25.08.2026 vs 25 Aug 2026. So we ask.

Resolution order mirrors Tz::init():
  1. The analyst's `date_format` / `time_format` user preference
  2. The install-wide system_settings row. No UI surfaces this yet - it is the
     fallback the self-service portal needs, since portal users are not
     analysts and have no preferences to read.
  3. The built-in default, chosen to reproduce what the app rendered BEFORE
     this feature existed, so no existing install sees a date change.

The stored value is a KEY ('dmy_dot'), never a pattern string - an unvalidated
free-text pattern is a footgun, and PHP `date()` and JS Intl share no syntax.
Both renderers consume the SAME token template below, so they cannot drift.

## Methods

| Method | What it does |
|---|---|
| [init()](#init) | Resolve both formats for this request. Safe without a DB - falls back to the defaults. Normally reached via Tz::init(), which passes its own connection; call it directly only in a context that has no Tz. |
| [apply()](#apply) | Accept a candidate value for one of the two keys, ignoring blanks and unknown values. |
| [dateKey()](#datekey) | The effective date-format key. |
| [timeKey()](#timekey) | The effective time-format key. Lazily resolves - see `dateKey()`. |
| [months()](#months) | Ordered month names. `$short` picks the abbreviated set. |
| [weekdays()](#weekdays) | Ordered weekday names, MONDAY FIRST (index 0 = Monday). |
| [names()](#names) | The translated names in one calendar group (common.calendar.<group>, or <group>_short), in the order of `$keys` - a missing string falls back to the capitalised English key rather than a dotted key path. |
| [render()](#render) | Render a DateTime through a token template. The single PHP-side renderer. |
| [jsPayload()](#jspayload) | Everything the browser needs to render dates itself, published by Tz::scriptTag(). Kept here so the PHP and JS renderers read one source. |

### init

*public static* - line 207

Resolve both formats for this request. Safe without a DB - falls back to the defaults. Normally reached via Tz::init(), which passes its own connection; call it directly only in a context that has no Tz.

```php
function init(?PDO $existing = null)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$existing` | `?PDO` | `null` | a connection to reuse; null opens one (or keeps the defaults when none can be opened) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 221 | `DateFmt::init` | read [system_settings](/code/tables/system_settings.md) |  |
| 231 | `DateFmt::init` | read [user_preferences](/code/tables/user_preferences.md) |  |

**Called from:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

### apply

*private static* - line 252

Accept a candidate value for one of the two keys, ignoring blanks and unknown values.

```php
function apply($key, $value)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` |  |  | 'date_format' or 'time_format'; any other key is ignored |
| `$value` |  |  | a key of DATE_TEMPLATES or TIME_TEMPLATES; null, '' or unknown values are ignored |

### dateKey

*public static* - line 269

The effective date-format key.

Lazily RESOLVES rather than lazily defaulting. The difference matters: the
first live run of the settings page published the built-in default to the
browser while the database said dmy_dot, because nothing had called `init()`
and the accessor quietly answered with the default. A wrong format that
looks like a deliberate choice is invisible; resolving here means a
context nobody thought about - a cron, an email template, a PDF - still
gets the truth.

```php
function dateKey()
```

**Called from:** [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md), [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

### timeKey

*public static* - line 275

The effective time-format key. Lazily resolves - see `dateKey()`.

```php
function timeKey()
```

**Called from:** [includes/timezone.php](/code/files/includes/timezone.php.md)

### months

*public static* - line 297

Ordered month names. `$short` picks the abbreviated set.

`$inDate` asks for the form a month takes WHEN A DAY NUMBER IS BESIDE IT.
In English there is no difference, but Slavic languages inflect: Russian
standing alone is "март", inside a date it is "5 марта"; Polish
"marzec" becomes "5 marca"; Ukrainian "березень" becomes "5 березня".
Rendering the standalone form in a date reads plainly wrong to a native
speaker, and it is a full month name so the abbreviation cannot hide it.

A locale supplies 'months_in_date' only if it needs one; everything else
falls through to 'months' and is unaffected.

```php
function months($short = false, $inDate = false)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$short` |  | `false` | true for the abbreviated names |
| `$inDate` |  | `false` | true for the form used beside a day number (full names only; ignored when `$short`) |

### weekdays

*public static* - line 314

Ordered weekday names, MONDAY FIRST (index 0 = Monday).

```php
function weekdays($short = false)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$short` |  | `false` | true for the abbreviated names |

**Called from:** [includes/timezone.php](/code/files/includes/timezone.php.md), [morning-checks/index.php](/code/files/morning-checks/index.php.md)

### names

*private static* - line 332

The translated names in one calendar group (common.calendar.<group>, or <group>_short), in the order of `$keys` - a missing string falls back to the capitalised English key rather than a dotted key path.

```php
function names($group, $short, array $keys, $optional = false)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$group` |  |  | the calendar group: 'months', 'weekdays' or 'months_in_date' |
| `$short` |  |  | true for the abbreviated set (<group>_short) |
| `$keys` | `array` |  | the English keys in display order, e.g. 'january'..'december' |
| `$optional` |  | `false` | When true, return NULL if the locale does not define this group at all, rather than falling back. Used by months_in_date, which most languages do not need. |

### render

*public static* - line 376

Render a DateTime through a token template. The single PHP-side renderer.

```php
function render(DateTime $dt, $template)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dt` | `DateTime` |  | the moment to render, already in the display zone |
| `$template` |  |  | a token template such as 'DD MON YYYY' (tokens: MONTH YYYY MON DD MM YY mi HH D h A) |

**Called from:** [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md), [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [morning-checks/index.php](/code/files/morning-checks/index.php.md), [system/date-formats/index.php](/code/files/system/date-formats/index.php.md), [system/preferences/index.php](/code/files/system/preferences/index.php.md)

### jsPayload

*public static* - line 408

Everything the browser needs to render dates itself, published by Tz::scriptTag(). Kept here so the PHP and JS renderers read one source.

```php
function jsPayload()
```

**Called from:** [includes/timezone.php](/code/files/includes/timezone.php.md)

