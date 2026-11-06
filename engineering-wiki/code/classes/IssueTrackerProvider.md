# IssueTrackerProvider

> Class in [includes/integrations/IssueTrackerProvider.php](/code/files/includes/integrations/IssueTrackerProvider.php.md), line 49

IssueTrackerProvider — the provider-agnostic contract for an external issue tracker (Jira first; GitHub, GitLab and Azure DevOps behind the same door).

Everything above this line — the link registry, the outbound queue, the
inbound event pipeline, echo suppression, the workflow action, the settings
and ticket UI — talks to this interface and never knows which tracker is live.
That is the same split as includes/messaging/MessagingProvider.php, which
already puts Twilio and Meta Cloud behind one contract, and it is deliberately
copied rather than reinvented.

Concrete providers are constructed with a DECRYPTED integration_connections
row (credentials already a PHP array).

── Conventions, both borrowed from MessagingProvider ────────────────────────

 1. Only the genuinely universal methods are abstract. Optional ones default
    to throwing "not supported for this provider", so the contract never
    collapses to a lowest common denominator just because one tracker lacks a
    concept.

 2. `supports()` answers the same question for the UI. A thrown exception is the
    wrong way for a settings screen to discover that GitHub has no issue
    types — it needs to know BEFORE it renders the field.

── The canonical event shape (what normaliseEvent/pollChanges return) ───────

  [
    'event_id'   => 'jira-evt-8837',   // provider's id, for dedupe; required
    'type'       => 'status_changed',  // status_changed | comment_added |
                                       // issue_created | field_changed
    'external_id'=> '10042',           // the issue this is about
    'status_name'     => 'In Progress',       // status_changed only
    'status_category' => 'in_progress',       // status_changed only; see below
    'comment_id'      => '10101',             // comment_added only
    'comment_body'    => 'Need repro steps',  // comment_added only
    'author_identity' => 'svc@acme.com',      // WHO did it — drives echo suppression
    'occurred_at'     => 1785600000,          // unix seconds, or null
  ]

