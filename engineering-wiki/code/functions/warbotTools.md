# warbotTools()

The tool registry: every read-only tool Warbot and the MCP server can call, keyed by name, each with the description the model reads, its JSON schema, the capability needed to run it (null = War Room access is enough) and the handler that answers it.

```php
function warbotTools(): array
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 51.

**Returns:** array<string,array{description:string,schema:array,capability:?string,handler:callable}>

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpTools()](/code/functions/mcpTools.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolsFor()](/code/functions/warbotToolsFor.md), [warbotRunTool()](/code/functions/warbotRunTool.md)

