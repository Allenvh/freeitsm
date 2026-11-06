# includes/integrations/IssueTrackerProvider.php

> PHP - 339 lines

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

## Classes

- [IssueTrackerProvider](/code/classes/IssueTrackerProvider.md) (class, line 49) - IssueTrackerProvider — the provider-agnostic contract for an external issue tracker (Jira first; GitHub, GitLab and Azure DevOps behind the same door).

## How it hangs together

**Included by:** [includes/integrations/AzureDevOpsProvider.php](/code/files/includes/integrations/AzureDevOpsProvider.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/integrations/JiraProvider.php](/code/files/includes/integrations/JiraProvider.php.md)

**Calls:** [sslApplyCurl()](/code/functions/sslApplyCurl.md)

