# includes/attachment_limits.php

> PHP - 362 lines

Attachment size limits, and what happens to an inbound email that breaks them (GH #170). The ONE place the numbers and the rules are read and applied.

─────────────────────────────────────────────────────────────────────────────
WHERE EACH SETTING LIVES (Ed: "split by job, with signposting")
─────────────────────────────────────────────────────────────────────────────
  System -> Security -> Attachments      the LIMITS, for everything:
      attachment_max_mb         largest single attachment (default 10, as before)
      attachment_max_total_mb   largest total per inbound email (0 = no limit)
    The per-attachment limit applies to every attachment path that does not set
    its own: inbound email, the self-service portal, chat channels, analyst
    replies, attached documents and change attachments. The total applies to
    inbound email, where a message arrives as one thing.

  Tickets -> Settings -> Mailboxes       what an OVERSIZED EMAIL does:
      oversize_mode             strip (keep the email, remove what is too big,
                                leave a note in its place) | reject (no ticket)
      oversize_strip_order      when the TOTAL is over: largest (remove the
                                biggest until the rest fit) | all | order (keep
                                in order until the next would not fit)
      oversize_alert / _alert_to        email these addresses about it
      oversize_autoreply / _autoreply_text   tell the sender

  Each mailbox may override all of it, limits included (target_mailboxes.
  oversize_policy, JSON with the same names; NULL = the defaults above) - a
  supplier mailbox that receives drawings is the case Ed had in mind.

⚠️ PHP's own limits (upload_max_filesize, post_max_size) still cap anything
uploaded through a BROWSER - FreeITSM never sees a file PHP refused. Inbound
email does not go through them (it is fetched, not uploaded), but it is held in
memory while it is processed, so memory_limit matters for very large mail.
`phpUploadLimits()` reports all three for the settings screen.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [attachmentSetting()](/code/functions/attachmentSetting.md) | 45 | One setting, cached per request. |
| [attachmentMb()](/code/functions/attachmentMb.md) | 69 | A whole number of MB from a setting, clamped to something sane (0 allowed only where it means "no limit"). |
| [attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md) | 78 | The install-wide largest single attachment, in bytes. What uploads.php uses when a caller sets none. |
| [oversizeDefaultReplyText()](/code/functions/oversizeDefaultReplyText.md) | 84 | The default text of the auto-reply. {subject}, {limit} and {total_limit} are filled in. |
| [oversizeRules()](/code/functions/oversizeRules.md) | 95 | The rules for one mailbox: the install defaults, overridden by the mailbox's own oversize_policy when it has one. Limits in BYTES (total 0 = no limit). |
| [oversizeNormalisePolicy()](/code/functions/oversizeNormalisePolicy.md) | 123 | A policy as posted by a settings screen (MB, strings), made safe. Used for the mailbox override on save and on read, so a hand-edited row cannot break a check. |
| [oversizeCleanAddresses()](/code/functions/oversizeCleanAddresses.md) | 146 | "a@x.com; b@y.com, junk" -> "a@x.com, b@y.com" - valid addresses only. |
| [oversizePlan()](/code/functions/oversizePlan.md) | 170 | Decide what to do with one email's attachments. |
| [oversizeMbWords()](/code/functions/oversizeMbWords.md) | 214 | Formats a byte count as megabytes for messages - whole numbers without a decimal ("10 MB"), otherwise one place ("2.5 MB"). |
| [phpUploadLimits()](/code/functions/phpUploadLimits.md) | 221 | What PHP on this server will accept, for the settings screen. |
| [oversizeNotify()](/code/functions/oversizeNotify.md) | 260 | Tell people about an oversized email: the alert to the configured addresses, and the reply to the sender. Returns processing-log steps for the inbound log. |
| [oversizeReplySkipReason()](/code/functions/oversizeReplySkipReason.md) | 325 | Why NOT to auto-reply to this sender, or null to go ahead. |
| [oversizePolicyForStorage()](/code/functions/oversizePolicyForStorage.md) | 349 | A normalised policy in the shape stored on a mailbox (MB, not bytes) and read back by `oversizeNormalisePolicy()`. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `attachmentSetting` | read [system_settings](/code/tables/system_settings.md) |  |
| 273 | `oversizeNotify` | read [tickets](/code/tables/tickets.md) |  |
| 335 | `oversizeReplySkipReason` | read [mailbox_activity_log](/code/tables/mailbox_activity_log.md) |  |

## How it hangs together

**Includes:** [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md)

**Included by:** [api/tickets/oversize_settings.php](/code/files/api/tickets/oversize_settings.php.md), [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [system/security/index.php](/code/files/system/security/index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [mailboxSendSimple()](/code/functions/mailboxSendSimple.md)

