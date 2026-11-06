# includes/domains/checks.php

> PHP - 346 lines

Domains — the health and security checks, and the grade they add up to.

─────────────────────────────────────────────────────────────────────────────
WHAT A FINDING IS

  ['key' => 'spf_missing', 'area' => 'email', 'level' => 'fail',
   'weight' => 10, 'params' => ['...']]

`key` names a pair of strings in lang/en/domains.php — check.<key>.title and
check.<key>.advice — so the words are translated and the stored JSON is not
English. `level` is pass | info | warn | fail; only warn and fail cost points.

─────────────────────────────────────────────────────────────────────────────
🔑 THE PURPOSE CHANGES THE QUESTION

A domain nobody sends mail from is not "missing" SPF — it should have a
DIFFERENT SPF, one that says "nobody sends mail as me" (v=spf1 -all), plus a
DMARC reject policy and a null MX. Otherwise it is the perfect address for
somebody else to send phishing from: unused, trusted, and unmonitored. So
defensive / parked / redirect domains get the lockdown checks instead of the
mail-sending ones. Most tools grade every domain the same and call a parked
domain "fine" because it has no mail to fail.

─────────────────────────────────────────────────────────────────────────────
THE GRADE

100 minus the weight of every warn and fail, clamped to 0-100, then A+ / A /
B / C / D / F. An expired or redemption-period domain is an F whatever else
it gets right — nothing else matters if the name is about to be lost.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainRunChecks()](/code/functions/domainRunChecks.md) | 46 | Run every check for one domain row. |
| [domainGrade()](/code/functions/domainGrade.md) | 281 | Scores a domain's health findings out of 100 (each takes off its weight) and turns the score into a grade, A+ to F; an expired, redemption or NXDOMAIN finding is always F. |
| [domainParseTags()](/code/functions/domainParseTags.md) | 305 | "v=DMARC1; p=reject; rua=mailto:x" → ['v'=>'DMARC1','p'=>'reject','rua'=>'mailto:x'] |
| [domainSpfLookupCount()](/code/functions/domainSpfLookupCount.md) | 326 | How many DNS lookups evaluating this SPF record costs (RFC 7208 §4.6.4 caps it at 10; past that, receivers treat the record as a permanent error — i.e. SPF silently stops protecting the domain). Follows include: and redirect=. |

## How it hangs together

**Includes:** [includes/domains/names.php](/code/files/includes/domains/names.php.md), [includes/domains/dns.php](/code/files/includes/domains/dns.php.md), [includes/domains/tls.php](/code/files/includes/domains/tls.php.md)

**Included by:** [includes/services/domains.php](/code/files/includes/services/domains.php.md)

**Calls:** [domainDns()](/code/functions/domainDns.md), [domainParseSslHosts()](/code/functions/domainParseSslHosts.md), [domainPurposeIsNonMail()](/code/functions/domainPurposeIsNonMail.md), [domainTlsCertificate()](/code/functions/domainTlsCertificate.md), [domainTransferLockNotApplicable()](/code/functions/domainTransferLockNotApplicable.md), [domainTxtStartingWith()](/code/functions/domainTxtStartingWith.md)

