# includes/integrations/JiraProvider.php

> PHP - 928 lines

JiraProvider — Jira Cloud and Jira Data Center / Server behind one contract.

── Cloud vs Data Center ────────────────────────────────────────────────────

They are the same product with two genuinely different APIs, and the
differences are not cosmetic:

                       Cloud                     Data Center / Server
  REST base            /rest/api/3               /rest/api/2
  Description format   ADF (nested JSON)         wiki markup (a string)
  Auth                 email + API token (Basic) Personal Access Token (Bearer)
  User identity        accountId                 username ("name")
  Project list         /project/search (paged)   /project (a plain array)

Rather than two providers with 80% duplication, this is ONE provider with a
`flavour`. It is decided once at `testConnection()` and cached in the
connection's credentials blob, falling back to a base-URL heuristic
(*.atlassian.net is always Cloud; Data Center never is) so a connection saved
before the sniff still behaves.

── Testability ─────────────────────────────────────────────────────────────

Payload building and response parsing are separate, protected methods rather
than being inlined into the HTTP calls. That is deliberate: it means the
interesting logic — the ADF-vs-wiki choice, the status mapping, the error
extraction — is provable without a live Jira site. tests/integrations/run.php
subclasses this and stubs `httpRequest()`.

⚠️ Status categories: Jira's own statusCategory is the ONLY stable thing here.
Status *names* are per-project, per-workflow and renamed at will, so nothing
branches on them. See the note on tickets.merged_into_id for the last time
keying on a status name cost us.

## Classes

- [JiraProvider](/code/classes/JiraProvider.md) (class, line 40)

## How it hangs together

**Includes:** [includes/integrations/IssueTrackerProvider.php](/code/files/includes/integrations/IssueTrackerProvider.php.md), [includes/integrations/IssueDoc.php](/code/files/includes/integrations/IssueDoc.php.md)

**Included by:** [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

