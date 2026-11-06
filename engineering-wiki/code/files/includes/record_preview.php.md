# includes/record_preview.php

> PHP - 463 lines

At-a-glance previews of a linked record (discussion #91).

dschipfel asked for linked records to be clickable; that shipped. He then
asked for a preview so you can see what is on the other end of a link without
leaving the page, named the relationships that matter most, and asked for the
fields listed below. Read-only, by his request: "allowing edits from the
preview would add complexity and could increase the risk of accidental
changes."

🔴 A PREVIEW IS A READ. Everything here is a way to see a record you are
looking at a link to — and a link can be to something you are not allowed to
open. So every type checks BOTH the module and the record itself, and an
unreachable record returns exactly what a non-existent one returns: null.
Telling the two apart would confirm that a record exists, which is the thing
the check is there to withhold.

⚠️ ONE function per type, all reached through `recordPreview()`. Wiring a
preview into a new screen must never mean writing a new query.

The fields are the ones promised in the discussion, so they are listed here
as the contract they are:

  Ticket            number, subject, status, priority, who it is with, requester
  Task              title, status, assignee, due date, subtask progress
  Change            title, status, risk, planned window
  Problem           number, title, status, how many tickets are attached
  Asset             tag, make and model, serial, who holds it, warranty
  Contract          number, supplier, renewal date
  Knowledge article title, and the opening lines

## Functions

| Function | Line | What it does |
|---|--:|---|
| [recordPreview()](/code/functions/recordPreview.md) | 57 | A preview, or NULL when the type is unknown, the record does not exist, or this analyst may not see it. One answer for all three, deliberately. |
| [rpField()](/code/functions/rpField.md) | 90 | Shorthand for one line of a preview. |
| [rpFields()](/code/functions/rpFields.md) | 124 | Drop the empties, so callers can just list every field they might have. |
| [recordPreviewTicket()](/code/functions/recordPreviewTicket.md) | 131 | ── Ticket ────────────────────────────────────────────────────────────────── |
| [recordPreviewTask()](/code/functions/recordPreviewTask.md) | 175 | ── Task ──────────────────────────────────────────────────────────────────── |
| [recordPreviewChange()](/code/functions/recordPreviewChange.md) | 222 | ── Change ────────────────────────────────────────────────────────────────── |
| [recordPreviewProblem()](/code/functions/recordPreviewProblem.md) | 262 | ── Problem ───────────────────────────────────────────────────────────────── |
| [recordPreviewAsset()](/code/functions/recordPreviewAsset.md) | 299 | ── Asset ─────────────────────────────────────────────────────────────────── |
| [recordPreviewContract()](/code/functions/recordPreviewContract.md) | 362 | ── Contract ──────────────────────────────────────────────────────────────── |
| [recordPreviewDomain()](/code/functions/recordPreviewDomain.md) | 401 | ── Domain (#154) ─────────────────────────────────────────────────────────── |
| [recordPreviewKnowledgeArticle()](/code/functions/recordPreviewKnowledgeArticle.md) | 435 | ── Knowledge article ─────────────────────────────────────────────────────── |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 141 | `recordPreviewTicket` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |
| 188 | `recordPreviewTask` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 231 | `recordPreviewChange` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 273 | `recordPreviewProblem` | read [problem_tickets](/code/tables/problem_tickets.md), read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 306 | `recordPreviewAsset` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md) |  |
| 338 | `recordPreviewAsset` | read [assets](/code/tables/assets.md) |  |
| 370 | `recordPreviewContract` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [contract_statuses](/code/tables/contract_statuses.md) |  |
| 407 | `recordPreviewDomain` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [analysts](/code/tables/analysts.md), read [suppliers](/code/tables/suppliers.md) |  |
| 444 | `recordPreviewKnowledgeArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/system/record_preview.php](/code/files/api/system/record_preview.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [entityLink()](/code/functions/entityLink.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [t()](/code/functions/t.md)

