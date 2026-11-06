# includes/domains/lookup.php

> PHP - 487 lines

Domains — asking the registry about a domain.

─────────────────────────────────────────────────────────────────────────────
RDAP FIRST, WHOIS SECOND

RDAP is the modern, JSON replacement for WHOIS, and every generic TLD has to
offer it. IANA publishes which server answers for which TLD
(data.iana.org/rdap/dns.json); that file is cached for a week in
system_settings rather than fetched per lookup.

About a sixth of TLDs — mostly country codes: .de, .io, .co, .eu, .us, .jp —
publish no RDAP server. For those the old port-43 WHOIS is tried: IANA's own
WHOIS names the registry's WHOIS server, and the reply is plain text parsed
for the handful of lines that matter. Some registries (DENIC for .de) never
publish an expiry date at all; the result says so rather than guessing, and
the screen offers the field for a person to fill in.

─────────────────────────────────────────────────────────────────────────────
WHAT THIS DOES NOT DO

It never writes. It returns a normalised array and DomainsService::applyLookup()
decides what to keep, under the operator's "does the registry win?" setting,
with a history row for every field it changes.

⚠️ Registry servers rate-limit and publish no numbers. Callers that loop (the
scheduled run) space requests out per server — see `domainLookupPace()`.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainHttpGet()](/code/functions/domainHttpGet.md) | 45 | One outbound GET. Never throws. |
| [domainRdapBootstrap()](/code/functions/domainRdapBootstrap.md) | 83 | TLD → RDAP base URL, from IANA's bootstrap file, cached for seven days. A failed refresh keeps using the stale copy — a week-old map of registries is far better than none, and they rarely move. |
| [domainLookup()](/code/functions/domainLookup.md) | 145 | Look a domain up at its registry. |
| [domainRdapLookup()](/code/functions/domainRdapLookup.md) | 169 | Looks a domain up at its registry's RDAP server and reads the dates, statuses, locks, registrar, name servers, DNSSEC and registrant into the standard result shape. |
| [domainBlankResult()](/code/functions/domainBlankResult.md) | 228 | The result shape every source fills in. |
| [domainNormaliseStatuses()](/code/functions/domainNormaliseStatuses.md) | 246 | "client transfer prohibited" (RDAP) and "clientTransferProhibited https://icann.org/epp#…" (WHOIS) → "client transfer prohibited". |
| [domainDeriveFlags()](/code/functions/domainDeriveFlags.md) | 270 | Locks and states the statuses imply. |
| [domainVcardField()](/code/functions/domainVcardField.md) | 308 | Returns one field's value (for example 'fn' or 'org') from an RDAP jCard, or null when absent or empty. |
| [domainLooksRedacted()](/code/functions/domainLooksRedacted.md) | 325 | True when a registrant value is a privacy placeholder ("REDACTED", "Domains By Proxy", GDPR wording) rather than a real name. |
| [domainDateOnly()](/code/functions/domainDateOnly.md) | 335 | Any date string → Y-m-d, or null. |
| [domainWhoisServer()](/code/functions/domainWhoisServer.md) | 355 | The WHOIS server for a TLD, from IANA's referral, cached with the bootstrap. |
| [domainWhoisQuery()](/code/functions/domainWhoisQuery.md) | 385 | One port-43 query. null when the server could not be reached. |
| [domainWhoisLookup()](/code/functions/domainWhoisLookup.md) | 409 | Looks a domain up over WHOIS, for registries without RDAP, and reads the dates, statuses, registrar, name servers and DNSSEC from the text reply. |
| [domainLookupPace()](/code/functions/domainLookupPace.md) | 464 | Space out requests to one registry. Called between lookups in a batch: the same RDAP/WHOIS host is not asked again within `$gapSeconds`. |
| [domainTransferLockNotApplicable()](/code/functions/domainTransferLockNotApplicable.md) | 483 | Registries where a registrar "transfer lock" is not how transfers are controlled, so its absence is not a weakness. .uk: Nominet registrar tags. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `domainRdapBootstrap` | read [system_settings](/code/tables/system_settings.md) |  |
| 116 | `domainRdapBootstrap` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |
| 362 | `domainWhoisServer` | read [system_settings](/code/tables/system_settings.md) |  |
| 372 | `domainWhoisServer` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [includes/domains/names.php](/code/files/includes/domains/names.php.md), [includes/ssl.php](/code/files/includes/ssl.php.md)

**Included by:** [includes/domains/dns.php](/code/files/includes/domains/dns.php.md), [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md)

**Calls:** [domainTld()](/code/functions/domainTld.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