⚠️ status_category is one of the four STATUS_* constants and nothing else.
Every decision in the system keys off it; status_name is for display only.
Jira statuses are per-project and freely renamed, so branching on the name is
the same mistake as keying a rule on a ticket status name — see the comment on
tickets.merged_into_id for the last time that bit us.

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Keeps the decrypted integration_connections row the connector works with. |
| [getProvider()](#getprovider) | The provider key from the connection row ('jira' or 'azuredevops'). |
| [createIssue()](#createissue) | Create an issue and return the bits we store on the link. |
| [fetchIssue()](#fetchissue) | Read one issue's current state. Same return shape as `createIssue()`. Throws if the issue is gone — the caller decides whether that orphans the link. |
| [fetchIssues()](#fetchissues) | Read several issues at once. The default loops `fetchIssue()`, which is always correct; providers that can do it in one call (Jira via JQL) should override, because the poll cron is the hot path. |
| [addComment()](#addcomment) | Post a comment. Returns the provider's comment id (recorded for echo suppression). |
| [addAttachment()](#addattachment) | Attaches a file to an external issue. The default throws; connectors that declare CAP_ATTACHMENTS override it. |
| [updateFields()](#updatefields) | Updates fields on an external issue. The default throws; a connector that supports it overrides it. |
| [verifyWebhook()](#verifywebhook) | Verify an inbound webhook really came from the provider. Return false to reject — the endpoint 403s. |
| [normaliseEvent()](#normaliseevent) | Raw webhook payload → zero or more canonical events (see the file header). |
| [pollChanges()](#pollchanges) | The firewalled-install path: ask the provider what changed since `$since` and return the SAME canonical events a webhook would have produced. |
| [testConnection()](#testconnection) | Verify the connection with a lightweight read-only call and return BOTH a human-readable detail for the settings screen and the authenticated account's identity. |
| [listProjects()](#listprojects) | Lists the tracker's projects for the mapping dropdowns. The default throws; connectors override it. |
| [listIssueTypes()](#listissuetypes) | Lists the issue types available in a project. The default throws; connectors that declare CAP_ISSUE_TYPES override it. |
| [renderDoc()](#renderdoc) | Render a body into whatever this provider's API wants. Usually one line delegating to IssueDoc — `$doc`->toAdf(), ->toMarkdown(), ->toHtml(), ->toWikiMarkup() — which is why adding a provider rarely means writing a renderer. |
| [supports()](#supports) | Does this provider have `$capability`? Default: no. |
| [capabilities()](#capabilities) | The optional features this connector supports. The base class declares none. |
| [httpRequest()](#httprequest) | Shared cURL. Returns [httpCode, bodyString]; an HTTP error code is NOT an exception, because the caller knows what a 404 means for the call it made. |

### __construct

*public* - line 72

Keeps the decrypted integration_connections row the connector works with.

```php
function __construct(array $connection)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$connection` | `array` |  | the integration_connections row, with its credentials already decrypted to an array |

### getProvider

*public* - line 78

The provider key from the connection row ('jira' or 'azuredevops').

```php
function getProvider(): string
```

### createIssue

*public* - line 98

Create an issue and return the bits we store on the link.

```php
function createIssue(array $target, string $summary, IssueDoc $body, array $fields = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$target` | `array` |  | provider-shaped destination — Jira: ['project'=>'OPS', 'issue_type'=>'Bug']; GitHub: ['repo'=>'org/name']; DevOps: ['project'=>…,'area_path'=>…,'work_item_type'=>…]. Opaque to core on purpose: the arity genuinely differs. |
| `$summary` | `string` |  | plain text |
| `$body` | `IssueDoc` |  | the description, rendered for the provider by `renderDoc()` |
| `$fields` | `array` | `[]` | extra mapped fields (V3+); [] in V1 |

**Returns:** array ['external_id'=>…, 'external_key'=>…, 'external_url'=>…, 'status_name'=>…, 'status_category'=>…]

### fetchIssue

*public* - line 106

Read one issue's current state. Same return shape as `createIssue()`. Throws if the issue is gone — the caller decides whether that orphans the link.

```php
function fetchIssue(string $externalId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the tracker's own id for the issue, as stored in integration_links.external_id |

### fetchIssues

*public* - line 119

Read several issues at once. The default loops `fetchIssue()`, which is always correct; providers that can do it in one call (Jira via JQL) should override, because the poll cron is the hot path.

A single issue failing must not lose the rest — its id is simply absent
from the result, and the caller decides what a missing issue means.

```php
function fetchIssues(array $externalIds): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalIds` | `array` |  | tracker ids of the issues to read (integration_links.external_id values) |

**Returns:** array    external_id => issue array (same shape as fetchIssue)

### addComment

*public* - line 138

Post a comment. Returns the provider's comment id (recorded for echo suppression).

```php
function addComment(string $externalId, IssueDoc $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the tracker id of the issue to comment on |
| `$body` | `IssueDoc` |  | the comment, rendered for the provider by `renderDoc()` |

### addAttachment

*public* - line 150

Attaches a file to an external issue. The default throws; connectors that declare CAP_ATTACHMENTS override it.

```php
function addAttachment(string $externalId, array $file): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the tracker id of the issue to attach the file to |
| `$file` | `array` |  | ['data'=>binary, 'filename'=>string, 'content_type'=>string] |

### updateFields

*public* - line 161

Updates fields on an external issue. The default throws; a connector that supports it overrides it.

```php
function updateFields(string $externalId, array $fields): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$externalId` | `string` |  | the tracker id of the issue to update |
| `$fields` | `array` |  | provider field name => new value, in the provider's own field naming |

### verifyWebhook

*public* - line 178

Verify an inbound webhook really came from the provider. Return false to reject — the endpoint 403s.

⚠️ Verify against the RAW body, before json_decode: the provider signs
bytes, and a re-encoded array is not the same bytes.

```php
function verifyWebhook(string $rawBody, array $headers): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the request body exactly as received, before any JSON decoding |
| `$headers` | `array` |  | the inbound request's HTTP headers |

### normaliseEvent

*public* - line 188

Raw webhook payload → zero or more canonical events (see the file header).

```php
function normaliseEvent(array $raw): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `array` |  | the webhook payload, JSON-decoded |

### pollChanges

*public* - line 210

The firewalled-install path: ask the provider what changed since `$since` and return the SAME canonical events a webhook would have produced.

Polling is a degraded webhook, not a second pipeline — everything
downstream of here cannot tell which one produced the event.

`$externalIds` is the watch list: the issues we actually hold links to.
Polling is scoped to them rather than to "everything that changed", because
an unscoped poll on a busy Jira site returns thousands of issues we have no
interest in. (V4, where an issue raised in Jira creates a ticket, is the
case that will need an unscoped variant — it can pass an empty list and a
project scope then.)

```php
function pollChanges(?string $since, array $externalIds = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$since` | `?string` |  | provider-native watermark; null = first run |
| `$externalIds` | `array` | `[]` | issues to check |

### testConnection

*public* - line 231

Verify the connection with a lightweight read-only call and return BOTH a human-readable detail for the settings screen and the authenticated account's identity.

⚠️ account_identity is not cosmetic. It is half of echo suppression: an
inbound event authored by this identity is our own write coming back, and
must be dropped rather than re-imported. It is captured in V1 — before
anything reads it — because back-filling it for links that already exist
is miserable.

```php
function testConnection(): array
```

**Returns:** array ['detail' => 'Connected to Acme Jira as svc@acme.com', 'account_identity' => 'svc@acme.com']

### listProjects

*public* - line 239

Lists the tracker's projects for the mapping dropdowns. The default throws; connectors override it.

```php
function listProjects(): array
```

**Returns:** array [['key'=>'OPS','name'=>'Operations'], …]

### listIssueTypes

*public* - line 251

Lists the issue types available in a project. The default throws; connectors that declare CAP_ISSUE_TYPES override it.

```php
function listIssueTypes(string $project): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `string` |  | the project's key, as `listProjects()` returns it |

**Returns:** array [['id'=>'10001','name'=>'Bug'], …]

### renderDoc

*public* - line 267

Render a body into whatever this provider's API wants. Usually one line delegating to IssueDoc — `$doc`->toAdf(), ->toMarkdown(), ->toHtml(), ->toWikiMarkup() — which is why adding a provider rarely means writing a renderer.

```php
function renderDoc(IssueDoc $doc)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `IssueDoc` |  | the description or comment to render |

**Returns:** mixed array (ADF) or string (Markdown / HTML / wiki markup)

### supports

*public* - line 276

Does this provider have `$capability`? Default: no.

```php
function supports(string $capability): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$capability` | `string` |  | one of the CAP_* constants |

### capabilities

*protected* - line 286

The optional features this connector supports. The base class declares none.

```php
function capabilities(): array
```

**Returns:** string[] the CAP_* keys this provider offers

### httpRequest

*protected* - line 310

Shared cURL. Returns [httpCode, bodyString]; an HTTP error code is NOT an exception, because the caller knows what a 404 means for the call it made.

⚠️ `sslApplyCurl()` is mandatory — it honours the app-wide SSL_VERIFY_PEER
setting and ships the CA bundle. Never write raw CURLOPT_SSL_VERIFYPEER.

`body` is a STRING — JSON for the usual calls, or a pre-built
`multipart/form-data` payload for a file upload, in which case the caller
supplies the matching `Content-Type: multipart/form-data; boundary=…`
header itself. Building the multipart body in the provider rather than
handing cURL an array keeps it deterministic, avoids a temp file per
upload, and means the exact bytes are assertable without a network.

```php
function httpRequest(string $url, array $opts = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full request URL, query string included |
| `$opts` | `array` | `[]` | ['method'=>'POST','headers'=>[],'body'=>string\|array,'auth'=>'user:pass','timeout'=>int] |

