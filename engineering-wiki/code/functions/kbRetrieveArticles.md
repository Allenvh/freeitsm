# kbRetrieveArticles()

Find the Knowledge articles most relevant to a question. Uses embedding similarity when an OpenAI key + embedded articles exist, else falls back to every in-scope article. Returns ['articles' => [{id,title,body}], 'method' => 'vector'|'all'].

SCOPE — both arguments narrow what can possibly be returned, and they answer
different questions:
  `$tenantId` — WHOSE articles: the company asking (e.g. a web chat widget's).
              Its own articles plus shared (tenant_id IS NULL) ones. NULL means
              no company context => shared only. No-op on a single-company install.
  `$viewer`   — WHO is reading, as an Audience:: level. Web chat is anonymous, so it
              passes Audience::PUBLIC and can only ever see articles an author has
              deliberately marked public.

The default is Audience::PUBLIC — the MOST restrictive rung — on purpose. This
function's only caller is the anonymous web chat, so a future caller that forgets
to think about scope gets too little, never too much.

⚠️ `$viewer` ALSO accepts a KnowledgeViewer, and that is the form to use. An
Audience:: string names a RUNG; it cannot name a PERSON, so it can never carry
the access list — and this is the retrieval path where a missed filter is the
worst kind of leak, because the model paraphrases the article into an answer
and nothing records that the article was ever read. The string form is kept so
existing callers (and the harness that guards them) stay valid; when a
KnowledgeViewer is passed it is authoritative and `$tenantId` is ignored, since
the viewer already carries its own company.

Filtering happens in SQL, before scoring: it is both the security boundary and
strictly less work than decoding vectors we would discard. Filtering the
ANSWER afterwards would not work at all — by then the model has read the text.

```php
function kbRetrieveArticles(
    PDO $conn,
    string $question,
    int $limit = 5,
    ?int $tenantId = null,
    $viewer = Audience::PUBLIC
): array
```

Defined in [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$question` | `string` |  | the reader's question, embedded and compared with each article |
| `$limit` | `int` | `5` | the most articles returned on the vector path (the fallback returns at least 10) |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` |  | `Audience::PUBLIC` | a KnowledgeViewer (preferred, authoritative), or an Audience:: level string such as 'public' |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 131 | `kbRetrieveArticles` | read [system_settings](/code/tables/system_settings.md) |  |
| 141 | `kbRetrieveArticles` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 149 | `kbRetrieveArticles` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 167 | `kbRetrieveArticles` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [kbCosineSimilarity()](/code/functions/kbCosineSimilarity.md), [kbGenerateEmbedding()](/code/functions/kbGenerateEmbedding.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md) - in [webchatAiReply()](/code/functions/webchatAiReply.md)

