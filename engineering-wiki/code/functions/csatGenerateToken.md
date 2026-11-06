# csatGenerateToken()

Generate a token unique to (ticket, response_row). 32-byte random base ensures it's not guessable from ticket id alone; we then HMAC it with the install secret so even a database leak of `token` alone isn't enough — though for the survey URL we just use the random portion since the response row IS the authoritative store. Keeping HMAC available as a verification step in case we ever want to surface the link in places where the DB isn't queried.

```php
function csatGenerateToken(): string
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 51.

**Returns:** `string`

## Called from

- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatSendInChannel()](/code/functions/csatSendInChannel.md), [sendCsatSurvey()](/code/functions/sendCsatSurvey.md)

