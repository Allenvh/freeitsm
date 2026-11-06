<?php
/**
 * POST — certificates recorded by hand on a domain or subdomain.
 *   {action:'save', id?, domain_id, purpose, cert_type_id, supplier_id | provider_name, expiry_date, url, notes}
 *   {action:'delete', id}
 * Thin adapter over DomainsService::saveManualCertificate() / deleteManualCertificate().
 * Anybody who can edit the domain can record its certificates - the same rule as
 * editing the domain itself; out of the analyst's companies reads as not found.
 */
require_once __DIR__ . '/../../includes/domains/api_bootstrap.php';

domainApiRun(function () use ($conn, $ctx) {
    csrfRequireWriteMethod();
    $in = domainApiBody();
    if (($in['action'] ?? '') === 'delete') {
        DomainsService::deleteManualCertificate($conn, $ctx, (int)($in['id'] ?? 0));
        domainApiOk();
    }
    $res = DomainsService::saveManualCertificate($conn, $ctx, $in);
    domainApiOk(['id' => $res['id'], 'created' => $res['created']]);
});
