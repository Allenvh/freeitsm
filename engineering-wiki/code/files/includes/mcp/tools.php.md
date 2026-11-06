# includes/mcp/tools.php

> PHP - 371 lines

The MCP server's tools (3.2.0) - api/mcp/index.php is the protocol, this is what it offers.

🔑 A THIN ADAPTER OVER THE WARBOT REGISTRY (includes/warbot/tools.php), as
that file always intended: the same names, descriptions, schemas and plain PHP
handlers, plus a few tools only an MCP client gets (Projects, below).

🔑 READ-ONLY, EVERY ONE. Nothing here writes. An assistant reading tickets
can be fed instructions by whoever wrote the ticket; read-only makes that
embarrassing rather than dangerous. Writes are a later, separate decision.

─────────────────────────────────────────────────────────────────────────────
🔴 THE GATE THIS FILE ADDS, AND WHY WARBOT DID NOT NEED IT

Warbot answers in a war-room channel and checks only that the asker can open
the war room. Its tools are NOT company-scoped - its own header leaves "whose
data" open - and they do not check the module behind each answer. Exposed
as they are over MCP, an analyst limited to one company could read another
company's tickets through their assistant. So every tool here is declared
with:

  module        the module whose data it reads - the key's analyst must be
                able to open it (analystCanAccessModule);
  company_safe  true when the tool either reads install-wide data (services,
                suppliers, the rota) or scopes itself (Knowledge visibility,
                the Projects tools below). A tool that is NOT company-safe is
                offered on a multi-company install only to a key that sees
                every company (company_scope null) - never narrowed by guess.
                company_scope here is the EFFECTIVE one (mcpEffectiveScope):
                the key's companies narrowed to its analyst's.

Plus the key's own API permission (mcp.read) and Warbot's capability rule. A
tool the key may not run is not LISTED, rather than listed and refused: an
assistant told about a tool it cannot use keeps reaching for it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mcpWarbotToolMap()](/code/functions/mcpWarbotToolMap.md) | 44 | name => [module, company_safe] for the Warbot tools. A Warbot tool not listed here is not offered. |
| [mcpTools()](/code/functions/mcpTools.md) | 69 | Every tool: [description, schema, module, company_safe, capability, handler(conn, args, analystId, apiKey)]. |
| [mcpEffectiveScope()](/code/functions/mcpEffectiveScope.md) | 156 | 🔴 THE KEY'S EFFECTIVE COMPANIES (security review, 2026-10-09). A key's own company_scope is null when an administrator left it on "all companies" - and the REST API reads null as EVERY company, even when the analyst the key acts as is limited to one. The MCP server promises "you see what that analyst may see", so it intersects: the key's companies AND the analyst's. |
| [mcpToolAllowed()](/code/functions/mcpToolAllowed.md) | 170 | May this key run this tool? (permission is checked once, by the endpoint) |
| [mcpToolsFor()](/code/functions/mcpToolsFor.md) | 184 | tools/list: what this key may use, in MCP's shape. |
| [mcpRunTool()](/code/functions/mcpRunTool.md) | 209 | tools/call. Returns [text, isError]. An unknown or forbidden tool is null - the endpoint answers that as a protocol error, not as tool output. |
| [mcpProjectScopeSql()](/code/functions/mcpProjectScopeSql.md) | 234 | " AND p.tenant_id ..." for the key's companies (NULL company = the Default one). |
| [mcpProjectCompanySql()](/code/functions/mcpProjectCompanySql.md) | 251 | The company part of the MCP project filter - limits projects to the API key's companies. |
| [mcpToolListProjects()](/code/functions/mcpToolListProjects.md) | 268 | MCP tool: lists the projects the API key may see. |
| [mcpToolProjectOverview()](/code/functions/mcpToolProjectOverview.md) | 280 | MCP tool: one project's overview. |
| [mcpToolProjectRaid()](/code/functions/mcpToolProjectRaid.md) | 292 | MCP tool: a project's RAID log - risks, assumptions, issues and dependencies. |
| [mcpToolProjectTasks()](/code/functions/mcpToolProjectTasks.md) | 304 | MCP tool: a project's tasks. |
| [mcpToolProjectDates()](/code/functions/mcpToolProjectDates.md) | 316 | MCP tool: a project's key dates. |
| [mcpToolProjectBudget()](/code/functions/mcpToolProjectBudget.md) | 328 | The budget stays MCP-only: Warbot answers in a channel everyone in it can read. |
| [mcpToolSearchKnowledge()](/code/functions/mcpToolSearchKnowledge.md) | 356 | KNOWLEDGE - by the KEY's companies (KnowledgeViewer::forApiKey), not the analyst's active company, which an API call does not have. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 363 | `mcpToolSearchKnowledge` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/mcp/index.php](/code/files/api/mcp/index.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [apiEffectiveCompanyScope()](/code/functions/apiEffectiveCompanyScope.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [projectAssistDates()](/code/functions/projectAssistDates.md), [projectAssistFind()](/code/functions/projectAssistFind.md), [projectAssistList()](/code/functions/projectAssistList.md), [projectAssistMoney()](/code/functions/projectAssistMoney.md), [projectAssistOverview()](/code/functions/projectAssistOverview.md), [projectAssistRaid()](/code/functions/projectAssistRaid.md), [projectAssistTasks()](/code/functions/projectAssistTasks.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [warbotTools()](/code/functions/warbotTools.md)

