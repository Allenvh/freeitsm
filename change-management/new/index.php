<?php
/**
 * /change-management/new/ — pretty URL for creating a change.
 *
 * This is a thin wrapper around ../index.php. We just set a sentinel
 * variable that the main page picks up to emit a JS bootstrap line, so
 * the editor opens straight away in create mode. All URLs in the page
 * are built via BASE_URL (defined in config.php) so depth doesn't
 * matter — every nav link, asset and API call still resolves correctly
 * even though the browser address bar reads /change-management/new/.
 */
$openCreateOnLoad = true;
include __DIR__ . '/../index.php';
