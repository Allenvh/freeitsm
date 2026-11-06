# domainRunChecks()

Run every check for one domain row.

```php
function domainRunChecks(array $d, array $settings): array
```

Defined in [includes/domains/checks.php](/code/files/includes/domains/checks.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` | `array` |  | the `domains` row |
| `$settings` | `array` |  | `domainSettings()` |

**Returns:** array{findings:array, score:int, grade:string, ssl_expiry_date:?string, ssl_issuer:?string, snapshot:array, certificates:array}

## Calls

[domainDns()](/code/functions/domainDns.md), [domainGrade()](/code/functions/domainGrade.md), [domainParseSslHosts()](/code/functions/domainParseSslHosts.md), [domainParseTags()](/code/functions/domainParseTags.md), [domainPurposeIsNonMail()](/code/functions/domainPurposeIsNonMail.md), [domainSpfLookupCount()](/code/functions/domainSpfLookupCount.md), [domainTlsCertificate()](/code/functions/domainTlsCertificate.md), [domainTransferLockNotApplicable()](/code/functions/domainTransferLockNotApplicable.md), [domainTxtStartingWith()](/code/functions/domainTxtStartingWith.md)

## Called from

- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::runChecks`

