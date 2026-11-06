# api/messaging/test_channel.php

> API endpoint - 247 lines

API Endpoint: test a messaging channel. Three read-mostly checks, each safe to run:

credentials   — validate the stored credentials against the provider (read-only API call).
  reachability  — FreeITSM calls the channel's OWN public webhook URL and confirms it
                  round-trips back to this script (catches a down tunnel / wrong base URL).
  simulate      — run a synthetic inbound message through the real ingest, confirm a
                  ticket is created, then delete the test ticket/message/user.
  all (default from the UI) — run all three and return a structured result.

No real WhatsApp message is ever sent.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/test_channel.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |
| Reads | `GET id`, `GET mode` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [testCredentials()](/code/functions/testCredentials.md) | 97 | Validate credentials with the provider (read-only). |
| [testReachability()](/code/functions/testReachability.md) | 112 | Confirm the channel's own public webhook URL is reachable from the internet. |
| [testSimulation()](/code/functions/testSimulation.md) | 160 | Run a synthetic inbound message through ingest, then clean up everything it created. |
| [simCleanup()](/code/functions/simCleanup.md) | 221 | Remove everything a simulation created (ticket, its messages, the placeholder user) and restore the channel stamp. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 176 | `testSimulation` | read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 203 | `testSimulation` | read [tickets](/code/tables/tickets.md) |  |
| 225 | `simCleanup` | **delete** [emails](/code/tables/emails.md) |  |
| 226 | `simCleanup` | **delete** [tickets](/code/tables/tickets.md) |  |
| 232 | `simCleanup` | read [users](/code/tables/users.md) |  |
| 236 | `simCleanup` | read [tickets](/code/tables/tickets.md) |  |
| 239 | `simCleanup` | **delete** [users](/code/tables/users.md) |  |
| 243 | `simCleanup` | **update** [messaging_channels](/code/tables/messaging_channels.md) (`last_inbound_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingAdminMayAdministerChannel()](/code/functions/messagingAdminMayAdministerChannel.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [normaliseChannelIdentifier()](/code/functions/normaliseChannelIdentifier.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

