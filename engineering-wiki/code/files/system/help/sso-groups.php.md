# system/help/sso-groups.php

> PHP - 269 lines

System Help — Setting up groups and teams from single sign-on (3.5.0).

A step-by-step guide with a checklist for each identity provider: the parts
done in the provider (tabs: Entra, Okta, Keycloak, other), then the parts done
in FreeITSM, then testing. Written for someone who has not done this before -
Ed asked for it after setting it up against Entra himself. Signposted from the
Groups and teams tab of the provider dialog (System -> SSO).

Ticks are remembered in this browser only (localStorage), per provider tab.
Every "go to System -> X" is a link that opens in a new tab, so the guide stays
open beside the screen being set up.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sgLink()](/code/functions/sgLink.md) | 25 | A "System -> X" link that opens in a new tab. |
| [sgCheck()](/code/functions/sgCheck.md) | 35 | One tick-off step. |

## How it hangs together

**Includes:** [system/help/_init.php](/code/files/system/help/_init.php.md), [system/help/_top.php](/code/files/system/help/_top.php.md), [system/help/_bottom.php](/code/files/system/help/_bottom.php.md)

**Calls:** [helpSectionNum()](/code/functions/helpSectionNum.md)

