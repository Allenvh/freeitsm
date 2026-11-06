<?php
/**
 * Assets covered by a contract (discussion #106, dschipfel).
 *
 * Both directions of one link: which equipment a contract covers, and which
 * contracts cover a piece of equipment. A mobile service agreement and the
 * handsets and SIMs on it is the worked example from the request.
 *
 * ⚠️ WHY THIS FILE EXISTS RATHER THAN FOUR ENDPOINTS DOING IT THEMSELVES.
 * Every read of the asset side has to be filtered to the companies the analyst
 * can reach. Four endpoints filtering separately is four chances to forget;
 * forgetting once puts one customer's equipment on another customer's contract
 * page, and it looks exactly like a working feature.
 *
 * 🔑 ONE COMPANY PER LINK (3.5.0). Contracts belong to a company now, and a
 * contract covers only assets of its OWN company - checked on every link, even
 * for an analyst who can reach both companies (that is precisely the analyst
 * who could otherwise wire two clients together). The picker offers the
 * contract's company's assets, so the rule is discoverable rather than an error.
 *
 * 🔑 A scoped list is not a gate. `contractAssetCanReach()` is called again on
 * every write and every delete, because the id in a POST body did not
 * necessarily come from the list we rendered.
 *
 * ⚠️ NO HIDDEN COUNT. When a contract covers ten assets and the reader may see
 * three, they are shown three and told nothing about the other seven. A count
 * of what you cannot see is a leak of its own, and this is the mistake the
 * requester picker made: it scoped the list and not the count.
 */

require_once __DIR__ . '/tenancy.php';
require_once __DIR__ . '/contract_party.php';

/**
 * Can this analyst reach this asset at all?
 *
 * The gate for every write. Returns false for an asset that does not exist,
 * which is the same answer as one they may not see — deliberately, because
 * telling them apart tells you a row exists.
 */
function contractAssetCanReach(PDO $conn, int $analystId, int $assetId): bool
{
    if ($assetId <= 0) {
        return false;
    }
    // Every company the analyst can reach, not just the active one (3.5.0): the
    // contract they are working on may belong to another of their companies, and
    // contractAssetLink() holds the asset to the contract's company anyway.
    [$where, $args] = contractAssetReachSql($conn, $analystId);
    $stmt = $conn->prepare("SELECT 1 FROM assets a WHERE a.id = ?" . $where);
    $stmt->execute(array_merge([$assetId], $args));
    return (bool)$stmt->fetchColumn();
}

/** " AND ..." keeping assets (alias a) in companies this analyst can reach. ['', []] with one company. */
function contractAssetReachSql(PDO $conn, int $analystId): array
{
    return isMultiTenant($conn) ? allAccessibleTenantsFilter($conn, $analystId, 'a.tenant_id') : ['', []];
}

/** The columns every asset row in this feature shows. One home, so the two directions match. */
function contractAssetSelect(): string
{
    return "SELECT ca.id AS link_id, ca.reference, ca.created_datetime,
                   a.id AS asset_id, a.hostname, a.manufacturer, a.model,
                   a.service_tag, a.asset_tag,
                   ty.name AS type_name, l.name AS location_name
              FROM contract_assets ca
              JOIN assets a               ON a.id = ca.asset_id
         LEFT JOIN asset_types ty         ON ty.id = a.asset_type_id
         LEFT JOIN asset_locations l      ON l.id = a.location_id";
}

/**
 * Equipment covered by a contract, filtered to what this analyst may see.
 *
 * @param int $contractId  contracts.id; a contract the analyst cannot view gives an empty list
 */
function contractAssetsFor(PDO $conn, int $analystId, int $contractId): array
{
    if (!contractCanView($conn, $analystId, $contractId)) return [];
    [$where, $args] = contractAssetReachSql($conn, $analystId);
    $stmt = $conn->prepare(
        contractAssetSelect() . " WHERE ca.contract_id = ?" . $where .
        " ORDER BY ty.name, a.hostname, a.asset_tag"
    );
    $stmt->execute(array_merge([$contractId], $args));

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['link_id']  = (int)$r['link_id'];
        $r['asset_id'] = (int)$r['asset_id'];
    }
    return $rows;
}

