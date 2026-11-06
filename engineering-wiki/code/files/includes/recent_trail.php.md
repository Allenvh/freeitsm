# includes/recent_trail.php

> PHP - 572 lines

The recent trail — "how did I get here", not "what did I look at".

GH discussion #124 asked for internal tabs, in the shape the products
FreeITSM is an alternative to draw them: a horizontal strip along the top,
one tab per screen, managed by hand. That was declined — 91 server-rendered
pages cannot carry it without becoming a single-page app, the browser already
does tabs better than any of us would, and a tab strip is a desktop-only idea
in a product whose 16 modules were just made to work at 360px.

What survived was the SENTENCE underneath the request: help somebody get back
to what they were doing when they had to jump out into a different module.

This answers that sentence, and inverts every property of a tab bar on the way
— which is precisely what stops it reading as an imitation of one:

  a tab bar is                     the trail is
  ─────────────────────────────    ────────────────────────────────────────
  automatic, every click a tab     ambient: nothing to open, close or manage
  ephemeral, per browser tab       per ANALYST — survives sign-out, follows
                                   you to another machine
  horizontal, eats vertical space  a pane in the waffle drawer, identical at
                                   360px and 1920px
  accumulates until tidied         capped, ages out, prunes itself

🔑 RECORDS, NOT SCREENS. "APPSVR01", never "Assets". A list of pages visited
is noise; a list of THINGS is the whole point.

⭐ AND IT IS AN OUTLINE, NOT A LIST — Ed's idea, and the thing that makes it
worth building rather than merely useful. Records are grouped under the module
they were viewed in, the way headings and body text sit in a word processor.
Go to Tickets and read three tickets: one "Tickets" heading, three rows under
it. Jump to Knowledge for an article: a "Knowledge" heading with the article
under it. Come BACK to tickets and you get a SECOND, NEW "Tickets" heading —
not an amendment to the first. The repetition is the information: it is what
turns a list of records into a picture of an afternoon's work, and it says
plainly WHY those three tickets were open together.

⚠️ SO THIS IS A LOG, NOT A "RECENTS" LIST, and that is a deliberate departure
from every other most-recently-used list in the product. The same ticket seen
twice in a day MUST appear twice, in two different groups, or the navigation
story it is drawing is a lie. There is therefore no unique key on
(analyst, type, id) — see the schema — and pruning does the job that a unique
key would otherwise have done.

🔴 ACCESS IS RE-CHECKED AT RENDER, NEVER AT WRITE TIME. A record you could
open on Monday and cannot open on Wednesday has to be GONE from Wednesday's
drawer. The rule inherited from record previews (#91) holds here in full:
the MODULE gate first, then the RECORD gate, and a record that is missing must
be indistinguishable from one that is forbidden — otherwise the trail becomes
a way to confirm that a record exists.

⚠️ NOT `activeTenantFilter()`. That answers "is this in the company I am
currently LOOKING AT", which is a view setting rather than a permission, and
would quietly drop records the analyst is perfectly entitled to open.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [entityVisit()](/code/functions/entityVisit.md) | 130 | Record that this analyst has just looked at this record — the PAGE LOAD path. |
| [recentTrailWrite()](/code/functions/recentTrailWrite.md) | 158 | The write itself, with no opinion about what kind of request it is. |
| [recentTrailShouldRecord()](/code/functions/recentTrailShouldRecord.md) | 226 | Is this request a person opening a record page? |
| [recentTrailPrune()](/code/functions/recentTrailPrune.md) | 246 | Keep the log to its cap, by count and by age. |
| [entityRecentTrail()](/code/functions/entityRecentTrail.md) | 286 | The analyst's trail, resolved and grouped into level-1 headings. |
| [recentTrailWithinGap()](/code/functions/recentTrailWithinGap.md) | 370 | Are two visits close enough in time to belong to the same sitting? |
| [recentTrailLabels()](/code/functions/recentTrailLabels.md) | 398 | Resolve a batch of trail rows to labels, dropping every one the analyst may not see. |
| [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md) | 437 | One query for the labels of one type, then the record gate on each. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 181 | `recentTrailWrite` | read [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |
| 192 | `recentTrailWrite` | **update** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) (`visited_datetime`) |  |
| 203 | `recentTrailWrite` | **insert** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) (`analyst_id`, `entity_type`, `entity_id`, `visited_datetime`) |  |
| 249 | `recentTrailPrune` | **delete** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |
| 259 | `recentTrailPrune` | **delete** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |
| 293 | `entityRecentTrail` | read [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |
| 446 | `recentTrailLabelsForType` | read [tickets](/code/tables/tickets.md) |  |
| 452 | `recentTrailLabelsForType` | read [tasks](/code/tables/tasks.md) |  |
| 457 | `recentTrailLabelsForType` | read [problems](/code/tables/problems.md) |  |
| 463 | `recentTrailLabelsForType` | read [changes](/code/tables/changes.md) |  |
| 470 | `recentTrailLabelsForType` | read [assets](/code/tables/assets.md) |  |
| 479 | `recentTrailLabelsForType` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 484 | `recentTrailLabelsForType` | read [domains](/code/tables/domains.md) |  |
| 490 | `recentTrailLabelsForType` | read [projects](/code/tables/projects.md) |  |
| 492 | `recentTrailLabelsForType` | read [projects](/code/tables/projects.md) |  |
| 503 | `recentTrailLabelsForType` | read [users](/code/tables/users.md) |  |
| 508 | `recentTrailLabelsForType` | read [tenants](/code/tables/tenants.md) |  |
| 515 | `recentTrailLabelsForType` | read [suppliers](/code/tables/suppliers.md) |  |
| 520 | `recentTrailLabelsForType` | read [contacts](/code/tables/contacts.md) |  |
| 528 | `recentTrailLabelsForType` | read [contracts](/code/tables/contracts.md) |  |
| 541 | `recentTrailLabelsForType` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/system/recent_trail.php](/code/files/api/system/recent_trail.php.md), [api/system/recent_trail_visit.php](/code/files/api/system/recent_trail_visit.php.md), [cmdb/object.php](/code/files/cmdb/object.php.md), [contracts/view.php](/code/files/contracts/view.php.md), [domains/view.php](/code/files/domains/view.php.md), [people/company.php](/code/files/people/company.php.md), [people/contact.php](/code/files/people/contact.php.md), [people/person.php](/code/files/people/person.php.md), [people/supplier.php](/code/files/people/supplier.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [contractCanView()](/code/functions/contractCanView.md), [entityLink()](/code/functions/entityLink.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [projectIdVisibleTo()](/code/functions/projectIdVisibleTo.md)

