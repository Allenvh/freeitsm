# includes/knowledge/writeup_ai.php

> PHP - 855 lines

The Knowledge assistant — turning closed tickets into knowledge, honestly.

The naive version of this feature ("button on a ticket, out comes an article")
fails on the first ticket you try it on. An analyst who writes *"reset password,
asked user to log in again"* has not written an article, and a knowledge base
full of that is worse than an empty one: it teaches people the portal is
useless and deflection goes backwards.

So this file is built around three rules.

 1. THE MODEL'S FIRST JOB IS TO REFUSE. Every call judges before it writes. A
    thin ticket comes back with "there is no article here, and here is what I
    would need to know" — never with a padded-out article.
 2. A REFUSAL IS NOT A DEAD END. When the ticket looks worth writing up but
    the detail is missing, the model asks two to four specific questions. The
    analyst who wrote one line will happily type three sentences into a box.
 3. VOLUME IS THE REAL SIGNAL. One ticket cannot tell you whether an article is
    worth having; a cluster of fourteen near-identical ones can. The gap finder
    (see writeupGapCandidateSql) is the half that decides WHAT to write; this
    half only decides whether there is enough material to write it FROM.

Shared deliberately: the Tickets module owns the moment (a button on a solved
ticket) and the Knowledge module owns the judgement (the assistant, the
thresholds, the drafts). Both call in here rather than keeping two copies —
the same split kb_ai.php already uses for Knowledge chat vs web chat.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [writeupAiConfig()](/code/functions/writeupAiConfig.md) | 47 | Which AI config the assistant should use. |
| [writeupSchemaReady()](/code/functions/writeupSchemaReady.md) | 71 | Does this install have the gap tables yet? |
| [writeupSettings()](/code/functions/writeupSettings.md) | 94 | Tunables, with defaults that a fresh install can live with. |
| [writeupPlainText()](/code/functions/writeupPlainText.md) | 146 | Strip HTML, entities, quoted reply chains and signatures down to readable text. |
| [writeupRichness()](/code/functions/writeupRichness.md) | 197 | How much of an article could actually be written from this one ticket? 0-100. |
| [writeupTicketBundle()](/code/functions/writeupTicketBundle.md) | 235 | Everything the assistant knows about one ticket: the readable text, the parts it was built from, and the richness score. |
| [writeupEmbeddingText()](/code/functions/writeupEmbeddingText.md) | 369 | The shorter text used for EMBEDDING a ticket (as opposed to drafting from it). |
| [writeupStopWords()](/code/functions/writeupStopWords.md) | 400 | Words too common on a service desk to carry any signal. |
| [writeupStem()](/code/functions/writeupStem.md) | 432 | Crude suffix stemmer, so that different tenses of the same complaint match. |
| [writeupSubjectTokens()](/code/functions/writeupSubjectTokens.md) | 478 | Normalise a subject into comparable tokens: strip Re:/Fwd:, ticket references, numbers, punctuation and stop words, then stem what is left. |
| [writeupTokenSimilarity()](/code/functions/writeupTokenSimilarity.md) | 521 | Overlap coefficient of two token sets — \|A ∩ B\| / min(\|A\|, \|B\|) — with a floor of two shared tokens. |
| [writeupCluster()](/code/functions/writeupCluster.md) | 570 | Single-linkage clustering: grow a cluster outwards from a seed, pulling in anything close to ANY member rather than only to the seed. |
| [writeupCleanLabel()](/code/functions/writeupCleanLabel.md) | 615 | Tidy a ticket subject into something that reads as a knowledge-base heading. Cosmetic only — the model writes the real title when it drafts. |
| [writeupSystemPrompt()](/code/functions/writeupSystemPrompt.md) | 647 | The system prompt. One call does both jobs — judge, then write — because the judgement is worthless if a second call can overrule it, and because a single streamed response is a far better experience than a spinner followed by a verdict followed by another spinner. |
| [writeupProtocolBlock()](/code/functions/writeupProtocolBlock.md) | 730 | The response protocol, shared by all three modes so it can never drift apart. |
| [writeupUserMessage()](/code/functions/writeupUserMessage.md) | 755 | Build the user message for a single-ticket write-up. |
| [writeupClusterUserMessage()](/code/functions/writeupClusterUserMessage.md) | 773 | Build the user message for a cluster write-up: the richest ticket as the source, the rest as evidence of how the question gets asked. |
| [writeupParseResponse()](/code/functions/writeupParseResponse.md) | 802 | Split a model response into its verdict and body. |
| [writeupExtractQuestions()](/code/functions/writeupExtractQuestions.md) | 823 | Pull the "- " question lines out of a NOT_ENOUGH body. |
| [writeupExtractExplanation()](/code/functions/writeupExtractExplanation.md) | 843 | The prose part of a NOT_ENOUGH body, with the question lines removed. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 123 | `writeupSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 238 | `writeupTicketBundle` | read [tickets](/code/tables/tickets.md) |  |
| 252 | `writeupTicketBundle` | read [emails](/code/tables/emails.md) |  |
| 262 | `writeupTicketBundle` | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 274 | `writeupTicketBundle` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 284 | `writeupTicketBundle` | read [problem_tickets](/code/tables/problem_tickets.md) |  |
| 372 | `writeupEmbeddingText` | read [tickets](/code/tables/tickets.md) |  |
| 378 | `writeupEmbeddingText` | read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md)

**Included by:** [api/knowledge/assistant_settings.php](/code/files/api/knowledge/assistant_settings.php.md), [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md), [api/knowledge/gap_dismiss.php](/code/files/api/knowledge/gap_dismiss.php.md), [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md), [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md), [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md)

**Calls:** [aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

