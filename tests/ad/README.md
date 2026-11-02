# Active Directory tests

Use a disposable MySQL 8 database named `freeitsm_ad_test` (or that name with a
suffix). Load `database/freeitsm.sql` into it, grant the application test user
access, and run:

```sh
AD_TEST_DB_NAME=freeitsm_ad_test php tests/ad/run.php
```

The runner uses `DB_SERVER`, `DB_USERNAME`, and `DB_PASSWORD` from the environment.
It creates fixtures and changes state only in that explicitly selected database.
Supply `ENCRYPTION_KEY_PATH` pointing to a test key outside the web root. Do not
run on production data. Recreate the disposable database before repeating.

Tests exercise the real MySQL persistence and authorization engine using an
injected directory adapter. No production AD, mail server, or passwords are needed.
Actual AD schema behavior, TLS hostname/CA failures, DC failover and nested-group
matching require the documented staging-directory checks in `docs/active-directory.md`.

`python3 tests/ad/http_checks.py` runs read-only HTTP and rejected-CSRF checks against
a local development instance on port 8080 with AD disabled and the documented
admin/freeitsm seed account. Do not run this check against a configured deployment.
