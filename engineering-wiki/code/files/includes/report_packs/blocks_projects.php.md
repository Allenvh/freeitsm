# includes/report_packs/blocks_projects.php

> PHP - 291 lines

Report Packs: Projects blocks (3.2.0) - so the monthly board pack includes the projects without anybody copying figures out of the portfolio.

Health and progress are worked out exactly as the Projects screens work them
out: the same `projectTaskStats()` + `projectDecorate()` (includes/projects/read.php),
so a pack can never show a project green that the portfolio shows red.

WHAT THE DATE RANGE MEANS
  status, risks, the summary's health tiles, the health chart
      a snapshot of NOW - health has no history to look back on - and the
      blocks say so (`snapshot`);
  milestones, the summary's "stages ending" tile
      milestones (3.3.0), stage ends and target finishes INSIDE the range, so
      last month's pack lists last month's milestones and whether they were met.

Company: the pack's company, through projects.tenant_id (NULL = Default), the
same `rpTenantClause()` every other area uses. A `projects` option narrows to
chosen projects; empty means all of them.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpProjectRows()](/code/functions/rpProjectRows.md) | 34 | Projects in the pack's company, decorated with health and progress. |
| [rpProjectClause()](/code/functions/rpProjectClause.md) | 67 | The company clause plus members-only (3.3.0): a pack carries a members-only project only when the person it is built for may see it - never for nobody. |
| [rpProjectHealthPill()](/code/functions/rpProjectHealthPill.md) | 80 | A health pill: the words the Projects module uses, in its colours. |
| [rpProjectsKpis()](/code/functions/rpProjectsKpis.md) | 94 | Projects block: KPI tiles - live projects, how many are green, amber and red now, and stages ending in the period. |
| [rpProjectsHealth()](/code/functions/rpProjectsHealth.md) | 121 | Projects block: a chart of live projects by health (green, amber, red). A snapshot of today. |
| [rpProjectsStatus()](/code/functions/rpProjectsStatus.md) | 144 | Projects block: a table of projects with health, progress, current stage, target date and, optionally, manager and the reason for the health. |
| [rpProjectsMilestones()](/code/functions/rpProjectsMilestones.md) | 184 | Stage ends and target finishes inside the range, and whether each was met. |
| [rpProjectsRisks()](/code/functions/rpProjectsRisks.md) | 237 | The open risks that matter most, across the chosen projects. |
| [rpProjectChoices()](/code/functions/rpProjectChoices.md) | 280 | The projects offered by a block's "Projects" option: those the viewer can see, live first. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `rpProjectRows` | read [project_stages](/code/tables/project_stages.md) |  |
| 47 | `rpProjectRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 101 | `rpProjectsKpis` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 192 | `rpProjectsMilestones` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 200 | `rpProjectsMilestones` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |
| 243 | `rpProjectsRisks` | read [project_raid](/code/tables/project_raid.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 286 | `rpProjectChoices` | read [projects](/code/tables/projects.md) |  |

## How it hangs together

**Includes:** [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md)

**Included by:** [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md)

**Calls:** [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectCode()](/code/functions/projectCode.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [rpFmtDate()](/code/functions/rpFmtDate.md), [rpTenantClause()](/code/functions/rpTenantClause.md), [t()](/code/functions/t.md)

