# contracts/settings/manifest.php

> PHP - 85 lines

Contracts — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. The tab bar, the tick-boxes on System → Roles and their descriptions are
all derived from this file. See includes/capabilities.php.

Note what the split buys here. Five of these tabs are plain lookup lists — the kind of
thing a contracts administrator maintains without a second thought. The **RFP AI** tab
holds an AI provider's API key, which costs money and can be pointed at an attacker's
proxy. Under a single 'manage the Contracts settings' permission those would be the
same grant. They are not the same thing.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

