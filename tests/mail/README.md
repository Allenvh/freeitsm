# Mail regression checks after the upstream upgrade

Run `php tests/mail/upstream-smtp.php` from the repository root. It exercises the
actual upstream SMTP sender against a loopback fixture, with randomly generated
independent SMTP credentials, no OAuth, successful delivery and rejected passwords.
The old Webklex and custom mailbox form tests were retired with those implementations.

The upgrade image includes PHP IMAP via PECL; upstream otherwise omits this extension.
Validate real IMAP folder access, ticket intake and replies against GreenMail too.
See [the upgrade guide](../../docs/upstream-4.10-upgrade.md).
