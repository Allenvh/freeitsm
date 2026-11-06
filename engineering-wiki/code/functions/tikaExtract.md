# tikaExtract()

Ask Tika for the text of a file.

⚠️ THE RETURN DISTINGUISHES THREE OUTCOMES, AND THAT IS THE POINT:

  ['ok' => true,  'text' => '…']              it read the file
  ['ok' => false, 'retry' => true,  …]        Tika is unreachable or timed out
  ['ok' => false, 'retry' => false, …]        Tika answered and could not read it

A caller must write `pending` for retry=true and `failed` for retry=false. If a
five-minute outage marked every PDF that arrived during it as `failed`, those
files would be permanently blacklisted and never looked at again — the index
would be quietly wrong forever and nothing would say so.

```php
function tikaExtract(PDO $conn, string $path, string $filename): array
```

Defined in [includes/search/tika.php](/code/files/includes/search/tika.php.md), line 132.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$path` | `string` |  | the full path of the file on disk, already checked to be inside the attachments directory |
| `$filename` | `string` |  | the original file name, sent to Tika as a hint for choosing a parser |

**Returns:** array{ok:bool,text:string,retry:bool,error:string}

## Calls

[attTextNormalise()](/code/functions/attTextNormalise.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [tikaTimeout()](/code/functions/tikaTimeout.md), [tikaUrl()](/code/functions/tikaUrl.md)

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextDrain()](/code/functions/documentTextDrain.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md)

