<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Shared setup for the Knowledge visibility harnesses.
 *
 * These are integration checks, not unit tests: they drive the REAL endpoints
 * over HTTP against a running install, because the things they guard (a company
 * filter, an audience gate, an ownership check) only exist end to end. A mocked
 * version would have passed while the app leaked.
 *
 * ⚠️ Runs against whatever install BASE_TEST_URL points at, creating and
 * deleting real rows (all prefixed ZZ-). Point it at a DEV install only.
 */

define('BASE_TEST_URL', rtrim(getenv('FREEITSM_TEST_URL') ?: 'http://localhost/freeitsm-app', '/') . '/');

/** Where PHP writes its sessions — the harnesses forge one to act as an analyst. */
define('SESS_DIR', rtrim(getenv('FREEITSM_SESS_DIR') ?: (ini_get('session.save_path') ?: sys_get_temp_dir()), '/\\'));
