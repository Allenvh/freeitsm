# includes/projects/budget.php

> PHP - 558 lines

Projects - the budget (3.2.0): planned against actual, in the project's own currency, with labour worked out from the time logged on its tasks.

─────────────────────────────────────────────────────────────────────────────
🔑 FLEXIBLE, WITH NO WAY TO PAINT YOURSELF INTO A CORNER (Ed, 2026-10-08:
"flexible, but not letting someone get in a tricky situation if they change
their mind later"). Every choice here can be changed later without quietly
rewriting what is already recorded:

 - CURRENCY IS STORED ON THE PROJECT (projects.currency), stamped when it is
   created or first budgeted - never derived from the install default on
   read. Changing the default later affects new projects only.
 - AMOUNTS IN DIFFERENT CURRENCIES ARE NEVER ADDED. Anything that totals
   across projects (Report Packs, Watchtower) groups by currency. A contract
   in another currency is shown on its line but not counted.
 - RATES HAVE AN EFFECTIVE-FROM DATE (project_labour_rates). Time logged on a
   day is priced at the rate in force that day, so raising a rate today does
   not re-price last month.
 - HOW LABOUR IS COSTED IS A SETTING read on every view (hours / rate /
   analyst), and labour is never stored as money - switching mode just shows
   the same time differently.
 - Default and analyst rates are in the INSTALL currency; a project rate is
   in the PROJECT's. Time that only an install-currency rate could price, on
   a project in another currency, is shown as unpriced hours - never
   silently converted.

Budget lines hold planned and actual amounts per line. Labour is not a line.

─────────────────────────────────────────────────────────────────────────────
OVER TIME AND THE FORECAST (3.3.0)
 - A line may say WHEN: planned_date (when the money is expected to go out)
   and spent_date (when it went). Undated planned money counts from the
   project's start; undated actual money from the day the line was last saved.
 - A line's FORECAST - what it is now expected to cost in the end - is what
   somebody typed (forecast_amount), otherwise the larger of planned and
   actual: money already spent is not a saving until the line is finished.
 - LABOUR STILL TO COME is the hours left on open tasks' estimates (estimate
   minus the time logged on that task), priced at the rate in force today -
   `projectLabourToCome()`. Off with project_forecast_labour = 0.
 - 🔑 NOTHING IS COUNTED TWICE: a labour-category line with no typed actual is
   the PLAN for labour, so the forecast takes the larger of those lines and
   labour (logged + still to come) - not both.
 - project_cost_basis says whether the cost tolerance measures actual spend
   (the 3.2.0 behaviour, the default) or the forecast.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectBudgetReady()](/code/functions/projectBudgetReady.md) | 57 | True when the budget tables and the projects.currency column exist (after Database Verification). Cached per request. |
