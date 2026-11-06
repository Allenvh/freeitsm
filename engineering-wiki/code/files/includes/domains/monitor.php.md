# includes/domains/monitor.php

> PHP - 112 lines

Domains — change detection: noticing that something important moved.

The classic domain hijack is quiet: somebody who has phished the registrar
login changes the name servers, and from then on they control the website and
the mail. Nobody at the organisation changed anything, so nobody is looking.
The first sign is usually customers reporting a strange page.

So after every check the fields that only change on purpose are compared with
the last run, and anything that moved is (1) written to the domain's history
and (2) announced as a `domain.changed` event — which the notification bell,
workflows and the alert e-mail all pick up.

⚠️ DELIBERATELY NOT WATCHED: A / AAAA records. Sites behind a CDN or a cloud
load balancer change address constantly and legitimately; watching them would
bury the one alert that matters under hundreds that do not.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainWatchedFields()](/code/functions/domainWatchedFields.md) | 21 | The fields compared, and a label key for each (lang: domains.watch.<key>). |
| [domainBaseline()](/code/functions/domainBaseline.md) | 35 | Build the baseline from a domain row plus the check snapshot. |
| [domainBaselineDiff()](/code/functions/domainBaselineDiff.md) | 66 | What changed between the previous baseline and this one. |
| [domainBaselineMerge()](/code/functions/domainBaselineMerge.md) | 88 | Keep the previous value of a field when this run could not see it, so a one-off gap never erases what we knew (and never makes tomorrow's reappearance look like a change). |
| [domainChangeIsSerious()](/code/functions/domainChangeIsSerious.md) | 104 | Is this change one that should wake somebody up (vs. merely be recorded)? |

## How it hangs together

**Included by:** [includes/services/domains.php](/code/files/includes/services/domains.php.md)

