# includes/domains/status_link.php

> PHP - 301 lines

Domains -> Service Status (3.0.0): what a domain's trouble does to the services linked to it (domain_status_services). Everything here is decided by Domains -> Settings -> Service Status.

off      nothing - the links are still shown, as information
  suggest  the domain's page says which services are at risk and offers one
           button that raises the incident; a person decides (the default)
  auto     the scheduled run raises it by itself

"Trouble" is the same idea the Watchtower card and the alerts already use: a
domain whose status still wants alerts and which is not being deliberately
let go, that has EXPIRED (domain_status_on_expired) or whose live certificate
has expired, or is within domain_status_cert_days of it (domain_status_on_cert).

🔑 NEVER TWICE. domain_status_incidents holds one row per (domain, trigger,
fingerprint), the fingerprint being the date the trouble is about - the
domain_alerts_sent rule. The same lapse never raises a second incident, and
renewing (a new date) re-arms it. While a domain has an incident it raised
that is still open, nothing new is raised for it either: one incident per
outage, however many reasons.

🔑 CUSTOMERS READ THE STATUS PAGE. The opening update is INTERNAL unless
domain_status_public says otherwise - Service Status's own rule (#99) for any
caller that did not ask.

Incidents are created and resolved through ServiceStatusService, so they get
the opening update, the impact snapshot and the workflow events exactly as
one raised by hand.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainStatusReady()](/code/functions/domainStatusReady.md) | 43 | True once Database Verification has created the domain_status_incidents and domain_status_services tables; checked once per request. |
| [domainStatusProblems()](/code/functions/domainStatusProblems.md) | 64 | What is wrong with this domain right now, as far as Service Status is concerned. Each: kind ('expired' \| 'cert'), fingerprint (the date), date. |
| [domainStatusRow()](/code/functions/domainStatusRow.md) | 89 | One domain row with the fields the problem test needs (alerts_enabled from its status). |
| [domainStatusServiceIds()](/code/functions/domainStatusServiceIds.md) | 104 | The services linked to a domain, ids only (install-wide records). |
| [domainStatusOpenIncident()](/code/functions/domainStatusOpenIncident.md) | 117 | The incident this domain raised that is still open, or null. |
| [domainStatusState()](/code/functions/domainStatusState.md) | 136 | For the domain's page: the mode, the trouble, the linked services at risk, an open incident if there is one, and whether this analyst may raise one (Service Status module access). |
| [domainStatusWording()](/code/functions/domainStatusWording.md) | 155 | The title and opening comment, in plain words (stored data: what customers may read). |
| [domainStatusRaise()](/code/functions/domainStatusRaise.md) | 180 | Raise one incident for a domain's trouble on its linked services, and record it against every current problem. Returns the incident id, or the open one it already raised. Throws ServiceError when there is nothing to raise (no trouble, or no services linked). |
| [domainStatusImpactId()](/code/functions/domainStatusImpactId.md) | 216 | The impact level to record. The chosen one if it still exists; otherwise the MOST severe active level that counts as downtime. |
| [domainStatusResolvedName()](/code/functions/domainStatusResolvedName.md) | 233 | The first active "resolved" incident status, by its own order. |
| [domainStatusRun()](/code/functions/domainStatusRun.md) | 244 | The scheduled part: raise (mode auto) and resolve (domain_status_auto_resolve). The actor is the system - actorId 0, which ServiceStatusService stores as the creator rather than inventing a member of staff. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 | `domainStatusReady` | read [domain_status_incidents](/code/tables/domain_status_incidents.md) |  |
| 49 | `domainStatusReady` | read [domain_status_services](/code/tables/domain_status_services.md) |  |
| 92 | `domainStatusRow` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 106 | `domainStatusServiceIds` | read [domain_status_services](/code/tables/domain_status_services.md), read [status_services](/code/tables/status_services.md) |  |
| 120 | `domainStatusOpenIncident` | read [domain_status_incidents](/code/tables/domain_status_incidents.md), read [status_incidents](/code/tables/status_incidents.md) |  |
| 201 | `domainStatusRaise` | **insert** [domain_status_incidents](/code/tables/domain_status_incidents.md) (`domain_id`, `incident_id`, `trigger_kind`, `fingerprint`) |  |
| 219 | `domainStatusImpactId` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 225 | `domainStatusImpactId` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 235 | `domainStatusResolvedName` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 252 | `domainStatusRun` | read [domain_status_services](/code/tables/domain_status_services.md) |  |
| 261 | `domainStatusRun` | read [domain_status_incidents](/code/tables/domain_status_incidents.md) |  |
| 271 | `domainStatusRun` | read [domain_status_incidents](/code/tables/domain_status_incidents.md) |  |
| 275 | `domainStatusRun` | read [domain_status_incidents](/code/tables/domain_status_incidents.md) |  |
| 280 | `domainStatusRun` | read [status_incidents](/code/tables/status_incidents.md) |  |
| 291 | `domainStatusRun` | **update** [domain_status_incidents](/code/tables/domain_status_incidents.md) (`resolved_datetime`) |  |

## How it hangs together

**Includes:** [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), [includes/domains/links.php](/code/files/includes/domains/links.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Included by:** [api/domains/links.php](/code/files/api/domains/links.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [domainSetting()](/code/functions/domainSetting.md), [domainSettings()](/code/functions/domainSettings.md)

