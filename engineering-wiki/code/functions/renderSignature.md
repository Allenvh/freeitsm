# renderSignature()

Resolve a signature body for one analyst.

A code with no value behind it is removed rather than left showing. That is the
opposite of what `renderReplyTemplate()` does, and deliberately so: an unresolved
`[requester_first_name]` sits in an editor where an analyst reads it before
sending, whereas an analyst who has not filled in a mobile number would otherwise
have `[my_mobile]` appear at the bottom of every email they ever send.

```php
function renderSignature(PDO $conn, string $body, int $analystId): string
```

Defined in [includes/signatures.php](/code/files/includes/signatures.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `string` |  | the signature's stored HTML, with [my_*] merge codes |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[signatureMergeData()](/code/functions/signatureMergeData.md)

## Called from

- [api/myaccount/get_signatures.php](/code/files/api/myaccount/get_signatures.php.md)

