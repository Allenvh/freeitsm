# AzureDevOpsProvider

> Class in [includes/integrations/AzureDevOpsProvider.php](/code/files/includes/integrations/AzureDevOpsProvider.php.md), line 43

## Methods

| Method | What it does |
|---|---|
| [capabilities()](#capabilities) | The optional features Azure DevOps supports - everything except a priority list. |
| [baseUrl()](#baseurl) | The organisation (or on-prem collection) root, no trailing slash. |
| [apiUrl()](#apiurl) | An organisation-scoped API URL: `{base}/_apis/{path}`. |
| [projectUrl()](#projecturl) | A project-scoped API URL: `{base}/{project}/_apis/{path}`. |
| [browseUrl()](#browseurl) | The human-facing link stored on the link row and shown in the panel. |
| [devopsRequest()](#devopsrequest) | Auth is a Personal Access Token as HTTP Basic with an EMPTY username — `Authorization: Basic base64(":" + PAT)`. Azure DevOps ignores the username entirely; sending the user's email in it also works, which is why a wrong implementation can appear to succeed on a developer's own account and fail on a service account. |
| [patchRequest()](#patchrequest) | A JSON Patch write. Separated because the content type is the whole trick. |
| [extractError()](#extracterror) | An Azure DevOps error into something an analyst can act on. |
| [renderDoc()](#renderdoc) | Azure DevOps fields are HTML — both the description and comment bodies. `System.Description` is rendered as rich text on every work item form. |
| [bodyFieldFor()](#bodyfieldfor) | Which field holds the body for this work item type. |
| [buildCreatePatch()](#buildcreatepatch) | Build the JSON Patch document for a create. |
| [targetType()](#targettype) | Which work item type to create. |
| [addOp()](#addop) | One JSON Patch add operation against a field. |
| [trimTitle()](#trimtitle) | ⚠️ System.Title is capped at 255 characters and Azure DevOps rejects the whole create if it is longer — so a ticket with a very long subject would fail to escalate at all rather than arrive with a shortened title. |
| [createIssue()](#createissue) | Creates a work item of the target's type in the target project and returns it parsed. |
| [parseWorkItem()](#parseworkitem) | Azure DevOps' work item JSON → our normalised shape. |
| [stateCategory()](#statecategory) | A state NAME → its category, for this project's process and this type. |
| [mapStateCategory()](#mapstatecategory) | Azure DevOps' five state categories → our four. |
| [resolvedMeans()](#resolvedmeans) | The connection's answer for `Resolved`, validated against our closed set. |
| [readFields()](#readfields) | The fields worth asking for. Narrower than the default, which returns everything. |
| [fetchIssue()](#fetchissue) | Reads one work item by its numeric id and returns it parsed for the escalation panel. |
| [fetchIssues()](#fetchissues) | Batch read — one call for the whole watch list, because this is the poll cron's hot path. |
| [projectOf()](#projectof) | Comments are project-scoped even though work item ids are not, so we have to know which project the item lives in. Resolved from the item itself and cached, rather than requiring every caller to carry it. |
| [addComment()](#addcomment) | Posts a comment, rendered to HTML, on a work item and returns the new comment's id. |
| [fetchComments()](#fetchcomments) | Comments on one work item, normalised. |
| [parseComment()](#parsecomment) | One Azure DevOps comment → the fields the canonical comment_added event needs. |
| [htmlToText()](#htmltotext) | A comment's HTML → plain text for the internal note. |
| [addAttachment()](#addattachment) | Two steps, and the second is the one that is easy to miss. |
| [safeFilename()](#safefilename) | Azure DevOps takes the filename verbatim; strip anything path- or header-breaking. |
| [updateFields()](#updatefields) | ⚠️ A flat map of reference name => value, translated into `add` ops. |
| [pollChanges()](#pollchanges) | Which watched work items changed, and what was said on them. |
| [pollCutoff()](#pollcutoff) | The window boundary: the watermark, less a small overlap, and never more than the cap. Pure, so the capping rule is provable without a clock. |
| [wiqlTimestamp()](#wiqltimestamp) | WIQL is UTC when the literal says so. Format it that way, always. |
| [searchChangedSince()](#searchchangedsince) | Asks WIQL which of the watched work items changed since `$cutoff`, in chunks. |
| [testConnection()](#testconnection) | Confirm the credentials and capture who we are. |
| [listProjects()](#listprojects) | Lists the organisation's well-formed projects for the mapping dropdowns, a page of 100 at a time up to 1,000. |
| [listIssueTypes()](#listissuetypes) | The work item types this project's process defines. |
| [isInternalType()](#isinternaltype) | Types that exist for tooling rather than for people to be assigned work. |

### capabilities

*protected* - line 65

The optional features Azure DevOps supports - everything except a priority list.

```php
function capabilities(): array
```

### baseUrl

*protected* - line 84

The organisation (or on-prem collection) root, no trailing slash.

```php
function baseUrl(): string
```

### apiUrl

*protected* - line 101

An organisation-scoped API URL: `{base}/_apis/{path}`.

Work item reads are deliberately org-scoped rather than project-scoped —
a work item id is unique across the organisation, and scoping the read to
a project would mean knowing which project it lives in before we can look
it up, which the link row does not always carry.

```php
function apiUrl(string $path, array $query = [], string $version = self::API_VERSION): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the route under `_apis/`, e.g. 'wit/workitems/42'; a leading slash is dropped |
| `$query` | `array` | `[]` | query-string parameters; api-version is added and wins over any given |
| `$version` | `string` | `self::API_VERSION` | the api-version to send - one of the API_VERSION* constants |

### projectUrl

*protected* - line 115

A project-scoped API URL: `{base}/{project}/_apis/{path}`.

```php
function projectUrl(
    string $project,
    string $path,
    array $query = [],
    string $version = self::API_VERSION
): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the project name (or GUID); URL-encoded here |
| `$path` | `string` |  | the route under the project's `_apis/`; a leading slash is dropped |
| `$query` | `array` | `[]` | query-string parameters; api-version is added and wins over any given |
| `$version` | `string` | `self::API_VERSION` | the api-version to send - one of the API_VERSION* constants |

### browseUrl

*public* - line 128

The human-facing link stored on the link row and shown in the panel.

```php
function browseUrl(string $project, string $id): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the name of the project the work item lives in |
| `$id` | `string` |  | the numeric work item id |

### devopsRequest

*protected* - line 147

Auth is a Personal Access Token as HTTP Basic with an EMPTY username — `Authorization: Basic base64(":" + PAT)`. Azure DevOps ignores the username entirely; sending the user's email in it also works, which is why a wrong implementation can appear to succeed on a developer's own account and fail on a service account.

```php
function devopsRequest(
    string $method,
    string $url,
    ?array $json = null,
    ?string $contentType = null
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the HTTP method - 'GET', 'POST' or 'PATCH' |
| `$url` | `string` |  | the full URL, from `apiUrl()` or `projectUrl()` |
| `$json` | `?array` | `null` | the body to JSON-encode; null sends no body |
| `$contentType` | `?string` | `null` | the body's Content-Type; null means application/json |

### patchRequest

*protected* - line 178

A JSON Patch write. Separated because the content type is the whole trick.

```php
function patchRequest(string $method, string $url, array $ops): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | 'POST' to create a work item, 'PATCH' to change one |
| `$url` | `string` |  | the full URL, from `apiUrl()` or `projectUrl()` |
| `$ops` | `array` |  | the JSON Patch operations, e.g. built by `addOp()` |

### extractError

*protected* - line 195

An Azure DevOps error into something an analyst can act on.

⚠️ A 203 is NOT a success here. When a PAT is invalid or expired, Azure
DevOps often answers 203 with an HTML sign-in page rather than 401 with
JSON — so "the connection test passed but nothing works" is the symptom of
treating any 2xx as fine. This is why `devopsRequest()` accepts 200-299 but
`testConnection()` insists on a parsed identity.

```php
function extractError(int $code, string $rawBody): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `int` |  | the HTTP status code |
| `$rawBody` | `string` |  | the response body as received - JSON, or an HTML sign-in page |

### renderDoc

*public* - line 217

Azure DevOps fields are HTML — both the description and comment bodies. `System.Description` is rendered as rich text on every work item form.

```php
function renderDoc(IssueDoc $doc)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `IssueDoc` |  | the description or comment to render as HTML |

### bodyFieldFor

*protected* - line 241

Which field holds the body for this work item type.

⚠️ This is not a detail. In the stock **Agile** process a Bug's form shows
*Repro Steps* (`Microsoft.VSTS.TCM.ReproSteps`) and does NOT show
`System.Description`. Writing the description to System.Description on a
Bug therefore succeeds, returns 200, and produces a work item that looks
EMPTY to the developer who opens it. Nothing errors; the text is simply
somewhere the form does not display.

So the type's own field list decides, and the answer is cached per
(project, type) because it cannot change without an admin editing the
process.

```php
function bodyFieldFor(string $project, string $type): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the project name |
| `$type` | `string` |  | the work item type name, e.g. 'Bug' |

### buildCreatePatch

*protected* - line 278

Build the JSON Patch document for a create.

Separate and protected so the patch shape is provable without a live org —
this is the single most provider-specific thing in the file.

```php
function buildCreatePatch(array $target, string $summary, IssueDoc $body, array $fields = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$target` | `array` |  | array{project:string, work_item_type?:string, issue_type?:string, area_path?:string, iteration_path?:string} - the escalation target |
| `$summary` | `string` |  | the title; `trimTitle()` collapses whitespace and caps it at 255 characters |
| `$body` | `IssueDoc` |  | the description, written to the type's body field (see `bodyFieldFor()`) |
| `$fields` | `array` | `[]` | mapped fields, field reference name => value; keys that are not strings are skipped |

### targetType

*protected* - line 323

Which work item type to create.

⚠️ TWO KEYS, and this is not belt-and-braces. The core is tracker-neutral
and speaks of an `issue_type` — that is what escalate_ticket.php and the
workflow action both send, because "issue type" is the word the mapping
screen uses for every provider. `work_item_type` is Azure DevOps' own name
for the same thing, and is what the contract's docblock shows for this
provider.

Reading only one of them is a bug that CANNOT be seen: an escalation
requesting a Bug quietly produced a Task, with no error anywhere, because
the default silently applied. That is exactly what happened on the first
live run. Accept both, and default only when neither was given.

```php
function targetType(array $target): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$target` | `array` |  | the escalation target; its work_item_type, else issue_type, is read |

### addOp

*protected* - line 340

One JSON Patch add operation against a field.

```php
function addOp(string $refName, $value): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$refName` | `string` |  | the field reference name, e.g. 'System.Title' |
| `$value` |  |  | the value to set |

### trimTitle

*protected* - line 352

⚠️ System.Title is capped at 255 characters and Azure DevOps rejects the whole create if it is longer — so a ticket with a very long subject would fail to escalate at all rather than arrive with a shortened title.

```php
function trimTitle(string $summary): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$summary` | `string` |  | the ticket subject |

### createIssue

*public* - line 369

Creates a work item of the target's type in the target project and returns it parsed.

Throws when no project is given or Azure DevOps returns no id.

```php
function createIssue(array $target, string $summary, IssueDoc $body, array $fields = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$target` | `array` |  | the escalation target, shaped as for `buildCreatePatch()` - project is required |
| `$summary` | `string` |  | the ticket subject, used as the title |
| `$body` | `IssueDoc` |  | the description |
| `$fields` | `array` | `[]` | mapped fields, field reference name => value |

### parseWorkItem

*protected* - line 402

Azure DevOps' work item JSON → our normalised shape.

⚠️ `external_key` is the plain numeric id. Azure DevOps has no
human-readable key like Jira's OPS-123 — the id IS what people quote.

```php
function parseWorkItem(array $wi): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$wi` | `array` |  | one work item as Azure DevOps returns it - id plus fields keyed by reference name |

### stateCategory

*protected* - line 436

A state NAME → its category, for this project's process and this type.

⚠️ Both the project and the type are required, and neither is padding.
States are defined per work item type within a process, and two types in
one project genuinely disagree: in the stock Agile process "Resolved" is
category `Resolved` on a Bug and `InProgress` on a User Story.

Cached per (project, type) — a process only changes when an admin edits it.

```php
function stateCategory(string $project, string $type, string $state): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the project name |
| `$type` | `string` |  | the work item type name, e.g. 'Bug' |
| `$state` | `string` |  | the state name, e.g. 'Resolved'; matched without regard to case |

### mapStateCategory

*protected* - line 487

Azure DevOps' five state categories → our four.

| Azure DevOps | ours |
|--------------|------|
| Proposed     | todo |
| InProgress   | in_progress |
| Resolved     | **the connection decides** |
| Completed    | done |
| Removed      | cancelled |

🔑 `Resolved` means "a developer says it is fixed, nobody has verified it".
Whether that is *done* from a service desk's point of view is a genuine
judgement, not a technical fact:

  - treat it as **in progress** and the requester is told nothing until
    someone verifies — cautious, and the default, because telling somebody
    their problem is fixed when it is not is the worse failure;
  - treat it as **done** and the requester hears as soon as the developer
    marks it resolved — faster, right for teams who close on resolve.

Jira has no equivalent: its "Won't Do" lands in `done` and there is no
fifth category, which is why this setting exists only here.

```php
function mapStateCategory(string $category): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$category` | `string` |  | the Azure DevOps state category - Proposed, InProgress, Resolved, Completed or Removed |

### resolvedMeans

*public* - line 500

The connection's answer for `Resolved`, validated against our closed set.

```php
function resolvedMeans(): string
```

### readFields

*protected* - line 509

The fields worth asking for. Narrower than the default, which returns everything.

```php
function readFields(): string
```

### fetchIssue

*public* - line 519

Reads one work item by its numeric id and returns it parsed for the escalation panel.

```php
function fetchIssue(string $externalId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric work item id; anything else throws |

### fetchIssues

*public* - line 537

Batch read — one call for the whole watch list, because this is the poll cron's hot path.

⚠️ Capped at 200 ids per call by the API. Asking for 201 is a 400, not a
truncated list, so the chunking is load-bearing rather than politeness.

```php
function fetchIssues(array $externalIds): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalIds` | `array` |  | work item ids to read; ones that are not numeric are dropped |

### projectOf

*protected* - line 573

Comments are project-scoped even though work item ids are not, so we have to know which project the item lives in. Resolved from the item itself and cached, rather than requiring every caller to carry it.

```php
function projectOf(string $externalId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric work item id |

### addComment

*public* - line 593

Posts a comment, rendered to HTML, on a work item and returns the new comment's id.

```php
function addComment(string $externalId, IssueDoc $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric work item id |
| `$body` | `IssueDoc` |  | the comment, rendered to HTML |

### fetchComments

*public* - line 609

Comments on one work item, normalised.

```php
function fetchComments(string $externalId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric work item id |

**Returns:** array [['comment_id'=>…, 'comment_body'=>plain text, 'author_identity'=>…, 'author_name'=>…, 'created_ts'=>?int], …]

### parseComment

*protected* - line 636

One Azure DevOps comment → the fields the canonical comment_added event needs.

⚠️ author_identity is `createdBy.id`, NOT `createdBy.descriptor`. Echo
suppression compares this against what `testConnection()` returned, and the
two endpoints report descriptors in DIFFERENT formats for the same person
— `connectionData` gives a ClaimsIdentity string while a comment gives
`aad.<base64>`. Comparing descriptors would never match, so every one of
our own comments would be re-imported as if a developer had written it.
The GUID in `id` is identical across both.

```php
function parseComment(array $c): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | one entry of the comments response - id, text (HTML), createdBy, createdDate |

### htmlToText

*protected* - line 660

A comment's HTML → plain text for the internal note.

⚠️ Uses the shared reducer rather than `strip_tags()`, for the reason
documented on `integrationsHtmlToIssueText()`: strip_tags removes the TAGS
but keeps the CONTENT of <style>, so a pasted-in styled block arrives as
a wall of CSS.

```php
function htmlToText(string $html): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$html` | `string` |  | a comment's text as HTML |

### addAttachment

*public* - line 681

Two steps, and the second is the one that is easy to miss.

Uploading returns a URL but attaches the file to NOTHING. The work item
only gains it when a second JSON Patch adds an `AttachedFile` relation —
so an implementation that stops after the upload reports success, and the
file exists in the org, invisible to everyone.

```php
function addAttachment(string $externalId, array $file): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric id of the work item to attach the file to |
| `$file` | `array` |  | ['data'=>binary, 'filename'=>string, 'content_type'=>string] |

### safeFilename

*protected* - line 731

Azure DevOps takes the filename verbatim; strip anything path- or header-breaking.

```php
function safeFilename(string $filename): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filename` | `string` |  | the file's original name |

### updateFields

*public* - line 750

⚠️ A flat map of reference name => value, translated into `add` ops.

`add` is correct rather than `replace` for Azure DevOps: on a field that
has no value yet, `replace` fails, whereas `add` both sets and overwrites.
The contract cannot express `remove`, so clearing a field is not supported
here — and that is a genuine limit rather than an oversight.

```php
function updateFields(string $externalId, array $fields): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric work item id |
| `$fields` | `array` |  | field reference name => value; each becomes an `add` op |

### pollChanges

*public* - line 783

Which watched work items changed, and what was said on them.

⚠️ Two things here are easy to get wrong and both fail quietly:

 1. **`timePrecision=true` is a QUERY parameter, not a body field.**
    Without it Azure DevOps refuses any WIQL carrying a time-of-day and
    the whole poll errors. The tempting "fix" is to drop to a date-only
    boundary — which silently re-reads a WHOLE DAY of comments on every
    run, because our watermark moves in minutes.
 2. **The timestamp must carry an explicit `Z`.** With one, Azure DevOps
    honours real UTC — verified against a live organisation, including a
    future-dated window correctly matching nothing. This is the OPPOSITE
    of Jira, where an absolute date is read in the Jira user's own
    timezone and forced us onto relative minutes.

```php
function pollChanges(?string $since, array $externalIds = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$since` | `?string` |  | UTC 'Y-m-d H:i:s' watermark (last_poll_watermark); null on the first run returns nothing |
| `$externalIds` | `array` | `[]` | ids of the work items this connection holds links to; non-numeric ones are dropped |

### pollCutoff

*protected* - line 831

The window boundary: the watermark, less a small overlap, and never more than the cap. Pure, so the capping rule is provable without a clock.

The overlap exists because a comment written in the same second the poll
ran would otherwise fall between two windows and never be seen; the same
comment arriving twice is harmless, as the comment map dedupes by id.

```php
function pollCutoff(int $sinceTs, ?int $nowTs = null): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sinceTs` | `int` |  | the watermark as a Unix timestamp |
| `$nowTs` | `?int` | `null` | the current time as a Unix timestamp; null means `time()` |

### wiqlTimestamp

*protected* - line 844

WIQL is UTC when the literal says so. Format it that way, always.

```php
function wiqlTimestamp(int $ts): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ts` | `int` |  | a Unix timestamp |

### searchChangedSince

*protected* - line 858

Asks WIQL which of the watched work items changed since `$cutoff`, in chunks.

A chunk that fails is skipped rather than failing the whole poll.

```php
function searchChangedSince(array $ids, int $cutoff): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ids` | `array` |  | numeric work item ids to check |
| `$cutoff` | `int` |  | a Unix timestamp; items changed at or after it are returned |

**Returns:** string[] ids of watched work items changed since `$cutoff`

### testConnection

*public* - line 891

Confirm the credentials and capture who we are.

⚠️ `connectionData` rejects a bare `api-version=7.0` and demands the
`-preview` suffix, unlike every other endpoint this file calls.

account_identity is half of echo suppression — see `parseComment()` for why
this must be the GUID and never the descriptor.

```php
function testConnection(): array
```

### listProjects

*public* - line 918

Lists the organisation's well-formed projects for the mapping dropdowns, a page of 100 at a time up to 1,000.

```php
function listProjects(): array
```

**Returns:** array [['key'=>'Support','name'=>'Support'], …]

### listIssueTypes

*public* - line 949

The work item types this project's process defines.

⚠️ Per-project, genuinely — two projects in one organisation on different
processes return different lists. Basic gives Issue/Epic/Task; Agile adds
Bug, User Story and Feature. Caching this across projects would be wrong.

```php
function listIssueTypes(string $project): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the project name, as `listProjects()` returns it in `key` |

### isInternalType

*protected* - line 970

Types that exist for tooling rather than for people to be assigned work.

```php
function isInternalType(string $name): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the work item type name |

