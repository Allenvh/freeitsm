# Audience

> Class in [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md), line 44

Who is allowed to read a Knowledge article.

`knowledge_articles.audience` answers "who may read this", which is a
different question from `tenant_id` ("whose article is it"). You need both:
scoping an article to a company still hands it to that company's anonymous
website visitors unless something says it is internal.

Why one ordered value rather than a checkbox per channel: the readers are not
arbitrary peers, they are a ladder of how much we trust the reader —

  analyst           authenticated staff
  self-service user authenticated customer; we know who they are
  web chat visitor  ANONYMOUS. They typed a name and email; neither is verified

Three booleans would allow eight combinations, most of which are nonsense
("visible to web chat but not to analysts"), and every new channel would mean
another column plus a retroactive decision about every existing article. A
ladder cannot express a contradiction, and a new channel just declares which
rung it reads at.

The reader states its OWN level using the same vocabulary and gets everything
at that rung or more open:

  Audience::visibleTo(Audience::INTERNAL)  => internal, customer, public   (analyst)
  Audience::visibleTo(Audience::CUSTOMER)  => customer, public             (self-service)
  Audience::visibleTo(Audience::PUBLIC)    => public                       (web chat)

Constants rather than bare strings for the same reason as the Cap:: helpers:
a typo fatals at the call site, instead of silently matching nothing — and on
a visibility check, "silently matching nothing" is the difference between a
bug and a disclosure.

## Methods

| Method | What it does |
|---|---|
| [all()](#all) | All valid values, most restrictive first. |
| [isValid()](#isvalid) | True when the value is exactly one of the audience constants. |
| [normalise()](#normalise) | Normalise anything user-supplied to a valid level. Anything unrecognised becomes INTERNAL — the safe end of the ladder. |
| [visibleTo()](#visibleto) | The audiences a reader at `$viewerLevel` may see: their own rung and every more open one. An unrecognised level yields PUBLIC only (fail closed). |
| [sqlFilter()](#sqlfilter) | SQL predicate + params restricting a query to what `$viewerLevel` may read. Returns ['', []] for an analyst — they see every rung, so no filter is needed and the SQL stays byte-identical to before this existed. |

### all

*public static* - line 70

All valid values, most restrictive first.

```php
function all(): array
```

**Called from:** [api/knowledge/knowledge_bulk_audience.php](/code/files/api/knowledge/knowledge_bulk_audience.php.md), [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md)

### isValid

*public static* - line 80

True when the value is exactly one of the audience constants.

```php
function isValid($value): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | anything; only an exact audience string ('internal', 'customer', 'public') passes |

**Called from:** [api/knowledge/knowledge_bulk_audience.php](/code/files/api/knowledge/knowledge_bulk_audience.php.md), [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md)

### normalise

*public static* - line 91

Normalise anything user-supplied to a valid level. Anything unrecognised becomes INTERNAL — the safe end of the ladder.

```php
function normalise($value): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | user-supplied audience; anything unrecognised becomes 'internal' |

**Called from:** [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

### visibleTo

*public static* - line 102

The audiences a reader at `$viewerLevel` may see: their own rung and every more open one. An unrecognised level yields PUBLIC only (fail closed).

```php
function visibleTo(string $viewerLevel): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$viewerLevel` | `string` |  | the reader's audience level - 'internal', 'customer' or 'public' |

**Called from:** [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md)

### sqlFilter

*public static* - line 119

SQL predicate + params restricting a query to what `$viewerLevel` may read. Returns ['', []] for an analyst — they see every rung, so no filter is needed and the SQL stays byte-identical to before this existed.

`$alias` is the table alias ('' for an unaliased query).

```php
function sqlFilter(string $viewerLevel, string $alias = 'a'): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$viewerLevel` | `string` |  | the reader's audience level - 'internal', 'customer' or 'public' |
| `$alias` | `string` | `'a'` | the knowledge articles table's alias in the calling query; '' when it has none |

**Called from:** [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

