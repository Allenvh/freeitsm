# includes/projects/visibility.php

> PHP - 109 lines

Members-only projects (3.3.0) - the ONE place that decides who may see a project beyond its company.

projects.visibility is 'everyone' (the default: anybody who can open Projects
in the project's company, as before) or 'members'. A members-only project is
seen only by its team - the project manager, whoever created it, and its
members, directly or through a team (projectIsTeam's rule) - and by people
who manage Projects (admins always do).

🔑 Everything that lists or opens projects asks here: the portfolio
(projectListRows), the project page (ProjectsService::loadForActor), global
search, People, the recent trail, Documents, Watchtower, Warbot, MCP, the
REST API and Report Packs. A project somebody may not see is NOT FOUND to
them, never "hidden". The shared Calendar never shows a members-only
project's dates; Capacity still counts its work (people's load is real) but
names it only to those who may see it.

What it does not hide: tasks stay Tasks records, seen by whoever Tasks lets
see them (an assignee who is not a member still sees their task).

A system actor (analyst id 0 - scheduled work, demo import) sees everything
unless the caller says otherwise (Report Packs do: a pack never carries a
members-only project unless the person it is built for may see it).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectVisibilities()](/code/functions/projectVisibilities.md) | 34 | The visibility a project can have: everyone, or members only. |
| [projectVisibilityReady()](/code/functions/projectVisibilityReady.md) | 40 | Has Database Verification added projects.visibility? Before it, everything is visible. |
| [projectVisibilityColumn()](/code/functions/projectVisibilityColumn.md) | 55 | p.visibility, or 'everyone' before Verification - for a SELECT list. |
| [projectSeesAllPrivate()](/code/functions/projectSeesAllPrivate.md) | 61 | May this analyst see every members-only project? (Manage Projects; admins.) |
| [projectVisibleSql()](/code/functions/projectVisibleSql.md) | 75 | " AND (...)" limiting `$alias` to the projects this analyst may see, with its arguments. Empty when nothing is hidden from them. |
| [projectVisibleTo()](/code/functions/projectVisibleTo.md) | 92 | May this analyst see this project row (needs id, owner_analyst_id, created_by_id, visibility)? |
| [projectIdVisibleTo()](/code/functions/projectIdVisibleTo.md) | 100 | The same, by id - for callers holding only an id (Documents, the recent trail). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `projectVisibilityReady` | read [projects](/code/tables/projects.md) |  |
| 80 | `projectVisibleSql` | read [project_members](/code/tables/project_members.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 103 | `projectIdVisibleTo` | read [projects](/code/tables/projects.md) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md)

**Included by:** [api/projects/lookups.php](/code/files/api/projects/lookups.php.md), [api/system/global_search.php](/code/files/api/system/global_search.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

**Calls:** [projectIsManager()](/code/functions/projectIsManager.md), [projectIsTeam()](/code/functions/projectIsTeam.md)

