# Native LDAP integration and migration tests

These tests are destructive **only to the dedicated `freeitsm_native_ldap_test` database**. Use an isolated test MySQL instance; never supply production credentials. They do not modify Compose or contact corporate AD. Directory user/service passwords are generated at runtime; database and local test-administrator credentials come from environment variables.

## PHP schema, identity and migration suite

The application image must include its normal PHP extensions, LDAP and dependencies. Example with an existing test Compose network:

```sh
docker run --rm --network freeitsm_default --entrypoint php \
  -v "$PWD:/src:ro" \
  -e LDAP_TEST_DB_HOST=db -e LDAP_TEST_DB_USER \
  -e LDAP_TEST_DB_PASSWORD -e ENCRYPTION_KEY_PATH=/tmp/native-test.key \
  freeitsm-app \
  -r 'file_put_contents("/tmp/native-test.key",bin2hex(random_bytes(32)));require "/src/tests/native_ldap/run.php";'
```

The suite checks fresh schema creation, pre-LDAP upgrades, repeatability, local-password compatibility, upstream password/NUL guards, group gates, independent analyst/portal JIT, immutable identity conflicts, explicit module restrictions, legacy quarantine, dry runs, rollback for disabled/conflicting profiles, preservation of tickets and IDs, repeatable data migration and OIDC token/protocol isolation. The retained historical custom AD migration is used only to construct legacy test data.

## Real LDAP and HTTP fixture

Run the PHP suite first to initialize the disposable schema. Then set:

- `NATIVE_TEST_IMAGE`: built current application image (default `freeitsm-native-ldap:test`).
- `NATIVE_TEST_NETWORK`: Docker network containing the test database under the hostname `db` (default `freeitsm-cloud_default`).
- `NATIVE_TEST_DB_USER`, `NATIVE_TEST_DB_PASSWORD`: test database login.
- `NATIVE_TEST_LOCAL_USERNAME`, `NATIVE_TEST_LOCAL_PASSWORD`: the seeded local administrator in the disposable database.

Run `python3 tests/native_ldap/http_fixture.py` from the checkout. Docker and Python 3 are required; the fixture uses `osixia/openldap:1.5.0` for OpenLDAP binaries and starts `slapd` directly with ephemeral generated data and a read-only service ACL. Plain LDAP is confined to this isolated test network; this does not test production TLS certificates.

The runner creates `freeitsm-native-ldap-fixture` and `freeitsm-native-ldap-http`, publishes only `127.0.0.1:8089`, overlays the current working-tree files into the test app, and removes both containers/anonymous volumes afterward. It rejects fixture SQL operations unless DB_NAME is exactly `freeitsm_native_ldap_test`. It exercises the real admin APIs, real service/user binds and group searches, analyst and requester logins, wrong passwords, group denial, TOTP requirements, permission boundaries, provider deletion protection, CSRF and retired-page redirects. Errors redact generated credentials and omit PHP function arguments.

Existing `tests/mail` remain unchanged and should also pass. These tests do not establish corporate AD connectivity, enterprise CA trust, or live external OIDC behavior.

## Browser layout regression

`layout.py` requires Python Playwright and Chromium. Set `LDAP_LAYOUT_URL` to an isolated loopback test app, `LDAP_LAYOUT_USERNAME` and `LDAP_LAYOUT_PASSWORD` to its local administrator credentials, and optionally `CHROMIUM_PATH`. Run `python3 tests/native_ldap/layout.py`. It verifies that Add LDAP opens an editable form inside the visible scrolling region, Add OIDC remains functional, and the page has no browser script errors.
