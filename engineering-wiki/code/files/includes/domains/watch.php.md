# includes/domains/watch.php

> PHP - 237 lines

Domains — watching the outside world for your names.

Two optional scans, both OFF by default (Domains → Settings → Monitoring):

─────────────────────────────────────────────────────────────────────────────
CERTIFICATE TRANSPARENCY (crt.sh)

Every publicly trusted certificate is published in public logs. crt.sh
indexes them, so asking it "what certificates exist for example.com?" shows
every one ever issued — including one somebody obtained without you knowing,
which is the first visible step of a lot of phishing. New ones are stored
unacknowledged and shown on the domain; the FIRST scan of a domain records
everything already there as acknowledged, so switching the watch on does not
produce a wall of "new" certificates that are years old.

⚠️ crt.sh is a free community service with no published terms and a low rate
limit (a handful of queries a minute per address). The scheduled run asks
about each domain at most weekly and paces the queries. It is slow and
sometimes times out; a failure is simply retried next time.

─────────────────────────────────────────────────────────────────────────────
LOOK-ALIKES (typo-squatting)

The names one slip from yours — examp1e.com, exmaple.com, example-login.com,
example.co — generated here the way dnstwist does it, then resolved. A
look-alike that resolves exists; one with MAIL servers can receive the
invoices and password resets people mistype. Existence is all DNS can tell
us — whether it is malicious is a person's judgement, so each one can be
dismissed.

Only primary and secondary domains are scanned: the look-alikes of a parked
name are not worth a thousand DNS queries.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainCtScan()](/code/functions/domainCtScan.md) | 56 | Asks crt.sh for the unexpired certificates issued for a domain and stores any not seen before; the first scan stores them as acknowledged, later ones as new and fire domain.certificate_issued. |
| [domainCtIssuer()](/code/functions/domainCtIssuer.md) | 102 | "C=US, O=Let's Encrypt, CN=R11" → "Let's Encrypt (R11)" |
| [domainCtDate()](/code/functions/domainCtDate.md) | 115 | Turns a crt.sh timestamp (UTC, with or without a Z) into a 'Y-m-d H:i:s' UTC string, or null. |
| [domainLookalikeCandidates()](/code/functions/domainLookalikeCandidates.md) | 133 | The look-alike candidates for a name, keyed by candidate => technique. Deliberately bounded (a few hundred at most): every candidate is two DNS queries. |
| [domainLookalikeScan()](/code/functions/domainLookalikeScan.md) | 198 | Resolve the candidates and store the ones that exist. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 | `domainCtScan` | read [domain_certificates](/code/tables/domain_certificates.md) |  |
| 70 | `domainCtScan` | **insert** [domain_certificates](/code/tables/domain_certificates.md) (`domain_id`, `crtsh_id`, `common_name`, `name_value`, `issuer`, `not_before`, `not_after`, `first_seen_datetime`, `acknowledged`) |  |
| 204 | `domainLookalikeScan` | **insert** [domain_lookalikes](/code/tables/domain_lookalikes.md) (`domain_id`, `lookalike`, `technique`, `has_a`, `has_mx`, `first_seen_datetime`, `last_seen_datetime`) |  |
| 210 | `domainLookalikeScan` | read [domain_lookalikes](/code/tables/domain_lookalikes.md) |  |

## How it hangs together

**Includes:** [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), [includes/domains/dns.php](/code/files/includes/domains/dns.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/domains/watch.php](/code/files/api/domains/watch.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

**Calls:** [domainDns()](/code/functions/domainDns.md), [domainHttpGet()](/code/functions/domainHttpGet.md)

