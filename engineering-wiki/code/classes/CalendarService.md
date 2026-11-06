# CalendarService

> Class in [includes/services/calendar.php](/code/files/includes/services/calendar.php.md), line 29

## Methods

| Method | What it does |
|---|---|
| [saveEvent()](#saveevent) | Create (no id) or update (id present) an event. Returns ['id','created']. |
| [deleteEvent()](#deleteevent) | Delete an event. 404 if missing, 409 if generated. Returns the id. |
| [saveCategory()](#savecategory) | Create (no id) or update (id present) a category. Returns ['id','created']. |
| [deleteCategory()](#deletecategory) | Delete a category — refused (409) while events still use it. Returns the id. |
| [loadEventRow()](#loadeventrow) | Loads one calendar event; throws not found when it does not exist. |
| [guardGenerated()](#guardgenerated) | Generated rows belong to their sync — never edited/deleted through here. |
| [collectEventFields()](#collecteventfields) | Collect + validate the writable event fields (mirrors the API resource's apiCalendarEventFields). On update, absent keys fall back to the current row. Datetimes are naive server-local (Z/offset rejected). |
| [parseNaiveDatetime()](#parsenaivedatetime) | Parse a NAIVE local datetime ("YYYY-MM-DD HH:MM[:SS]", T separator OK, date-only OK). Throws on timezone designators — naive-local by design. (Throwing twin of the resource's apiParseNaiveDatetime.) |

### saveEvent

*public static* - line 40

Create (no id) or update (id present) an event. Returns ['id','created'].

```php
function saveEvent(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new), title, description, category_id, start_at, end_at, all_day, location, contract_id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `CalendarService::saveEvent` | **update** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `location`, `contract_id`, `updated_at`) |  |
| 82 | `CalendarService::saveEvent` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `location`, `contract_id`, `created_by`) |  |

**Called from:** [api/calendar/save_event.php](/code/files/api/calendar/save_event.php.md), [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md)

### deleteEvent

*public static* - line 100

Delete an event. 404 if missing, 409 if generated. Returns the id.

```php
function deleteEvent(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the calendar_events row to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `CalendarService::deleteEvent` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |

**Called from:** [api/calendar/delete_event.php](/code/files/api/calendar/delete_event.php.md), [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md)

### saveCategory

*public static* - line 118

Create (no id) or update (id present) a category. Returns ['id','created'].

```php
function saveCategory(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new), name, color (#rrggbb, default #ef6c00), description, is_active |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 134 | `CalendarService::saveCategory` | **update** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `description`, `is_active`, `updated_at`) |  |
| 141 | `CalendarService::saveCategory` | **insert** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `description`, `is_active`) |  |

**Called from:** [api/calendar/save_category.php](/code/files/api/calendar/save_category.php.md)

### deleteCategory

*public static* - line 153

Delete a category — refused (409) while events still use it. Returns the id.

```php
function deleteCategory(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the calendar_categories row to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 158 | `CalendarService::deleteCategory` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 164 | `CalendarService::deleteCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 165 | `CalendarService::deleteCategory` | **delete** [calendar_categories](/code/tables/calendar_categories.md) |  |

**Called from:** [api/calendar/delete_category.php](/code/files/api/calendar/delete_category.php.md)

### loadEventRow

*private static* - line 183

Loads one calendar event; throws not found when it does not exist.

```php
function loadEventRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the calendar_events row to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 185 | `CalendarService::loadEventRow` | read [calendar_events](/code/tables/calendar_events.md) |  |

### guardGenerated

*private static* - line 199

Generated rows belong to their sync — never edited/deleted through here.

```php
function guardGenerated(array $event): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `array` |  | the calendar_events row; a non-empty source means a sync made it |

### collectEventFields

*private static* - line 214

Collect + validate the writable event fields (mirrors the API resource's apiCalendarEventFields). On update, absent keys fall back to the current row. Datetimes are naive server-local (Z/offset rejected).

```php
function collectEventFields(
    PDO $conn,
    array $in,
    array $current = [],
    ?ActorContext $ctx = null
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields; only keys present are taken from here |
| `$current` | `array` | `[]` | the stored event row on update, for absent keys; [] on create |
| `$ctx` | `?ActorContext` | `null` | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 240 | `CalendarService::collectEventFields` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 253 | `CalendarService::collectEventFields` | read [contracts](/code/tables/contracts.md) |  |

### parseNaiveDatetime

*private static* - line 273

Parse a NAIVE local datetime ("YYYY-MM-DD HH:MM[:SS]", T separator OK, date-only OK). Throws on timezone designators — naive-local by design. (Throwing twin of the resource's apiParseNaiveDatetime.)

```php
function parseNaiveDatetime($value, string $field): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the date or date-time from the request, server-local with no zone; null or '' gives null |
| `$field` | `string` |  | the request field name, used in the error message |

