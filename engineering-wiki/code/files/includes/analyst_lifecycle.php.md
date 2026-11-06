# includes/analyst_lifecycle.php

> PHP - 716 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Analysts Leaving](https://github.com/edmozley/freeitsm/wiki/Analysts-Leaving)

When an analyst leaves: Deactivate, Anonymise or Delete (GH #178).

Everything in the database that refers to an analyst falls into one of these:

  personal  their own settings and memberships - dashboard widgets, saved
            views, preferences, team and module membership, folder grants.
            Useless without them. Removed on Delete and Anonymise.
  secret    sign-in material - SSO links, trusted devices, reset tokens, API
            keys. Removed on Delete and Anonymise. (Deactivate already stops
            all of it working: sign-in and the REST API both refuse an
            inactive analyst.)
  live      open work assigned to them - open tickets, problems, changes and
            tasks, articles, projects, contracts and domains they own. Offered
            for reassignment through each module's own service, so the history,
            notifications and workflow triggers are exactly a manual reassign's.
  setup     something routed to them that a person has to rethink - a form's
            approver, an SLA rule that notifies them, a CAB seat on an open
            change, future rota shifts, equipment still assigned to them.
            Listed, never changed automatically: clearing a form's approver
            could let approvals through unchecked.
  history   who did what - notes, time, audit trails, "created by". Kept, and
            still credited to them. This is why most leavers are DEACTIVATED:
            deleting would leave a hole in the record.

Delete is only possible when nothing in `history` or `setup` refers to them
(once their live work has been moved): an account made by mistake, a test
login. Otherwise the answer is Deactivate (reversible, names kept) or
Anonymise (irreversible: the records stay, the person becomes "Former
analyst #N").

TRAP: every column that refers to an analyst must be in `analystReferenceRegistry()`.
  tests/analyst-lifecycle.php fails on any it does not know, because a missing one
  is either a delete that errors (fresh installs, with foreign keys) or a dangling
  reference (installs without them).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [analystFormerName()](/code/functions/analystFormerName.md) | 44 | The placeholder an anonymised analyst becomes, e.g. "Former analyst #42". |
| [analystReferenceRegistry()](/code/functions/analystReferenceRegistry.md) | 56 | Every column that refers to an analyst, and what kind of reference it is. Each row: [table, column, kind] or [table, column, kind, extraWhere] where extraWhere narrows a polymorphic or partly-live column (trusted SQL, no placeholders). See the file header for the kinds. |
| [analystNameCopies()](/code/functions/analystNameCopies.md) | 306 | Text columns that hold a COPY of an analyst's name next to their id. Anonymise replaces the copy where the id says it is theirs. [table, nameColumn, idColumn]. Plus the four audit tables' old/new values, replaced where the WHOLE value is the old name (an assignment change records names, not ids) - see `analystAnonymise()`. Names typed into free text (notes, emails) are not found. |
| [analystNameAuditTables()](/code/functions/analystNameAuditTables.md) | 315 | Audit tables whose old_value / new_value record assignment changes by NAME. |
| [analystLcColumnExists()](/code/functions/analystLcColumnExists.md) | 326 | Does this table.column exist on this install? Installs differ (a module's tables arrive with Database Verification), so every query checks first. |
| [analystLcCount()](/code/functions/analystLcCount.md) | 345 | How many rows of one registry entry refer to this analyst. |
| [analystLiveGroups()](/code/functions/analystLiveGroups.md) | 364 | The open work that can be handed on, one group per kind of thing, each moved through its module's own service. 'count' is how many rows the group holds. |
| [analystLcIds()](/code/functions/analystLcIds.md) | 397 | The ids of the rows one registry entry matches (for the live groups). |
| [analystSetupLabels()](/code/functions/analystSetupLabels.md) | 411 | Plain-English names for the setup items, by table. |
| [analystFootprint()](/code/functions/analystFootprint.md) | 435 | Everything the screen needs to explain what Deactivate, Anonymise and Delete would do to this analyst. |
| [analystReassignLive()](/code/functions/analystReassignLive.md) | 494 | Hand an analyst's open work to another analyst, or to nobody (null), through each module's own service. Per item, so one refusal (a project the acting analyst may not change, say) does not stop the rest. Returns what moved and what did not. |
| [analystRemovePersonal()](/code/functions/analystRemovePersonal.md) | 563 | Remove everything of kind personal and secret for this analyst. |
| [analystDeactivate()](/code/functions/analystDeactivate.md) | 585 | Deactivate: no sign-in, no access, API keys refused - and every name kept. Reversible by ticking Active again. Moving their open work is a separate, optional step (analystReassignLive()), done first by the screen. |
| [analystAnonymise()](/code/functions/analystAnonymise.md) | 601 | Anonymise: everything Deactivate does, then the person behind the records is removed - personal settings and sign-in material deleted, the account's name and contact details replaced, and copies of the name next to their id replaced. The records themselves stay, credited to "Former analyst #N". Irreversible. |
| [analystDelete()](/code/functions/analystDelete.md) | 654 | Delete: only for an analyst nothing in history or setup refers to (once their open work has been moved). Personal settings and sign-in material go with them, in one transaction. Anything else refers to them -> refused, with the reason, so the screen can offer Deactivate or Anonymise instead. |
| [analystLcAssertActionable()](/code/functions/analystLcAssertActionable.md) | 682 | The checks every action shares: the analyst exists, it is not you, and it is not the last active administrator. |
| [analystLcLog()](/code/functions/analystLcLog.md) | 706 | Record the action in the system log - by id only. Writing the name here would keep, in the log of an anonymisation, the very thing it removed. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 | `analystReferenceRegistry` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 58 | `analystReferenceRegistry` | read [change_statuses](/code/tables/change_statuses.md) |  |
| 59 | `analystReferenceRegistry` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 60 | `analystReferenceRegistry` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 114 | `analystReferenceRegistry` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 116 | `analystReferenceRegistry` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 125 | `analystReferenceRegistry` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 127 | `analystReferenceRegistry` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 436 | `analystFootprint` | read [analysts](/code/tables/analysts.md) |  |
| 466 | `analystFootprint` | read [analysts](/code/tables/analysts.md) |  |
| 497 | `analystReassignLive` | read [analysts](/code/tables/analysts.md) |  |
| 534 | `analystReassignLive` | **update** [contracts](/code/tables/contracts.md) (`contract_owner_id`) |  |
| 539 | `analystReassignLive` | read [domains](/code/tables/domains.md) |  |
| 587 | `analystDeactivate` | **update** [analysts](/code/tables/analysts.md) (`is_active`, `last_modified_datetime`) |  |
| 603 | `analystAnonymise` | read [analysts](/code/tables/analysts.md) |  |
| 612 | `analystAnonymise` | **update** [analysts](/code/tables/analysts.md) (`username`, `full_name`, `email`, `job_title`, `department`, `phone`, `mobile`, `password_hash`, `totp_secret`, `totp_enabled`, `trust_device_enabled`, `auth_provider_id`) |  |
| 666 | `analystDelete` | **delete** [analysts](/code/tables/analysts.md) |  |
| 686 | `analystLcAssertActionable` | read [analysts](/code/tables/analysts.md) |  |
| 691 | `analystLcAssertActionable` | read [analysts](/code/tables/analysts.md) |  |
| 709 | `analystLcLog` | **insert** [system_logs](/code/tables/system_logs.md) (`log_type`, `analyst_id`, `details`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md)

**Included by:** [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md), [api/tickets/delete_analyst.php](/code/files/api/tickets/delete_analyst.php.md)