/**
 * Contracts covering an asset.
 *
 * Filtered by contractVisibilitySql() for the viewer: a contract in a company
 * they cannot reach is left out (a link made before 3.5.0 can cross companies).
 */
function contractsForAsset(PDO $conn, int $assetId, ?int $analystId = null): array
{
    // A CUSTOMER contract is hidden from an analyst who cannot see its customer's
    // company (#153). Null analyst = no filter, for callers with no viewer.
    require_once __DIR__ . '/contract_party.php';
    [$partyCols, $partyJoins] = contractPartySql($conn, 'c');
    [$vis, $visParams] = $analystId !== null ? contractVisibilitySql($conn, $analystId, 'c') : ['', []];
    $stmt = $conn->prepare(
        "SELECT ca.id AS link_id, ca.reference,
                c.id AS contract_id, c.contract_number, c.title,
                c.contract_start, c.contract_end, c.notice_date, c.notice_period_days,
                -- Both names, as the contracts list itself returns them: the
                -- legal name is the one on the paperwork, the trading name is
                -- the one people recognise. contract_statuses carries no colour
                -- column, so there is nothing to select for one.
                s.legal_name AS supplier_name, s.trading_name AS supplier_trading_name,
                st.name AS status_name
                $partyCols
           FROM contract_assets ca
           JOIN contracts c            ON c.id = ca.contract_id
      LEFT JOIN suppliers s            ON s.id = c.supplier_id
      LEFT JOIN contract_statuses st   ON st.id = c.contract_status_id
                $partyJoins
          WHERE ca.asset_id = ?$vis
       ORDER BY c.contract_end IS NULL, c.contract_end, c.title"
    );
    $stmt->execute(array_merge([$assetId], $visParams));

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['link_id']     = (int)$r['link_id'];
        $r['contract_id'] = (int)$r['contract_id'];
        $r['party_label'] = contractPartyLabel($r);
    }
    return $rows;
}

/**
 * Link an asset to a contract. Returns the new link id.
 *
 * Re-linking something already linked updates the reference rather than
 * failing: the unique key makes a second row impossible, and "it is already on
 * here" is not an error anybody wants to read.
 *
 * @param int $contractId  contracts.id to link the asset to
 * @param ?string $reference  free-text note on the link (e.g. a line or serial on the agreement); trimmed,
 *     cut to 190 characters, empty or null stores NULL
 * @throws RuntimeException when the contract or the asset is out of reach.
 */
function contractAssetLink(PDO $conn, int $analystId, int $contractId, int $assetId, ?string $reference): int
{
    // contractCanView is also the existence check: a customer contract out of
    // this analyst's companies (#153) is "no such contract".
    if (!contractCanView($conn, $analystId, $contractId)) {
        throw new RuntimeException('No such contract');
    }
    if (!contractAssetCanReach($conn, $analystId, $assetId)) {
        // Same message either way. See the header.
        throw new RuntimeException('No such asset');
    }
    // Same company, always (see the header). Both are reachable here, so naming
    // the problem tells the analyst nothing they could not already see.
    if (isMultiTenant($conn) && contractTenantReady($conn)) {
        $at = $conn->prepare("SELECT COALESCE(tenant_id, ?) FROM assets WHERE id = ?");
        $at->execute([getDefaultTenantId($conn), $assetId]);
        if ((int)$at->fetchColumn() !== contractTenantOf($conn, $contractId)) {
            throw new RuntimeException('That asset belongs to a different company from this contract.');
        }
    }

    $reference = $reference !== null ? mb_substr(trim($reference), 0, 190) : null;
    if ($reference === '') {
        $reference = null;
    }

    $conn->prepare(
        "INSERT INTO contract_assets (contract_id, asset_id, reference, linked_by_id, created_datetime)
              VALUES (?, ?, ?, ?, UTC_TIMESTAMP())
         ON DUPLICATE KEY UPDATE reference = VALUES(reference)"
    )->execute([$contractId, $assetId, $reference, $analystId]);

    $id = $conn->prepare("SELECT id FROM contract_assets WHERE contract_id = ? AND asset_id = ?");
    $id->execute([$contractId, $assetId]);
    return (int)$id->fetchColumn();
}

