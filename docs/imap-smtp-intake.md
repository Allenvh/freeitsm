# Generic IMAP/SMTP ticket intake

FreeITSM now has a provider foundation for `imap_smtp` mailboxes alongside Microsoft Graph and Gmail. Passwords are encrypted with the existing FreeITSM encryption helper and should never be logged.

## Configuration fields

A mailbox may define:

- IMAP: host, port, encryption (`ssl`, `tls`, `none`), username, encrypted password, folder, intake enabled.
- SMTP: host, port, encryption (`ssl`, `tls`, `none`), username, encrypted password, from address/name, outbound enabled.

## Intake behavior

- Fetches unread/unseen messages from IMAP.
- Creates an `intake_messages` row for troubleshooting and future triage.
- Uses message-id as duplicate protection through the existing email message id tracking plus the intake message unique key.
- Creates or updates tickets via the existing email-to-ticket path.
- Marks the IMAP message seen only after successful ticket creation.
- Logs intake ticket creation and failed processing audit events through the PHP error log.

Attachment handling is intentionally aligned to existing ticket attachment patterns; deeper MIME attachment extraction can be expanded without changing the provider abstraction.

## Test endpoints

Authenticated analysts can test saved mailbox connections:

- `api/tickets/test_imap_connection.php?mailbox_id=<id>`
- `api/tickets/test_smtp_connection.php?mailbox_id=<id>`

## Manual checklist

- Fresh `podman compose up -d`
- App can reach MySQL
- Keycloak starts
- FreeITSM local login still works
- Add OIDC provider manually in FreeITSM
- Login through Keycloak works
- Group claim maps to FreeITSM modules
- IMAP test connection succeeds
- SMTP test connection succeeds
- Email creates ticket
- Duplicate email does not create duplicate ticket
- Local break-glass login still works
