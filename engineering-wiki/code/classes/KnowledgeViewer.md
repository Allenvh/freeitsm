# KnowledgeViewer

> Class in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 75

WHO is asking. Not the same thing as ActorContext (service_context.php), which models an *analyst* performing a write — a Knowledge reader may be a portal user or an anonymous web-chat visitor, neither of whom has an analyst id at all.

The audience ladder already names exactly these three readers, so the viewer
carries its rung rather than inventing a parallel vocabulary:

  Audience::INTERNAL  analyst (or an API key, which acts as one)
  Audience::CUSTOMER  signed-in self-service user
  Audience::PUBLIC    anonymous web-chat visitor

Build one with a named constructor. There is deliberately no public
`new KnowledgeViewer(...)` that lets a caller assert its own rung from
request data — that is how a "level" parameter becomes a privilege
escalation.

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Private: viewers are built through the named constructors, so every caller states who is reading. |
| [scopeForOneCompany()](#scopeforonecompany) | One company id in the set form, honouring the two conditions under which Knowledge does no company filtering at all: multi-tenancy dormant, or the column absent on an install that has not run Database Verify. |
| [forAnalyst()](#foranalyst) | A signed-in analyst, reading from whichever company they have switched to. |
| [scopeForAllAccessible()](#scopeforallaccessible) | Every company this analyst may see, in the same set form as `scopeForOneCompany()`. Honours the same two "no filtering at all" cases. |
| [forApiKey()](#forapikey) | An authenticated REST API key. |
| [forPortalUser()](#forportaluser) | A signed-in self-service portal user. |
| [forWebChat()](#forwebchat) | An anonymous web-chat visitor. They typed a name and an email and neither was verified, which is the whole reason PUBLIC is the bottom rung. |
| [fromLegacyAudienceString()](#fromlegacyaudiencestring) | ⚠️ COMPATIBILITY ONLY — do not use in new code. |
| [forSystem()](#forsystem) | No filtering whatsoever — for internal machinery that must process every article regardless of who could read it (the search indexer building rows it will later filter at query time; the embedding backfill). |
| [level()](#level) | The audience rung this viewer reads at. |
| [analystId()](#analystid) | The analyst reading, or null for a portal user, a visitor or a system reader. |
| [userId()](#userid) | The self-service user reading, or null when the reader is not a portal user. |
| [companyScope()](#companyscope) | The company ids the viewer may read from, or null for no company limit. |
| [isUnrestricted()](#isunrestricted) | True for a `forSystem()` reader, which skips all filtering. |
| [unrestrictedReason()](#unrestrictedreason) | The reason given to `forSystem()` for skipping all filtering; empty for every other viewer. |
| [isAnalyst()](#isanalyst) | True for an analyst reading (internal level and not an unrestricted system reader). |

### __construct

*private* - line 124

Private: viewers are built through the named constructors, so every caller states who is reading.

```php
function __construct(
    string $level,
    ?int $analystId,
    ?int $userId,
    ?array $companyScope,
    bool $unrestricted = false,
    string $unrestrictedReason = ''
)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$level` | `string` |  | the audience rung - 'internal', 'customer' or 'public'; anything else becomes 'internal' |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `?int` |  | the portal user reading, or null when the reader is not a portal user |
| `$companyScope` | `?array` |  | null for no company limit, [] for shared articles only, or the company ids allowed |
| `$unrestricted` | `bool` | `false` | true only for `forSystem()` - skips every permission filter |
| `$unrestrictedReason` | `string` | `''` | why the filters are skipped; '' unless `$unrestricted` |

### scopeForOneCompany

*private static* - line 148

One company id in the set form, honouring the two conditions under which Knowledge does no company filtering at all: multi-tenancy dormant, or the column absent on an install that has not run Database Verify.

NULL `$tenantId` means "no company context" => shared only, which is what
`knowledgeTenantFilterForCompany()` has always done with a null.

```php
function scopeForOneCompany(PDO $conn, ?int $tenantId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### forAnalyst

*public static* - line 162

A signed-in analyst, reading from whichever company they have switched to.

The active company is resolved HERE rather than being passed in, so every
analyst read follows the header switcher automatically and a caller
cannot accidentally supply a different one.

```php
function forAnalyst(PDO $conn, int $analystId): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [api/files/shortcut.php](/code/files/api/files/shortcut.php.md), [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md), [api/knowledge/audit.php](/code/files/api/knowledge/audit.php.md), [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md), [api/knowledge/get_articles_for_embedding.php](/code/files/api/knowledge/get_articles_for_embedding.php.md), [api/knowledge/get_embedding_stats.php](/code/files/api/knowledge/get_embedding_stats.php.md), [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md), [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md), [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md), [api/knowledge/knowledge_tags.php](/code/files/api/knowledge/knowledge_tags.php.md), [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md), [api/system/global_search.php](/code/files/api/system/global_search.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/domains/links.php](/code/files/includes/domains/links.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [includes/record_preview.php](/code/files/includes/record_preview.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

### scopeForAllAccessible

*private static* - line 196

Every company this analyst may see, in the same set form as `scopeForOneCompany()`. Honours the same two "no filtering at all" cases.

```php
function scopeForAllAccessible(PDO $conn, int $analystId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

### forApiKey

*public static* - line 216

An authenticated REST API key.

`api_keys.analyst_id` is NOT NULL and api/v1/lib/auth.php states that
"every key acts as an analyst" — so a key inherits its analyst's reach and
needs no principal model of its own. The API comes along for free
PROVIDED it reads through this file like everything else.

```php
function forApiKey(PDO $conn, array $apiKey): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row (analyst_id, company_scope) |

**Called from:** [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md)

### forPortalUser

*public static* - line 241

A signed-in self-service portal user.

```php
function forPortalUser(PDO $conn, ?int $userId, ?int $tenantId): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `?int` |  | the portal user's users.id; null or 0 for no named user |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md)

### forWebChat

*public static* - line 250

An anonymous web-chat visitor. They typed a name and an email and neither was verified, which is the whole reason PUBLIC is the bottom rung.

```php
function forWebChat(PDO $conn, ?int $tenantId): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md)

### fromLegacyAudienceString

*public static* - line 271

⚠️ COMPATIBILITY ONLY — do not use in new code.

`kbRetrieveArticles()` has long accepted a bare Audience:: string, and its
harness passes every rung through it. Rather than rewrite the code and the
test that guards it in the same breath — which would leave the test unable
to catch the rewrite — that form still works, and lands here.

This is the ONE place a caller may state its own rung. It is named to be
obvious and greppable for exactly that reason: everything else must use a
constructor that DERIVES the rung from who is actually asking, because a
level accepted from request data is a privilege escalation waiting to be
found. Deleting this method is the last step of the migration.

```php
function fromLegacyAudienceString(PDO $conn, ?int $tenantId, string $level): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$level` | `string` |  | the audience rung the caller states - 'internal', 'customer' or 'public' |

**Called from:** [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md)

### forSystem

*public static* - line 287

No filtering whatsoever — for internal machinery that must process every article regardless of who could read it (the search indexer building rows it will later filter at query time; the embedding backfill).

`$reason` is required and is not decoration: it makes every bypass in the
codebase findable with one grep, and forces the author to name the
justification at the point of use rather than in a commit message.

```php
function forSystem(string $reason): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$reason` | `string` |  | why this caller may see every article, e.g. 'search indexer'; blank throws |

**Called from:** [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

### level

*public* - line 296

The audience rung this viewer reads at.

```php
function level(): string
```

### analystId

*public* - line 298

The analyst reading, or null for a portal user, a visitor or a system reader.

```php
function analystId(): ?int
```

### userId

*public* - line 300

The self-service user reading, or null when the reader is not a portal user.

```php
function userId(): ?int
```

### companyScope

*public* - line 302

The company ids the viewer may read from, or null for no company limit.

```php
function companyScope(): ?array
```

### isUnrestricted

*public* - line 304

True for a `forSystem()` reader, which skips all filtering.

```php
function isUnrestricted(): bool
```

### unrestrictedReason

*public* - line 306

The reason given to `forSystem()` for skipping all filtering; empty for every other viewer.

```php
function unrestrictedReason(): string
```

### isAnalyst

*public* - line 308

True for an analyst reading (internal level and not an unrestricted system reader).

```php
function isAnalyst(): bool
```