| [projectInstallCurrency()](/code/functions/projectInstallCurrency.md) | 68 | The install's currency (Projects -> Settings -> Budget). |
| [projectCurrencyOf()](/code/functions/projectCurrencyOf.md) | 78 | A project's currency: its own, or - not yet stamped - the install's. |
| [projectStampCurrency()](/code/functions/projectStampCurrency.md) | 84 | Stamp the install currency on a project that has none, so a later change of default cannot relabel it. |
| [projectLabourRates()](/code/functions/projectLabourRates.md) | 92 | Every rate, [scope][ref] => [[from, rate], ...] newest first. Cached per request. |
| [projectLabourRatesReset()](/code/functions/projectLabourRatesReset.md) | 105 | Forget the cached rates - after a rate is saved in the same request. |
| [projectRateOn()](/code/functions/projectRateOn.md) | 118 | The rate in force on `$date` for one scope/ref, or null. |
| [projectLabour()](/code/functions/projectLabour.md) | 132 | Labour for projects: time logged on their tasks, and what it cost. |
| [projectBudgetTotals()](/code/functions/projectBudgetTotals.md) | 179 | Budget totals for many projects at once - for health, exceptions, reports. |
| [projectLineActual()](/code/functions/projectLineActual.md) | 228 | One line's actual and where it came from: [amount\|null, 'typed'\|'contract'\|null, mismatch?]. A typed actual wins; otherwise a linked contract's value - only in the same currency. |
| [projectLineForecast()](/code/functions/projectLineForecast.md) | 245 | A line's forecast: what was typed, else the larger of planned and actual; null when it has neither (3.3.0). |
| [projectLineCoversLabour()](/code/functions/projectLineCoversLabour.md) | 260 | A labour line with no typed actual is the PLAN for labour: the time logged on the project's tasks is what it is spent on, so the forecast takes the larger of these lines and labour, never both (3.3.0). |
| [projectLabourToCome()](/code/functions/projectLabourToCome.md) | 274 | Labour still to come (3.3.0): the hours left on open tasks' estimates - each task's estimate less the time logged on that task - priced at the rate in force today, the same rules as `projectLabour()`. In hours mode, or where no rate applies, the hours are counted and the cost is left out. |
| [projectBudgetTimeline()](/code/functions/projectBudgetTimeline.md) | 330 | Spend over time for the chart (3.3.0): cumulative planned and actual by day, with the forecast from today to the target finish and the lines to measure against. Undated planned money counts from the start; undated actual money from the day its line was last saved; labour on the day it was logged. |
| [projectEarnedValue()](/code/functions/projectEarnedValue.md) | 380 | Earned value (3.3.0), from what the budget already holds: BAC budget at completion - the planned total PV planned value by date - the cumulative planned spend (the timeline's planned line) EV earned value by date - BAC x the share of today's whole scope that was done by then: by estimated hours when the project's tasks have estimates, otherwise by count. It only ever rises. AC actual cost by date - the cumulative actual (the timeline's actual line) Today's indices: CPI = EV / AC (above 1 = under budget), SPI = EV / PV (above 1 = ahead), CV = EV - AC, SV = EV - PV, EAC = BAC / CPI, VAC = BAC - EAC. Null when there is no planned budget or no work to measure. |
| [projectBudgetDetail()](/code/functions/projectBudgetDetail.md) | 437 | Everything the Budget tab shows. |
| [projectBudgetCostCentres()](/code/functions/projectBudgetCostCentres.md) | 513 | Active cost centres of the project's company (NULL company = the Default one). |
| [projectBudgetContracts()](/code/functions/projectBudgetContracts.md) | 524 | The contracts linked to the project on Connections - what a line may name. |
| [projectMoney()](/code/functions/projectMoney.md) | 541 | A money amount from input: null for empty, else a number with at most two decimals. |
| [projectValidCurrency()](/code/functions/projectValidCurrency.md) | 554 | ISO 4217 code check (three letters). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `projectBudgetReady` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 61 | `projectBudgetReady` | read [project_labour_rates](/code/tables/project_labour_rates.md) |  |
| 61 | `projectBudgetReady` | read [projects](/code/tables/projects.md) |  |
| 87 | `projectStampCurrency` | **update** [projects](/code/tables/projects.md) (`currency`) |  |
| 97 | `projectLabourRates` | read [project_labour_rates](/code/tables/project_labour_rates.md) |  |
| 142 | `projectLabour` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md) |  |
| 184 | `projectBudgetTotals` | read [project_budget_lines](/code/tables/project_budget_lines.md), read [contracts](/code/tables/contracts.md) |  |
| 285 | `projectLabourToCome` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 384 | `projectEarnedValue` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 441 | `projectBudgetDetail` | read [project_budget_lines](/code/tables/project_budget_lines.md), read [contracts](/code/tables/contracts.md), read [cost_centres](/code/tables/cost_centres.md) |  |
| 517 | `projectBudgetCostCentres` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 527 | `projectBudgetContracts` | read [project_contracts](/code/tables/project_contracts.md), read [contracts](/code/tables/contracts.md) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md)

**Included by:** [api/projects/export.php](/code/files/api/projects/export.php.md), [api/projects/get.php](/code/files/api/projects/get.php.md), [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md), [api/projects/settings.php](/code/files/api/projects/settings.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [projectSetting()](/code/functions/projectSetting.md)

## Traps

> [!WARNING]
> **Line 397, in `projectEarnedValue`:** the share is measured against TODAY's whole scope, never the scope as it stood on that date. Measuring against "tasks that existed by then" made earned value FALL whenever work was added (6 of 10 done = 60%, add 10 tasks = 30%), and earned value never falls - the work done is still done. Today's figures are the same either way; only the history changes, and now it can only rise.