/**
 * Change the note on an existing link.
 *
 * @param int $linkId  contract_assets.id
 * @param ?string $reference  the new note; trimmed, cut to 190 characters, empty or null clears it
 * @throws RuntimeException when the link is out of reach.
 */
function contractAssetSetReference(PDO $conn, int $analystId, int $linkId, ?string $reference): void
{
    $row = contractAssetLoad($conn, $analystId, $linkId);
    $reference = $reference !== null ? mb_substr(trim($reference), 0, 190) : null;
    if ($reference === '') {
        $reference = null;
    }
    $conn->prepare("UPDATE contract_assets SET reference = ? WHERE id = ?")
         ->execute([$reference, $row['id']]);
}

/**
 * Remove a link. The asset and the contract both survive it — this only ever
 * says "this equipment is not on that agreement".
 *
 * @param int $linkId  contract_assets.id to delete
 * @throws RuntimeException when the link is out of reach.
 */
function contractAssetUnlink(PDO $conn, int $analystId, int $linkId): void
{
    $row = contractAssetLoad($conn, $analystId, $linkId);
    $conn->prepare("DELETE FROM contract_assets WHERE id = ?")->execute([$row['id']]);
}

/**
 * One link, but only if this analyst can reach the asset on it.
 *
 * @param int $linkId  contract_assets.id to load
 * @throws RuntimeException when it does not exist or is out of reach.
 */
function contractAssetLoad(PDO $conn, int $analystId, int $linkId): array
{
    $stmt = $conn->prepare("SELECT id, contract_id, asset_id FROM contract_assets WHERE id = ?");
    $stmt->execute([$linkId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !contractAssetCanReach($conn, $analystId, (int)$row['asset_id'])
        || !contractCanView($conn, $analystId, (int)$row['contract_id'])) {
        throw new RuntimeException('No such link');
    }
    $row['id'] = (int)$row['id'];
    return $row;
}

/**
 * Assets this analyst could add to a contract, matching $q.
 *
 * Searches location as well as hostname, model, serial and tag, for the same
 * reason the ticket picker does: nobody knows the hostname of a meeting-room
 * TV, and nobody knows the service tag of a SIM card. They know what it is and
 * where it lives.
 *
 * @param int $contractId  contracts.id the assets would be added to; its company bounds the search
 * @param string $q  the search text, matched as a substring; empty lists everything not yet linked
 * @param int $limit  most rows to return
 */
function contractAssetSearch(PDO $conn, int $analystId, int $contractId, string $q, int $limit = 25): array
{
    if (!contractCanView($conn, $analystId, $contractId)) return [];   // #153
    // The contract's OWN company's assets (3.5.0), whatever the switcher says -
    // the only ones it may cover. The analyst can see the contract, so they can
    // reach its company.
    [$where, $args] = isMultiTenant($conn) && contractTenantReady($conn)
        ? tenantScopeSqlFor($conn, (int)contractTenantOf($conn, $contractId), 'a.tenant_id')
        : ['', []];

    $sql =
        "SELECT a.id AS asset_id, a.hostname, a.manufacturer, a.model,
                a.service_tag, a.asset_tag,
                ty.name AS type_name, l.name AS location_name
           FROM assets a
      LEFT JOIN asset_types ty    ON ty.id = a.asset_type_id
      LEFT JOIN asset_locations l ON l.id = a.location_id
          WHERE a.id NOT IN (SELECT asset_id FROM contract_assets WHERE contract_id = ?)";
    $params = [$contractId];

    if ($q !== '') {
        $sql .= " AND (a.hostname LIKE ? OR a.manufacturer LIKE ? OR a.model LIKE ?
                       OR a.service_tag LIKE ? OR a.asset_tag LIKE ? OR l.name LIKE ?)";
        $params = array_merge($params, array_fill(0, 6, '%' . $q . '%'));
    }

    $sql .= $where . " ORDER BY a.hostname, a.asset_tag LIMIT " . (int)$limit;

    $stmt = $conn->prepare($sql);
    $stmt->execute(array_merge($params, $args));

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['asset_id'] = (int)$r['asset_id'];
    }
    return $rows;
}
