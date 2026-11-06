# knowledge/settings/manifest.php

> PHP - 95 lines

Knowledge — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. See includes/capabilities.php.

Three of these four reach something that costs or leaks:
  - Email      — SMTP credentials for sharing articles out
  - AI         — the AI provider's API key
  - Embeddings — re-embedding the whole knowledge base spends real money
so all three are badged sensitive. The recycle bin (how long deleted articles are kept)
is the only ordinary one.

Two endpoints here serve MORE THAN ONE tab, so neither can be authorised with a single
guard — both authorise per setting key, from the keys declared below:
  - api/knowledge/save_email_settings.php  → Email + Recycle bin
  - api/system/ai/save_settings.php        → shared by SEVEN modules' AI tabs

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

