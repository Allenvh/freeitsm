# api/mcp/index.php

> API endpoint - 153 lines

FreeITSM MCP server (3.2.0) - the Model Context Protocol, so an AI assistant (Claude Code, Claude Desktop, any MCP client) can read FreeITSM as the analyst an API key belongs to.

TRANSPORT: MCP "Streamable HTTP", stateless. One URL; the client POSTs a
JSON-RPC 2.0 message and gets a JSON reply (no event stream - every tool here
answers at once, so there is nothing to stream). No session is kept: each
request is authenticated on its own, exactly like the REST API.

  initialize                 -> protocol version, capabilities (tools), server info
  notifications/initialized  -> 202, no body
  ping                       -> {}
  tools/list                 -> the tools THIS key may use (includes/mcp/tools.php)
  tools/call                 -> runs one; the answer is text content

AUTH: the REST API's keys (System -> API), as "Authorization: Bearer <key>",
needing the `mcp.read` permission. Same expiry, rate limit and last-used stamp
(apiAuthenticate). What a key may then see is decided per tool - the
analyst's module access and capabilities, and the key's company scope - in
includes/mcp/tools.php. Read-only: nothing here writes.

⚠️ ORIGIN: a request from a web page carrying an Origin that is not this
server's own Host is refused; MCP clients send none. Said plainly (security
review): this does NOT stop DNS rebinding - there the Origin and the Host are
both the attacker's name. What protects this endpoint from a browser is that
it takes ONLY a bearer key: no cookies, no CORS headers, OPTIONS is 405, so a
page can never send a credential here. This check is a second, cheap fence.

## The endpoint

| | |
|---|---|
| URL | `api/mcp/index.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mcpReply()](/code/functions/mcpReply.md) | 51 | Sends an HTTP status and, when given, a JSON body, then stops. |
| [mcpResult()](/code/functions/mcpResult.md) | 66 | Sends a JSON-RPC 2.0 success response for request `$id`, then stops. |
| [mcpRpcError()](/code/functions/mcpRpcError.md) | 75 | Sends a JSON-RPC 2.0 error response (code and message) for request `$id` with HTTP 200, as JSON-RPC expects, then stops. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), [includes/system_name.php](/code/files/includes/system_name.php.md)

**Calls:** [apiAuthenticate()](/code/functions/apiAuthenticate.md), [apiExtractKey()](/code/functions/apiExtractKey.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [mcpEffectiveScope()](/code/functions/mcpEffectiveScope.md), [mcpRunTool()](/code/functions/mcpRunTool.md), [mcpToolsFor()](/code/functions/mcpToolsFor.md), [systemName()](/code/functions/systemName.md)

