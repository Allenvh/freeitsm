# Standard parameters

A handful of parameter names mean the same thing everywhere in FreeITSM. They are explained once, here, rather than in thousands of `@param` lines. When a function's comment does not describe one of these, the Code map shows the meaning below, marked *standard*.

If a function uses one of these names for something else, its own `@param` line wins - so a function that bends the convention should say so.

| Parameter | Meaning |
|---|---|
| `PDO $conn` | The database connection. |
| `int $analystId` | The analyst doing this - normally the one signed in. What they may see and change is checked against it. |
| `ActorContext $ctx` | Who is acting: the analyst (or API key) id and display name, the companies they may reach, whether the call came from the app (`ui`) or the REST API (`api`), and their language. See [[code/classes/ActorContext|ActorContext]]. |
| `?int $tenantId` | A company (tenant) id. `null` means the Default company. |
| `int $projectId`, `int $ticketId`, `int $taskId`, `int $assetId` | The id of that project, ticket, task or asset. |

## In the REST API (`api/v1/`)

Every route handler has the same four parameters, because the router in [api/v1/index.php](../code/files/api/v1/index.php.md) calls them all the same way:

| Parameter | Meaning |
|---|---|
| `PDO $conn` | The database connection. |
| `array $apiKey` | The API key that authenticated the call - its row, including the scopes it was granted and any company it is limited to. |
| `array $params` | The ids captured from the URL path, in order, as integers - `/tickets/42/notes` gives `[42]`. |
| `array $body` | The request's JSON body, decoded (empty for a GET). |
