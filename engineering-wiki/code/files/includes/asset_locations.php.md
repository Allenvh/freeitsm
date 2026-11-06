# includes/asset_locations.php

> PHP - 69 lines

Asset locations: which ones a company can see, including SHARED ones (2.6.0).

Locations were purely per-company on purpose: a client's sites are its own.
A customer asked for locations shared across companies, such as a data centre
or head office that several clients' kit sits in. So a location can now be
marked shared, and every company can pick it, alongside its own private ones.

THE RULES, and why:

 - A shared location is stored with tenant_id NULL and is_shared = 1. NULL
   because it belongs to no one company: a company-owned shared location would
   vanish from every client the day its owner was deleted (the tenant FK
   cascades). The REST API and the importer already treat NULL-tenant
   locations as available to everyone, so this matches them.

 - A shared location's PARENT must be shared too (or it has none). Otherwise
   another company would see a child whose parent it cannot see, and the tree
   would draw an orphan.

 - Only an analyst who can reach EVERY company may create, change or delete a
   shared location. Editing one changes what every client sees, and deleting
   one clears it from every client's assets.

🔑 Every guard checks the COLUMN first: `is_shared` does not exist until
Database Verification has run, and the asset screen must keep working on an
install that has pulled the update but not run it yet.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [assetLocationsSharedReady()](/code/functions/assetLocationsSharedReady.md) | 34 | Has Database Verification added asset_locations.is_shared yet? |
| [assetLocationScope()](/code/functions/assetLocationScope.md) | 44 | The locations one company may see: its own plus every shared one. |
| [assetLocationIsShared()](/code/functions/assetLocationIsShared.md) | 61 | Is this location shared? False for a missing row or an un-verified install. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 | `assetLocationIsShared` | read [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md), [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md), [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md), [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md)

**Calls:** [tenancyColumnExists()](/code/functions/tenancyColumnExists.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

