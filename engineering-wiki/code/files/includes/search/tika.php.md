# includes/search/tika.php

> PHP - 215 lines

Apache Tika client — attachment extraction, tier 2 (discussion #53).

The formats the built-in tier refuses (PDF, legacy Office, and anything that
needs OCR) are sent to ONE external service rather than parsed here. The
reasoning is on the wiki page Attachment-Text-Extraction-Developer-Guide §2,
and the short version is that a document parser handling input from anyone who
can email the service desk belongs in a container, not in the process holding
the database credentials.

⚠️ FREEITSM HOSTS NOTHING. This is a URL and a timeout. The administrator runs
Tika wherever suits them — a container, a JVM, another machine — and that is
deliberately not our business to start, stop or supervise.

⚠️ TIKA HAS NO AUTHENTICATION OF ANY KIND. Anything that can reach it will
parse whatever bytes it is sent, using a very large parser surface. The
documentation tells administrators to bind it to loopback or an internal
container network and never publish the port. This client cannot enforce that,
but the setting screen says it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [tikaUrl()](/code/functions/tikaUrl.md) | 56 | The configured endpoint, or '' when tier 2 is switched off. |
| [tikaTimeoutOverride()](/code/functions/tikaTimeoutOverride.md) | 78 | Force a shorter timeout for the current process. |
| [tikaTimeout()](/code/functions/tikaTimeout.md) | 88 | How many seconds to wait for Tika, from the override or the system setting, kept within the allowed range. |
| [tikaConfigured()](/code/functions/tikaConfigured.md) | 101 | Is tier 2 switched on at all? |
| [tikaHandles()](/code/functions/tikaHandles.md) | 110 | Would Tika be asked about this file, if configured? |
| [tikaExtract()](/code/functions/tikaExtract.md) | 132 | Ask Tika for the text of a file. |
| [tikaPing()](/code/functions/tikaPing.md) | 193 | Is the service answering? Used by the settings screen's Test button, and NOWHERE in the indexing path — a health check before every attachment would be an extra HTTP round trip per file. There, the extraction attempt IS the check. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `tikaUrl` | read [system_settings](/code/tables/system_settings.md) |  |
| 93 | `tikaTimeout` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/search/extract.php](/code/files/includes/search/extract.php.md), [includes/ssl.php](/code/files/includes/ssl.php.md)

**Included by:** [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), [system/integrations/index.php](/code/files/system/integrations/index.php.md)

**Calls:** [attTextExtension()](/code/functions/attTextExtension.md), [attTextNormalise()](/code/functions/attTextNormalise.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

