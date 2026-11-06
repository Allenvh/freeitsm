# includes/capabilities.php

> PHP - 659 lines

RBAC Layer 2 — the capability registry.

A capability is an administrative permission: "may this analyst configure X?"
See docs/design/rbac.md. This file owns WHAT capabilities exist. includes/rbac.php
owns WHO holds them and enforces it.

---------------------------------------------------------------------------
WHY CONSTANTS AND NOT STRINGS
---------------------------------------------------------------------------
Phase 3 gives every settings TAB its own capability — around 70 of them, guarded
at a couple of hundred call sites. At that scale a bare string key is dangerous,
because of how it fails:

    requireCapabilityJson('lms.mange');    // <- typo

That does not error. It fails CLOSED, exactly as designed: a permanent, polite
403 that reads like a policy decision. Worse, `analystHasCapability()` short-circuits
true for is_admin BEFORE it looks at the string — so every test the maintainer runs
passes, and the only people who see it are users, who assume they simply aren't
allowed and ask to be made an admin. A typo quietly becomes a privilege escalation.

    requireCapabilityJson(Cap::LMS_MANGE); // <- Fatal error: Undefined constant

Same typo, opposite failure: loud, immediate, at the call site, on the first request
that touches the line — and it fires for the admin too, because the fatal happens
before the is_admin bypass can hide it. That asymmetry is the whole reason this
class exists. ALWAYS pass a Cap:: constant. Never write the string.

---------------------------------------------------------------------------
THE ENUM BRIDGE
---------------------------------------------------------------------------
PHP 8.1 enums are the right tool here; the project's floor is 7.4, so we can't use
them (see the wiki: "Raising the PHP floor to 8.1"). Every helper below is therefore
named after the enum method it will become, so the swap stays a find-and-replace:

    `capAll()`              -> Capability::cases()
    capFromKey(`$s`)        -> Capability::tryFrom(`$s`)
    capLabel(`$c`)          -> `$c`->label()
    capModule(`$c`)         -> `$c`->module()
    capIsSensitive(`$c`)    -> `$c`->isSensitive()
    capsForModule(`$m`)     -> Capability::forModule(`$m`)

If you add a helper, name it after the method it would be on the enum.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [settingsManifests()](/code/functions/settingsManifests.md) | 314 | Every module's settings manifest, loaded once. |
| [settingsManifestFor()](/code/functions/settingsManifestFor.md) | 333 | The manifest for one module, or null. |
| [capModules()](/code/functions/capModules.md) | 345 | Modules that own capabilities, and how to name them in the Roles picker. DERIVED from the manifests. Keys are the Layer 1 module slugs. |
| [capRegistry()](/code/functions/capRegistry.md) | 364 | The capability registry — DERIVED from the manifests. |
| [capAliases()](/code/functions/capAliases.md) | 405 | Retired keys -> their current replacement. |
| [capAll()](/code/functions/capAll.md) | 417 | Every declared capability key. Enum twin: Capability::cases(). |
| [capExists()](/code/functions/capExists.md) | 427 | Is this a capability the code defines? Enum twin: Capability::tryFrom() !== null. |
| [capFromKey()](/code/functions/capFromKey.md) | 441 | Resolve a key that came from OUTSIDE the code (a DB row, a form post) to a known capability, or null if the code no longer defines it. Enum twin: Capability::tryFrom(). |
| [capLabel()](/code/functions/capLabel.md) | 454 | Human description, shown in System -> Roles. Enum twin: `$cap`->label(). |
| [capModule()](/code/functions/capModule.md) | 464 | The module this capability administers. Enum twin: `$cap`->module(). |
| [capModuleLabel()](/code/functions/capModuleLabel.md) | 474 | Display name for a module slug. Enum twin: `$module`->label(). |
| [capIsSensitive()](/code/functions/capIsSensitive.md) | 484 | Does granting this need a second thought? Enum twin: `$cap`->isSensitive(). |
| [capsForModule()](/code/functions/capsForModule.md) | 494 | Every capability belonging to a module. Enum twin: Capability::forModule(). |
| [capUmbrella()](/code/functions/capUmbrella.md) | 508 | The module's umbrella capability ('<module>.manage'), or null if it has none. |
| [capExpandUmbrellas()](/code/functions/capExpandUmbrellas.md) | 528 | Expand umbrella grants: holding '<module>.manage' means holding every capability in that module. |
| [capGroups()](/code/functions/capGroups.md) | 547 | The registry shaped for the System -> Roles picker: grouped by module, labelled, with the umbrella first so "manage everything here" reads as the headline choice. |
| [capSelfCheck()](/code/functions/capSelfCheck.md) | 591 | Self-check: the things derivation CANNOT rule out. |
| [capIsUmbrella()](/code/functions/capIsUmbrella.md) | 655 | Is this capability its module's umbrella? |

## Classes

- [Cap](/code/classes/Cap.md) (class, line 62) - The capability keys. THE TYPE. Every guard call site names one of these constants.

## How it hangs together

**Included by:** [api/assets/asset_tag_preview.php](/code/files/api/assets/asset_tag_preview.php.md), [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md), [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md), [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md), [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md), [asset-management/labels.php](/code/files/asset-management/labels.php.md), [asset-management/settings/manifest.php](/code/files/asset-management/settings/manifest.php.md), [calendar/settings/manifest.php](/code/files/calendar/settings/manifest.php.md), [change-management/settings/manifest.php](/code/files/change-management/settings/manifest.php.md), [cmdb/settings/manifest.php](/code/files/cmdb/settings/manifest.php.md), [contracts/settings/manifest.php](/code/files/contracts/settings/manifest.php.md), [domains/settings/manifest.php](/code/files/domains/settings/manifest.php.md), [files/settings/manifest.php](/code/files/files/settings/manifest.php.md), [forms/settings/manifest.php](/code/files/forms/settings/manifest.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [knowledge/settings/manifest.php](/code/files/knowledge/settings/manifest.php.md), [lms/settings/manifest.php](/code/files/lms/settings/manifest.php.md), [morning-checks/settings/manifest.php](/code/files/morning-checks/settings/manifest.php.md), [problem-management/settings/manifest.php](/code/files/problem-management/settings/manifest.php.md), [process-mapper/settings/manifest.php](/code/files/process-mapper/settings/manifest.php.md), [projects/settings/manifest.php](/code/files/projects/settings/manifest.php.md), [service-status/settings/manifest.php](/code/files/service-status/settings/manifest.php.md), [software/settings/manifest.php](/code/files/software/settings/manifest.php.md), [tasks/settings/manifest.php](/code/files/tasks/settings/manifest.php.md), [tickets/settings/manifest.php](/code/files/tickets/settings/manifest.php.md), [war-room/settings/manifest.php](/code/files/war-room/settings/manifest.php.md), [watchtower/settings/manifest.php](/code/files/watchtower/settings/manifest.php.md), [workflow/settings/manifest.php](/code/files/workflow/settings/manifest.php.md)

**Calls:** [settingKeyOwner()](/code/functions/settingKeyOwner.md)

