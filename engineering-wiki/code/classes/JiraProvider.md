# JiraProvider

> Class in [includes/integrations/JiraProvider.php](/code/files/includes/integrations/JiraProvider.php.md), line 40

## Methods

| Method | What it does |
|---|---|
| [capabilities()](#capabilities) | The optional features the Jira connector supports, priorities included. |
| [flavour()](#flavour) | 'cloud' \| 'server'. Sniffed at testConnection, cached in credentials. |
| [isCloud()](#iscloud) | True when the connection is Jira Cloud rather than Server / Data Center. |
| [apiVersion()](#apiversion) | REST API version this flavour speaks. |
| [baseUrl()](#baseurl) | The connection's Jira address without a trailing slash. |
| [apiUrl()](#apiurl) | A REST API address for `$path`, on API version 3 (Cloud) or 2 (Server / Data Center). |
| [browseUrl()](#browseurl) | The human-facing link we store on the link row and show in the panel. |
| [jiraRequest()](#jirarequest) | Auth differs by flavour: Cloud uses HTTP Basic with the user's email and an API token; Data Center uses a Bearer Personal Access Token. |
| [extractError()](#extracterror) | Turn a Jira error response into something an analyst can act on. |
| [renderDoc()](#renderdoc) | ADF (an array) on Cloud; wiki markup (a string) on Data Center. |
| [buildCreatePayload()](#buildcreatepayload) | Builds the JSON body for creating an issue - project, issue type, a one-line summary of up to 255 characters, the rendered description and any mapped fields. |
| [createIssue()](#createissue) | Creates an issue and returns it as the escalation panel needs it. |
| [parseIssue()](#parseissue) | Jira's issue JSON → our normalised shape. |
| [mapStatusCategory()](#mapstatuscategory) | Jira's statusCategory key → our closed set. |
| [fetchIssue()](#fetchissue) | Reads one issue's summary, status and assignee and returns it parsed. |
| [fetchIssues()](#fetchissues) | Batch read via JQL — one call for the whole watch list instead of one per issue, which matters because this is the poll cron's hot path. |
| [addComment()](#addcomment) | Posts a comment on an issue, rendered for this Jira flavour, and returns the new comment's id. |
| [fetchComments()](#fetchcomments) | Comments on one issue, newest first, normalised. |
| [parseComment()](#parsecomment) | One Jira comment → the fields the canonical comment_added event needs. |
| [commentBodyToText()](#commentbodytotext) | A comment body in whichever shape this flavour sends → plain text. |
| [adfToText()](#adftotext) | ADF → plain text. |
| [tidyText()](#tidytext) | Shared tidy-up for text pulled out of a tracker. |
| [pollChanges()](#pollchanges) | The inbound path until webhooks land: ask Jira what has been commented on since `$since` and return canonical `comment_added` events. |
| [lookbackMinutes()](#lookbackminutes) | How many minutes back to ask Jira about. Pure, so the capping rule is provable without a clock or a network. |
| [searchUpdatedSince()](#searchupdatedsince) | Which of our watched issues changed in the last `$minutes`. |
| [addAttachment()](#addattachment) | Upload one file to an issue. |
| [buildMultipart()](#buildmultipart) | A single-file multipart/form-data body, and the boundary that goes with it. |
| [testConnection()](#testconnection) | Confirm the credentials and — just as importantly — capture who we are. |
| [listProjects()](#listprojects) | Lists the Jira projects for the mapping dropdowns - paged on Cloud (up to 1,000), one call on Server / Data Center. |
| [listPriorities()](#listpriorities) | Every priority defined on the site, for the mapping screen's dropdown. |
| [listIssueTypes()](#listissuetypes) | Issue types available on a project. |

### capabilities

*protected* - line 46

The optional features the Jira connector supports, priorities included.

```php
function capabilities(): array
```

### flavour

*public* - line 61

'cloud' | 'server'. Sniffed at testConnection, cached in credentials.

```php
function flavour(): string
```

### isCloud

*protected* - line 76

True when the connection is Jira Cloud rather than Server / Data Center.

```php
function isCloud(): bool
```

### apiVersion

*protected* - line 82

REST API version this flavour speaks.

```php
function apiVersion(): int
```

### baseUrl

*protected* - line 88

The connection's Jira address without a trailing slash.

```php
function baseUrl(): string
```

### apiUrl

*protected* - line 98

A REST API address for `$path`, on API version 3 (Cloud) or 2 (Server / Data Center).

```php
function apiUrl(string $path): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the route under the REST base, e.g. 'issue/10042'; a leading slash is dropped |

### browseUrl

*public* - line 108

The human-facing link we store on the link row and show in the panel.

```php
function browseUrl(string $key): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the issue key, e.g. 'OPS-123' |

### jiraRequest

*protected* - line 123

Auth differs by flavour: Cloud uses HTTP Basic with the user's email and an API token; Data Center uses a Bearer Personal Access Token.

```php
function jiraRequest(string $method, string $url, ?array $json = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the HTTP method - 'GET' or 'POST' |
| `$url` | `string` |  | the full URL from `apiUrl()`, query string included |
| `$json` | `?array` | `null` | the body to JSON-encode; null sends no body |

### extractError

*protected* - line 160

Turn a Jira error response into something an analyst can act on.

Jira reports field-level problems in `errors` (e.g. {"customfield_10010":
"Epic Link is required"}) and general ones in `errorMessages`. Surfacing
only "HTTP 400" would make the most common failure — a project whose screen
scheme demands a field we did not send — completely undiagnosable.

```php
function extractError(int $code, string $rawBody): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `int` |  | the HTTP status code |
| `$rawBody` | `string` |  | the response body as received |

### renderDoc

*public* - line 194

ADF (an array) on Cloud; wiki markup (a string) on Data Center.

```php
function renderDoc(IssueDoc $doc)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `IssueDoc` |  | the description or comment to render |

### buildCreatePayload

*protected* - line 212

Builds the JSON body for creating an issue - project, issue type, a one-line summary of up to 255 characters, the rendered description and any mapped fields.

Throws when no project or issue type was given or mapped.

```php
function buildCreatePayload(
    array $target,
    string $summary,
    IssueDoc $body,
    array $fields = []
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$target` | `array` |  | ['project' => 'OPS', 'issue_type' => 'Bug'] |
| `$summary` | `string` |  | the ticket subject; whitespace collapsed, blank becomes '(no subject)' |
| `$body` | `IssueDoc` |  | the description |
| `$fields` | `array` | `[]` | mapped Jira fields keyed by field id (e.g. 'priority' => ['name' => 'High']), merged last |

### createIssue

*public* - line 255

Creates an issue and returns it as the escalation panel needs it.

Reads the new issue back to get its status; if that read fails the creation still
stands and the status is left empty.

```php
function createIssue(array $target, string $summary, IssueDoc $body, array $fields = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$target` | `array` |  | ['project' => 'OPS', 'issue_type' => 'Bug'] - both required |
| `$summary` | `string` |  | the ticket subject |
| `$body` | `IssueDoc` |  | the description |
| `$fields` | `array` | `[]` | mapped Jira fields, as for `buildCreatePayload()` |

### parseIssue

*protected* - line 290

Jira's issue JSON → our normalised shape.

```php
function parseIssue(array $issue): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$issue` | `array` |  | one issue as the Jira REST API returns it - id, key and fields |

### mapStatusCategory

*protected* - line 319

Jira's statusCategory key → our closed set.

These three keys are fixed by Jira and are the same on every project on
every site, which is exactly why we key off them and not the status name.
Jira has no "cancelled" category — a Won't Do lands in `done` — so
STATUS_CANCELLED is simply unreachable here, and that is correct rather
than a gap.

```php
function mapStatusCategory(string $categoryKey): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$categoryKey` | `string` |  | Jira's statusCategory key - 'new', 'indeterminate' or 'done' |

### fetchIssue

*public* - line 334

Reads one issue's summary, status and assignee and returns it parsed.

```php
function fetchIssue(string $externalId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric Jira issue id (a key such as OPS-123 also works) |

### fetchIssues

*public* - line 350

Batch read via JQL — one call for the whole watch list instead of one per issue, which matters because this is the poll cron's hot path.

Chunked at 100: JQL has a practical length limit, and a site with hundreds
of linked issues would otherwise build a URL nothing will accept.

```php
function fetchIssues(array $externalIds): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalIds` | `array` |  | numeric Jira issue ids; anything else is dropped |

### addComment

*public* - line 430

Posts a comment on an issue, rendered for this Jira flavour, and returns the new comment's id.

```php
function addComment(string $externalId, IssueDoc $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric Jira issue id |
| `$body` | `IssueDoc` |  | the comment, rendered for this flavour |

### fetchComments

*public* - line 447

Comments on one issue, newest first, normalised.

```php
function fetchComments(string $externalId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric Jira issue id |

**Returns:** array [['comment_id'=>…, 'comment_body'=>plain text, 'author_identity'=>…, 'author_name'=>…, 'created_ts'=>?int], …]

### parseComment

*protected* - line 470

One Jira comment → the fields the canonical comment_added event needs.

⚠️ author_identity must be the SAME kind of value `testConnection()` returns,
because echo suppression compares the two. Cloud identifies a user by
accountId and Data Center by username, so this is flavour-aware for exactly
the reason `testConnection()` is.

```php
function parseComment(array $c): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | one comment as the Jira REST API returns it - id, body, author, created |

### commentBodyToText

*protected* - line 494

A comment body in whichever shape this flavour sends → plain text.

Cloud sends ADF (nested JSON); Data Center sends wiki markup (a string).
Same split as `renderDoc()`, in the other direction.

```php
function commentBodyToText($body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` |  |  | the comment body - an ADF array on Cloud, a wiki markup string on Data Center |

### adfToText

*protected* - line 515

ADF → plain text.

This lives in the connector rather than in IssueDoc on purpose: ADF is
Atlassian's format and nobody else's, whereas IssueDoc is the shared
document every provider renders FROM. Putting an ADF reader in IssueDoc
would make core carry one tracker's vocabulary.

Deliberately lossy. The job is "an analyst can read what the dev said",
not a faithful round-trip — a note is plain text, and the issue itself is
one click away on the pill.

```php
function adfToText($node): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$node` |  |  | an ADF node (array); anything else gives '' |

### tidyText

*protected* - line 568

Shared tidy-up for text pulled out of a tracker.

```php
function tidyText(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | text pulled out of a comment |

### pollChanges

*public* - line 601

The inbound path until webhooks land: ask Jira what has been commented on since `$since` and return canonical `comment_added` events.

`$since` is a UTC 'Y-m-d H:i:s' watermark written by the service after a
successful pull. **null means this connection has never been polled**, and
that case deliberately returns NOTHING: turning comment sync on must not
tip a tracker's entire comment history onto tickets, some of which closed
months ago. The first run only establishes the watermark; the second run
onwards is when comments start arriving.

Two calls, not one per issue: a JQL search narrows the watch list to the
issues that actually changed, and only those are read for comments. On a
quiet day that is one search and no comment reads at all.

```php
function pollChanges(?string $since, array $externalIds = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$since` | `?string` |  | UTC 'Y-m-d H:i:s' watermark (last_poll_watermark); null on the first run returns nothing |
| `$externalIds` | `array` | `[]` | numeric ids of the issues this connection holds links to; anything else is dropped |

### lookbackMinutes

*protected* - line 652

How many minutes back to ask Jira about. Pure, so the capping rule is provable without a clock or a network.

```php
function lookbackMinutes(int $sinceTs, ?int $nowTs = null): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sinceTs` | `int` |  | the watermark as a Unix timestamp |
| `$nowTs` | `?int` | `null` | the current time as a Unix timestamp; null means `time()` |

### searchUpdatedSince

*protected* - line 671

Which of our watched issues changed in the last `$minutes`.

⚠️ Uses JQL's RELATIVE date syntax (`updated >= -90m`) rather than an
absolute timestamp. An absolute JQL date is interpreted in the *Jira
user's* timezone, not UTC, so a server in one timezone and a Jira account
in another would silently miss or re-read a window's worth of comments.
Relative minutes have no timezone at all.

```php
function searchUpdatedSince(array $ids, int $minutes): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ids` | `array` |  | numeric Jira issue ids to check |
| `$minutes` | `int` |  | how far back to look, from `lookbackMinutes()` |

### addAttachment

*public* - line 716

Upload one file to an issue.

Jira needs three things that a normal JSON call does not:

 1. **`X-Atlassian-Token: no-check`** — without it Jira rejects the upload
    as a suspected cross-site request, with a 403 and no useful body. It
    is the single most common reason a Jira upload "just fails".
 2. **`multipart/form-data` with the field named `file`** — not `files`,
    not the filename.
 3. **No `Accept: application/json` weirdness** — it still returns JSON,
    but the request itself must carry the multipart content type with its
    boundary, which is why the body is built here rather than handed to
    cURL as an array.

```php
function addAttachment(string $externalId, array $file): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the numeric id of the issue to attach the file to |
| `$file` | `array` |  | ['data'=>binary, 'filename'=>string, 'content_type'=>string] |

### buildMultipart

*protected* - line 762

A single-file multipart/form-data body, and the boundary that goes with it.

⚠️ The boundary must not occur inside the file's bytes or the body is
truncated at that point — hence a random one, not a fixed string.

```php
function buildMultipart(
    string $filename,
    string $contentType,
    string $data,
    ?string &$boundary
): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filename` | `string` |  | the file's original name; path parts, quotes and line breaks are stripped |
| `$contentType` | `string` |  | the file's MIME type |
| `$data` | `string` |  | the file's bytes |
| `$boundary` | `?string` |  | set to the random boundary used - the caller puts it in the Content-Type header |

### testConnection

*public* - line 789

Confirm the credentials and — just as importantly — capture who we are.

account_identity is half of echo suppression: an inbound event authored by
this identity is our own write coming back and must be dropped, not
re-imported. Cloud identifies users by accountId, Data Center by username,
and those are the values that appear as the author in each flavour's
webhook payloads.

```php
function testConnection(): array
```

### listProjects

*public* - line 834

Lists the Jira projects for the mapping dropdowns - paged on Cloud (up to 1,000), one call on Server / Data Center.

```php
function listProjects(): array
```

**Returns:** array [['key'=>'OPS','name'=>'Operations'], …]

### listPriorities

*public* - line 874

Every priority defined on the site, for the mapping screen's dropdown.

⚠️ Site-wide, but Jira applies priorities **per project** through priority
schemes — so a name listed here can still be rejected by a particular
project. That is exactly why a rejected priority falls back to creating the
issue without one rather than failing the escalation
(`integrationsLooksLikePriorityRejection()`).

Cloud paginates this (`/priority/search`); Data Center returns a plain
array from `/priority`. Same split as `listProjects()`, and pinned by tests
because Atlassian has form for retiring the unpaged version — see §6.

```php
function listPriorities(): array
```

**Returns:** array [['id'=>'3','name'=>'Medium'], …]

### listIssueTypes

*public* - line 905

Issue types available on a project.

⚠️ Which types a project offers depends on its issue-type scheme, so this
cannot be a hardcoded Bug/Task/Story list — that is exactly how you end up
400ing on the one project that renamed everything.

```php
function listIssueTypes(string $project): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the project key, e.g. 'OPS' |

