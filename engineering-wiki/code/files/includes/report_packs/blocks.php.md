# includes/report_packs/blocks.php

> PHP - 476 lines

Report Packs: the block registry - everything that can be dragged into a pack.

TWO LEVELS, ON PURPOSE
----------------------
  handlers  what a block IS: its module, its options, and the PHP function that
            fetches its data. A pack stores the handler key and the options.
  toolbox   what the designer OFFERS: a searchable, previewable item that drops
            in a handler with options already chosen. "Tickets by status" and
            "Tickets by priority" are two toolbox items over one handler, so
            either can be turned into the other later from the Properties pane,
            the way a Word chart changes type without being re-inserted.

Adding a block is one handler entry plus one or more toolbox entries, plus its
function in the area's file. Nothing in the designer or the PDF engine changes:
they draw by the data's `kind` (chart / kpi / table / uptime), not by block.

MODULE ACCESS
-------------
Each handler names the module whose data it shows. The toolbox only offers what
the viewer may use, and api/reporting/packs/block_data.php refuses the rest - so
a pack shared with someone who lacks Contracts shows them a placeholder, never
the figures. 'reporting' is everyone who can open Report Packs at all.

Every data function receives (`$conn`, `$analystId`, `$opts`, `$range`, `$tenant`) and
returns an array with a `kind`. `$opts` has already been validated against the
handler's option schema, so a function never sees a value it did not declare.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpOptSelect()](/code/functions/rpOptSelect.md) | 47 | Option helpers, so the handler table below stays readable. |
| [rpOptInt()](/code/functions/rpOptInt.md) | 59 | A whole-number block option, between `$min` and `$max`. |
| [rpOptBool()](/code/functions/rpOptBool.md) | 69 | A yes/no block option. |
| [rpOptMulti()](/code/functions/rpOptMulti.md) | 79 | A multi-select block option whose choices come from the named source. |
| [rpChartOpt()](/code/functions/rpChartOpt.md) | 88 | The chart-type option for a block, offering every chart type with `$default` selected. |
| [rpHandlers()](/code/functions/rpHandlers.md) | 99 | Handlers, keyed. `module` gates the data; `kinds` lists what it can return (for the designer's preview); `fn` fetches it; `opts` is the option schema. |
| [rpToolboxItems()](/code/functions/rpToolboxItems.md) | 297 | The toolbox: [key => [handler, preset opts, area, default span, keywords]]. Titles and descriptions come from lang/en/reporting.php (packs.tool.<key>). |
| [rpCleanOpts()](/code/functions/rpCleanOpts.md) | 360 | Validate a block's options against its handler's schema. Unknown keys are dropped, out-of-range values clamped, missing ones defaulted - so the data function, the PDF and the saved design all see the same, legal values. |
| [rpBlockData()](/code/functions/rpBlockData.md) | 385 | Fetch one block's data for this viewer. Throws RuntimeException with a message fit to show in the block when the viewer may not see it. |
| [rpModuleName()](/code/functions/rpModuleName.md) | 404 | A module's display name, for "You do not have access to X". |
| [rpOptionSource()](/code/functions/rpOptionSource.md) | 415 | Choices for a 'multi' option's source (the Properties pane's checkboxes). |
| [rpCatalogue()](/code/functions/rpCatalogue.md) | 435 | The toolbox and handler schemas as the designer needs them, filtered to what this viewer may use. Blocks they cannot use are still DESCRIBED (so a shared pack can show "needs Contracts") but marked unavailable. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 422 | `rpOptionSource` | read [status_services](/code/tables/status_services.md) |  |

## How it hangs together

**Includes:** [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md), [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md), [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md), [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md)

**Included by:** [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [getModuleRegistry()](/code/functions/getModuleRegistry.md), [rpProjectChoices()](/code/functions/rpProjectChoices.md), [rpResolveRange()](/code/functions/rpResolveRange.md), [t()](/code/functions/t.md)

