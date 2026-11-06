# includes/entity_links.php

> PHP - 123 lines

Where does a record live? Answered ONCE.

⚠️ WHY THIS EXISTS. Three separate places already knew how to build a deep
link to a record, and they did not agree:

  - NotificationsService::linkFor()  — knew `ticket` and `task`, and returned
    NULL for everything else, so a notification about a change or a problem
    rendered as href="#" and went nowhere.
  - api/system/global_search.php     — knew seven types, with its own URLs.
  - anchors written inline across the module JS — whatever that file needed
    on the day.

The disagreement was not cosmetic. Change Management alone was linked to as
`?change_id=`, `?id=`, `?change=` and `?open=`; problems as `?problem_id=` in
one map and `?id=` in another. Most of that survived only because the modules
are permissive and accept several names.

⚠️ TWO MODULES ARE NOT PERMISSIVE, and both dead links found while sweeping
for GH #91 pointed at exactly those two:

  - the ticket inbox reads ONLY `ticket_id` (assets/js/inbox.js)
  - the tasks board reads ONLY `task`   (assets/js/tasks.js)

Get one of those wrong and the browser opens the right module and does
nothing at all — a link that looks like it worked, which is why neither was
ever reported. Tolerance elsewhere is what hid the drift; these two had none.

Every path returned here is relative to BASE_URL — the caller prefixes it.
That is the contract both previous maps already used.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [entityLink()](/code/functions/entityLink.md) | 47 | The canonical deep link for a record, or NULL when the type has no page. |
| [entityLinkTypes()](/code/functions/entityLinkTypes.md) | 116 | Every type this resolver can reach. Handy for tests, and for asserting that a new record type has been added here rather than linked to inline. |

## How it hangs together

**Included by:** [api/system/global_search.php](/code/files/api/system/global_search.php.md), [api/tasks/get.php](/code/files/api/tasks/get.php.md), [includes/domains/links.php](/code/files/includes/domains/links.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [includes/record_preview.php](/code/files/includes/record_preview.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

