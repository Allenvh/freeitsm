# includes/report_packs/access.php

> PHP - 264 lines

Report Packs: who may open, change and share a pack.

A pack is PRIVATE to the analyst who made it until they share it. A share names
one of three kinds of people:

  analyst     one analyst                         target_id = analysts.id
  team        everyone in a team                  target_id = teams.id
  department  everyone whose profile says it      target_value = analysts.department

"Department" is the free-text field on an analyst's profile (often filled in by
directory sync), NOT the ticket-routing departments table - see the wiki page
Groups-of-People-Developer-Guide for why FreeITSM has both. It is matched
case-insensitively and trimmed, because a directory writes "IT Services" and a
person types "it services".

Each share is View (open and export) or Edit (also change the design). Only the
owner shares, renames into a new owner, or deletes. A pack whose owner has been
deleted is orphaned (owner_id NULL by the foreign key); administrators can manage
those so nothing is left that nobody can remove.

⚠️ Access to a PACK is not access to its DATA. Every block is fetched with the
viewer's own module access and companies (api/reporting/packs/block_data.php), so
sharing a pack never shows anybody a figure they could not already see.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpViewer()](/code/functions/rpViewer.md) | 31 | The teams and department this analyst counts as, for matching shares. |
| [rpNormDept()](/code/functions/rpNormDept.md) | 61 | A department name normalised for matching: spaces collapsed, trimmed and lower case. |
| [rpShareMatchSql()](/code/functions/rpShareMatchSql.md) | 72 | SQL that matches the shares reaching this viewer, for `report_pack_shares s`. |
| [rpRole()](/code/functions/rpRole.md) | 93 | This viewer's role on a pack: 'owner', 'edit', 'view', or null (no access, or no such pack - the caller says "not found" for both, so ids cannot be probed). |
| [rpRoleAtLeast()](/code/functions/rpRoleAtLeast.md) | 119 | True if `$role` is at least `$need` ('view' < 'edit' < 'owner'). |
| [rpListPacks()](/code/functions/rpListPacks.md) | 128 | Every pack this analyst can open, newest change first, with their role on each. |
| [rpListShares()](/code/functions/rpListShares.md) | 175 | A pack's shares, with a display name for each target. Owner only. |
| [rpSaveShares()](/code/functions/rpSaveShares.md) | 213 | Replace a pack's shares with `$shares` (the whole list, as the dialog sends it). Each entry is validated against what exists; unknown targets are dropped, never stored, so a share cannot point at an id that a later analyst or team would inherit. |
| [rpKnownDepartments()](/code/functions/rpKnownDepartments.md) | 250 | The departments analysts actually have, for the share picker. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `rpViewer` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 43 | `rpViewer` | read [analysts](/code/tables/analysts.md) |  |
| 95 | `rpRole` | read [report_packs](/code/tables/report_packs.md) |  |
| 106 | `rpRole` | read [report_pack_shares](/code/tables/report_pack_shares.md) |  |
| 134 | `rpListPacks` | read [report_pack_shares](/code/tables/report_pack_shares.md), read [report_packs](/code/tables/report_packs.md), read [analysts](/code/tables/analysts.md) |  |
| 178 | `rpListShares` | read [report_pack_shares](/code/tables/report_pack_shares.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 237 | `rpSaveShares` | **delete** [report_pack_shares](/code/tables/report_pack_shares.md) |  |
| 238 | `rpSaveShares` | **insert** [report_pack_shares](/code/tables/report_pack_shares.md) (`pack_id`, `target_type`, `target_id`, `target_value`, `can_edit`) |  |
| 252 | `rpKnownDepartments` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Included by:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

