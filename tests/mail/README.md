# Generic IMAP/SMTP regressions

Run from the repository root. No production credentials are embedded in these tests.

- `node tests/mail/form.js`: runs the actual provider-switching JavaScript with a minimal DOM fixture, covering basic IMAP, Google, Microsoft delegated and Microsoft app-only.
- `php tests/mail/run.php`: requires the normal PHP extensions and an encryption key configured through `ENCRYPTION_KEY_PATH`. Uses an IMAP client mock and a loopback SMTP protocol fixture with randomly generated credentials; tests configuration, folder failure/cleanup, encryption, SMTP authentication, rejected passwords, sending and unauthenticated relay mode. It does not contact external servers.
- `python3 tests/mail/credentials.py`: optional integration test for the existing disposable HTTP fixture container `freeitsm-ad-http`, published at `127.0.0.1:8088`. Requires Docker access, the current app code/library in that container, and its disposable database `freeitsm_ad_test`. The runner refuses other database names before writes. Supply `MAIL_TEST_ADMIN_USERNAME` and `MAIL_TEST_ADMIN_PASSWORD` through your environment. It creates and removes one temporary mailbox, checks real API encryption/redaction/password reuse, and runs folder verification against a loopback IMAP fixture with the real Webklex library. It does not alter Compose or GreenMail. Do not point this at your normal installation.

The protocol fixtures are deliberately minimal and are not substitutes for final GreenMail or production-provider validation. Live Google/Microsoft OAuth and actual incoming-ticket creation are not covered here.
