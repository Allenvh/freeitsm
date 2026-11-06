# d018RunRealEndpoint()

Call the real counts endpoint as the signed-in analyst and return its decoded answer. Runs it in this request (no HTTP round trip), from its own folder so its relative require paths resolve, inside a function so its variables stay out of this file's.

TRAP: only ever for the analyst who is signed in. Running it for somebody else
would mean putting their id into the admin's live session, and a fatal error
part-way through would leave the admin signed in as them.

```php
function d018RunRealEndpoint(): array
```

Defined in [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md), line 120.

**Returns:** array{ok:bool,data:?array,raw:string}

## Called from

- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)

