# Mailbox schema migration troubleshooting

FreeITSM's canonical mailbox table is `target_mailboxes`. Older Microsoft Graph, Gmail, and Exchange-style mailbox records already live there, and the Mailboxes UI, OAuth callbacks, email intake workers, and reply senders should all use that table consistently.

## Why the `auth_mode` error happens

The runtime error below means the application code is selecting a newer mailbox column before the installed database has been upgraded:

```text
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'auth_mode' in 'field list'
```

A manual patch such as `ALTER TABLE mailboxes ...` fails when the database has no `mailboxes` table because FreeITSM does **not** use that as the mailbox configuration table. The real table is `target_mailboxes`.

## How db-verify fixes it

Run the setup/database verification page after updating FreeITSM. The verifier checks `target_mailboxes`, adds missing mailbox columns idempotently, and backfills legacy OAuth rows so existing Graph/Gmail/Exchange mailboxes continue to behave as OAuth-backed mailboxes.

The verifier repairs these columns when missing:

- `provider_type`
- `auth_mode`
- `imap_host`, `imap_port`, `imap_encryption`, `imap_username`, `imap_password_encrypted`, `imap_folder`
- `smtp_host`, `smtp_port`, `smtp_encryption`, `smtp_username`, `smtp_password_encrypted`, `smtp_from_address`, `smtp_from_name`
- `intake_enabled`, `outbound_enabled`
- `last_checked_at`

Existing rows are defaulted safely: Microsoft/Exchange-style rows become `provider_type = 'graph'`, Google rows become `provider_type = 'gmail'`, IMAP/SMTP rows keep `provider_type = 'imap_smtp'`, and OAuth-style auth is normalised to FreeITSM's delegated OAuth mode.

## Manual inspection

If setup/db-verify still reports a problem, inspect the database directly:

```sql
SHOW TABLES LIKE '%mail%';
DESCRIBE target_mailboxes;
```

You should see `target_mailboxes` and the columns listed above. Do not create or patch a separate `mailboxes` table unless a future migration explicitly changes the canonical table name.
