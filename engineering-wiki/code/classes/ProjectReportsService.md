# ProjectReportsService

> Class in [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), line 30

## Methods

| Method | What it does |
|---|---|
| [ready()](#ready) | True when the project_reports table exists (after Database Verification). |
| [listFor()](#listfor) | Reports (not briefings), newest first, with who wrote and approved them. |
| [latestBriefing()](#latestbriefing) | The project's most recent AI briefing, or null. |
| [shape()](#shape) | A project_reports row as the API returns it. |
| [briefing()](#briefing) | The Overview's briefing: the latest, or a fresh one if it is older than the cooldown. |
| [draftWithAi()](#draftwithai) | Draft a report with the AI. Saved as a draft for a person to edit and approve. |
| [save()](#save) | Write a report by hand (no id) or edit a draft (id). |
| [approve()](#approve) | Approve a draft. The project manager's, the creator's or a Projects manager's. |
| [delete()](#delete) | Delete a draft (anyone who may change the project) or an approved report (those who may approve). |
| [scheduleReady()](#scheduleready) | Have the 3.3.0 columns arrived (projects.report_schedule, project_reports.sent_*)? |
| [schedule()](#schedule) | The project's schedule: {schedule: off\|weekly\|fortnightly\|monthly, kind}. |
| [setSchedule()](#setschedule) | Set it - anyone who may change the project. |
| [recipients()](#recipients) | Who a report can be sent to from the project itself: its members with an email address - analysts, the analysts in a member team, and People - and the project manager. Each once, by address. |
| [send()](#send) | Email an APPROVED report (3.3.0) - the people who may approve it may send it, to any addresses (the page offers the project's members). Sent one by one through the system mailbox (ssSendSystemEmail, logged as "Project report"). Returns ['sent' => [...], 'failed' => [...]]; what was sent is recorded on the report and in the history. |
| [emailHtml()](#emailhtml) | The email: the report as the page shows it (the same three marks), in plain inline styles. |
| [markdownHtml()](#markdownhtml) | Escape, then headings, bullets and **bold** - the marks the AI is told to use (projects-reports.js `md()`). |
| [runSchedules()](#runschedules) | Scheduled drafts (3.3.0), from `projectAlertsScan()`: each ACTIVE project with a schedule gets one draft per period - weekly (ISO week), fortnightly (each even-numbered ISO week's pair) or monthly - drafted by the AI when it is set up, otherwise a draft holding the facts to write from. Never approved by itself: the project manager is told (project.report_drafted) and approves, edits or deletes it. One per period on the ledger, however often the scan runs. |
| [periodKey()](#periodkey) | This period's ledger fingerprint, or null when this is not a drafting period (fortnightly, odd weeks). |
| [draftScheduled()](#draftscheduled) | One scheduled draft: the AI's when it is set up and answers, otherwise the facts. |
| [load()](#load) | Loads one report (not a briefing) of the project; throws not found otherwise. |

### ready

*public static* - line 36

True when the project_reports table exists (after Database Verification).

```php
function ready(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `ProjectReportsService::ready` | read [project_reports](/code/tables/project_reports.md) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### listFor

*public static* - line 42

Reports (not briefings), newest first, with who wrote and approved them.

```php
function listFor(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 | `ProjectReportsService::listFor` | read [project_reports](/code/tables/project_reports.md), read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### latestBriefing

*public static* - line 60

The project's most recent AI briefing, or null.

```php
function latestBriefing(PDO $conn, int $projectId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `ProjectReportsService::latestBriefing` | read [project_reports](/code/tables/project_reports.md), read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### shape

*private static* - line 77

A project_reports row as the API returns it.

```php
function shape(array $r): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a project_reports row joined with the created_by / updated_by / approved_by names |

### briefing

*public static* - line 95

The Overview's briefing: the latest, or a fresh one if it is older than the cooldown.

```php
function briefing(PDO $conn, ActorContext $ctx, int $projectId, bool $refresh): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$refresh` | `bool` |  | true to ask the AI for a new briefing unless the latest is under 10 minutes old; false returns the latest |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `ProjectReportsService::briefing` | **delete** [project_reports](/code/tables/project_reports.md) |  |
| 105 | `ProjectReportsService::briefing` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `ai_model`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### draftWithAi

*public static* - line 117

Draft a report with the AI. Saved as a draft for a person to edit and approve.

```php
function draftWithAi(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    string $kind,
    int $days = 14
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the report kind: 'highlight', 'exception', 'checkpoint' or 'closure' |
| `$days` | `int` | `14` | how many days back the facts cover, clamped to 1..90 |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 126 | `ProjectReportsService::draftWithAi` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `ai_model`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### save

*public static* - line 140

Write a report by hand (no id) or edit a draft (id).

```php
function save(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (an existing draft to edit; absent or 0 writes a new one), title (required, up to 200), body, kind (new reports only, default 'highlight') |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 154 | `ProjectReportsService::save` | **update** [project_reports](/code/tables/project_reports.md) (`title`, `body`, `ai_edited`, `updated_by_id`, `updated_datetime`) |  |
| 160 | `ProjectReportsService::save` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### approve

*public static* - line 173

Approve a draft. The project manager's, the creator's or a Projects manager's.

```php
function approve(PDO $conn, ActorContext $ctx, int $projectId, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the report (project_reports.id) to approve |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 182 | `ProjectReportsService::approve` | **update** [project_reports](/code/tables/project_reports.md) (`status`, `approved_by_id`, `approved_datetime`) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### delete

*public static* - line 192

Delete a draft (anyone who may change the project) or an approved report (those who may approve).

```php
function delete(PDO $conn, ActorContext $ctx, int $projectId, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the report (project_reports.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 203 | `ProjectReportsService::delete` | **delete** [project_reports](/code/tables/project_reports.md) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### scheduleReady

*public static* - line 215

Have the 3.3.0 columns arrived (projects.report_schedule, project_reports.sent_*)?

```php
function scheduleReady(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 219 | `ProjectReportsService::scheduleReady` | read [projects](/code/tables/projects.md) |  |
| 219 | `ProjectReportsService::scheduleReady` | read [project_reports](/code/tables/project_reports.md) |  |

### schedule

*public static* - line 226

The project's schedule: {schedule: off|weekly|fortnightly|monthly, kind}.

```php
function schedule(PDO $conn, int $projectId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 229 | `ProjectReportsService::schedule` | read [projects](/code/tables/projects.md) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### setSchedule

*public static* - line 241

Set it - anyone who may change the project.

```php
function setSchedule(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    string $schedule,
    string $kind
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$schedule` | `string` |  | 'off', 'weekly', 'fortnightly' or 'monthly' |
| `$kind` | `string` |  | the kind each scheduled draft is: 'highlight', 'checkpoint' or 'exception' |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 250 | `ProjectReportsService::setSchedule` | **update** [projects](/code/tables/projects.md) (`report_schedule`, `report_schedule_kind`) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### recipients

*public static* - line 259

Who a report can be sent to from the project itself: its members with an email address - analysts, the analysts in a member team, and People - and the project manager. Each once, by address.

```php
function recipients(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 268 | `ProjectReportsService::recipients` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 273 | `ProjectReportsService::recipients` | read [analyst_teams](/code/tables/analyst_teams.md), read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### send

*public static* - line 294

Email an APPROVED report (3.3.0) - the people who may approve it may send it, to any addresses (the page offers the project's members). Sent one by one through the system mailbox (ssSendSystemEmail, logged as "Project report"). Returns ['sent' => [...], 'failed' => [...]]; what was sent is recorded on the report and in the history.

```php
function send(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $id,
    array $emails,
    string $note = ''
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the approved report (project_reports.id) to send |
| `$emails` | `array` |  | addresses to send to; each entry may hold several split by spaces, commas or semicolons (at most 50) |
| `$note` | `string` | `''` | an optional covering note shown above the report, cut to 2000 characters |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 328 | `ProjectReportsService::send` | **update** [project_reports](/code/tables/project_reports.md) (`sent_datetime`, `sent_by_id`, `sent_to`) |  |

**Called from:** [api/projects/reports.php](/code/files/api/projects/reports.php.md)

### emailHtml

*public static* - line 343

The email: the report as the page shows it (the same three marks), in plain inline styles.

```php
function emailHtml(array $project, array $report, string $sender, string $note): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `array` |  | the project row (needs id and name) |
| `$report` | `array` |  | the project_reports row (title, body, approved_datetime) |
| `$sender` | `string` |  | the name shown as "sent by" |
| `$note` | `string` |  | the covering note; '' for none |

### markdownHtml

*public static* - line 361

Escape, then headings, bullets and **bold** - the marks the AI is told to use (projects-reports.js `md()`).

```php
function markdownHtml(string $text): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the report body in the AI's limited markdown (headings, bullets, **bold**) - untrusted, escaped here |

### runSchedules

*public static* - line 386

Scheduled drafts (3.3.0), from `projectAlertsScan()`: each ACTIVE project with a schedule gets one draft per period - weekly (ISO week), fortnightly (each even-numbered ISO week's pair) or monthly - drafted by the AI when it is set up, otherwise a draft holding the facts to write from. Never approved by itself: the project manager is told (project.report_drafted) and approves, edits or deletes it. One per period on the ledger, however often the scan runs.

```php
function runSchedules(PDO $conn, ?int $projectId = null): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 389 | `ProjectReportsService::runSchedules` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 395 | `ProjectReportsService::runSchedules` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

**Called from:** [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md)

### periodKey

*public static* - line 428

This period's ledger fingerprint, or null when this is not a drafting period (fortnightly, odd weeks).

```php
function periodKey(string $schedule, ?int $now = null): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$schedule` | `string` |  | 'weekly', 'fortnightly' or 'monthly'; anything else gives null |
| `$now` | `?int` | `null` | a Unix timestamp to work from; null means now |

### draftScheduled

*private static* - line 445

One scheduled draft: the AI's when it is set up and answers, otherwise the facts.

```php
function draftScheduled(PDO $conn, array $p): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$p` | `array` |  | the projects row (with report_schedule and report_schedule_kind) as loaded by `runSchedules()` |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 466 | `ProjectReportsService::draftScheduled` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `ai_model`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

### load

*private static* - line 479

Loads one report (not a briefing) of the project; throws not found otherwise.

```php
function load(PDO $conn, int $projectId, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the report (project_reports.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 481 | `ProjectReportsService::load` | read [project_reports](/code/tables/project_reports.md) |  |

