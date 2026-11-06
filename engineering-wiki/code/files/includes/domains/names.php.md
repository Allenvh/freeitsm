# includes/domains/names.php

> PHP - 181 lines

Domains — turning whatever somebody typed or pasted into a domain name.

People paste URLs, e-mail addresses, "WWW.Example.COM.", names with a port on
the end, and names in their own script. Every one of those should land as the
same row, so every path in (the form, the paste box, CSV import, the REST API)
goes through `domainNormalise()` and nothing stores a name it did not return.

🔑 STORED AS ASCII. An internationalised name (bücher.de) is kept as its
punycode form (xn--bcher-kva.de) because that is the name the registry, DNS
and certificates all use; the Unicode form goes in display_name for people.
Two spellings of one name must never become two rows.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainNormalise()](/code/functions/domainNormalise.md) | 25 | Turns a typed or pasted URL, e-mail address or name into the one stored domain name - lower case, no scheme, path, port, root dot or leading www., internationalised names as punycode with the Unicode form as the display name. |
| [domainTld()](/code/functions/domainTld.md) | 89 | The last label: "co.uk" is not a TLD, "uk" is. |
| [domainHostBelongsTo()](/code/functions/domainHostBelongsTo.md) | 103 | Is `$host` this domain or one of its sub-domains? Used to keep the "extra hosts to check certificates on" list from being pointed at somebody else's server — or at an internal address, which would make this a way to probe the network. |
| [domainParseSslHosts()](/code/functions/domainParseSslHosts.md) | 117 | The extra certificate hosts, cleaned: one per entry, only ones that belong to the domain, de-duplicated. The apex and www are always checked and are not listed here. |
| [domainNormaliseHost()](/code/functions/domainNormaliseHost.md) | 136 | A host name (may be a sub-domain, keeps "www."), or null when it is not one. |
| [domainPurposes()](/code/functions/domainPurposes.md) | 154 | Purposes a domain can be registered for. The keys drive checks; labels are in lang. |
| [domainPurposeIsNonMail()](/code/functions/domainPurposeIsNonMail.md) | 171 | Purposes that should never send or receive mail, so get "lock it down" advice. |
| [domainRenewalModes()](/code/functions/domainRenewalModes.md) | 177 | auto \| manual \| do_not_renew \| unknown |

## How it hangs together

**Included by:** [includes/domains/checks.php](/code/files/includes/domains/checks.php.md), [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md)

