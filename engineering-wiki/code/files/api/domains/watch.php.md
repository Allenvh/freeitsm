# api/domains/watch.php

> API endpoint - 44 lines

The outside-world watches on one domain.

POST {id, action:'scan_ct'}            ask crt.sh now (slow — up to 45s)
POST {id, action:'ack_ct'}             mark every certificate as seen
POST {id, action:'scan_lookalikes'}    resolve the look-alike candidates now
POST {id, action:'dismiss', lookalike_id, dismissed:bool}

Manual scans work even when the scheduled watches are off: switching the
schedule off is "don't do this for every domain every week", not "never".

## The endpoint

| | |
|---|---|
| URL | `api/domains/watch.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | **delete** [domain_audit](/code/tables/domain_audit.md) |  |
| 29 |  | **update** [domain_certificates](/code/tables/domain_certificates.md) (`acknowledged`) |  |
| 34 |  | **delete** [domain_audit](/code/tables/domain_audit.md) |  |
| 38 |  | **update** [domain_lookalikes](/code/tables/domain_lookalikes.md) (`dismissed`) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/watch.php](/code/files/includes/domains/watch.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainCtScan()](/code/functions/domainCtScan.md), [domainLookalikeScan()](/code/functions/domainLookalikeScan.md), [domainSetting()](/code/functions/domainSetting.md)

