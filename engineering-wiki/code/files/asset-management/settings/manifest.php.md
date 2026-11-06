# asset-management/settings/manifest.php

> PHP - 182 lines

Asset Management — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. Everything else is derived from this file:

  - the tab bar on the settings page (only the tabs you may see are rendered);
  - the capabilities offered on System → Roles, with their descriptions and badges;
  - who may write each of the system_settings keys listed below.

See includes/capabilities.php for how the derivation works, and
includes/settings_manifest.php for the renderer.

Per tab:
  'id'           the tab's DOM id (its panel is <id>-tab)
  'cap'          the Cap:: constant required to see and use it.
                 null = a personal preference, not administration: always visible,
                 never gated, contributes no capability.
  'label_key'    i18n key for the tab's NAME on the tab bar
  'grant'        plain-English description of the PERMISSION, shown in System → Roles
  'sensitive'    true if it reaches credentials, email, money or the audit trail —
                 badged in the Roles picker so granting it makes you think
  'setting_keys' the system_settings keys this tab writes through the shared
                 save_system_settings.php endpoint (enforced in settings_keys.php,
                 which derives from this list)

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

