<?php
/**
 * GET — the certificate types (open to anyone in the module: the certificate form uses them).
 * POST {action:'save', id?, name, is_active, display_order}
 * POST {action:'delete', id}
 * Writes need Cap::DOMAINS_CERT_TYPES - capabilities guard writes, never reads.
 */
require_once __DIR__ . '/../../includes/domains/api_bootstrap.php';
require_once __DIR__ . '/../../includes/domains/subdomains.php';

domainApiRun(function () use ($conn) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        domainApiOk(['ready' => domainManualCertsReady($conn), 'types' => domainCertificateTypes($conn, true)]);
    }
    domainRequireCap($conn, Cap::DOMAINS_CERT_TYPES);
    if (!domainManualCertsReady($conn)) domainApiFail('Run Database Verification first.');
    $in = domainApiBody();
    if (($in['action'] ?? '') === 'delete') {
        DomainsService::deleteCertType($conn, (int)($in['id'] ?? 0));
        domainApiOk();
    }
    $res = DomainsService::saveCertType($conn, $in);
    domainApiOk(['id' => $res['id']]);
});
