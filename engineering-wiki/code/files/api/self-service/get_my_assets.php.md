# api/self-service/get_my_assets.php

> API endpoint - 55 lines

API: The equipment assigned to the signed-in portal user (discussion #57).

⚠️ This endpoint is the whole of the portal's permission rule, and it is a
deliberately narrow one: a portal user may only ever attach equipment that is
assigned to THEM. There is no search, no browsing, and no id parameter — the
user is taken from the session and nothing else is reachable.

That does leave shared equipment out of a user's reach: nobody is assigned the
TV in a meeting room, so it cannot be picked here. That is the accepted
behaviour, not an oversight — the alternatives (exposing every unassigned
asset, or asking end users to understand a "shared" flag) were judged more
confusing and more leaky than letting an analyst attach it afterwards.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_my_assets.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [users_assets](/code/tables/users_assets.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

