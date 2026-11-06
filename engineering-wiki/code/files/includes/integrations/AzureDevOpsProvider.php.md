# includes/integrations/AzureDevOpsProvider.php

> PHP - 979 lines

AzureDevOpsProvider — Azure DevOps Services (cloud) and Server (on-prem).

── Why this connector is shaped differently from Jira ───────────────────────

Jira and Azure DevOps look alike from the outside and are genuinely different
underneath. The four differences that drive this file:

  1. WRITES ARE JSON PATCH. Everything — create and update — is a
     `[{"op":"add","path":"/fields/System.Title","value":…}]` document sent as
     `application/json-patch+json`. There is no "post a fields object" call.
  2. TYPES AND STATES ARE PER-PROCESS, not per-project or global. Two projects
     in the SAME organisation can disagree about which work item types exist
     and what their states are called.
  3. BODIES ARE HTML, and which FIELD holds the body depends on the work item
     type — see `bodyFieldFor()`.
  4. QUERYING IS TWO-STEP. WIQL returns ids only; the fields need a second
     batch read.

── ⚠️ Five state categories, not four ──────────────────────────────────────

Azure DevOps has Proposed / InProgress / **Resolved** / Completed / Removed.
We have four. `Resolved` — "the developer says it is fixed, but nobody has
verified it" — is the one that does not map cleanly, so it is a per-connection
setting rather than a decision baked in here. See `mapStateCategory()`.

⚠️ Never branch on a state NAME. Azure DevOps proves why better than Jira did:
in the stock Agile process the state called **"Resolved" is category Resolved
on a Bug and category InProgress on a User Story** — the same word meaning
different things on two types in the same project.

── Testability ─────────────────────────────────────────────────────────────

As with JiraProvider, payload building and response parsing are separate
protected methods so the interesting logic is provable without a live
organisation. tests/integrations stubs `httpRequest()`.

## Classes

- [AzureDevOpsProvider](/code/classes/AzureDevOpsProvider.md) (class, line 43)

## How it hangs together

**Includes:** [includes/integrations/IssueTrackerProvider.php](/code/files/includes/integrations/IssueTrackerProvider.php.md), [includes/integrations/IssueDoc.php](/code/files/includes/integrations/IssueDoc.php.md)

**Included by:** [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

