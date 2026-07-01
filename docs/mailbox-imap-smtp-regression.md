# IMAP/SMTP mailbox regression checks

Use these checks after changing Generic IMAP/SMTP mailbox saving or D005 diagnostics.

## Encryption persistence

1. Create or edit a Generic IMAP/SMTP mailbox.
2. Set SMTP encryption to `tls`, save, and verify `target_mailboxes.smtp_encryption` is `tls`.
3. Set SMTP encryption to `none`, save, and verify `target_mailboxes.smtp_encryption` is `none`.
4. Run D005 `show_config` and verify resolved runtime config shows `smtp.encryption=false` for `none`.
5. Repeat the same persistence check for IMAP encryption with only `none`, `ssl`, or `tls` accepted.

## IMAP message identity

1. Run D005 `list_messages` against a Generic IMAP/SMTP mailbox with test messages.
2. Verify each message has either a positive UID and an external message id formatted as `imap:{mailbox_id}:{uid}`, or a unique fallback external message id.
3. Verify D005 includes sanitized raw Webklex message attributes for each message so UID location can be diagnosed without exposing secrets.
4. Run Check All and verify messages are processed instead of being collapsed into `imap-0` / UID `0` duplicates.
