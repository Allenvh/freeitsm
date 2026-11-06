#!/usr/bin/env bash
# Seed the throwaway Baikal with a user, two address books and some contacts.
#
# Idempotent: the user and books are created only if missing, and each vCard is
# PUT to a fixed URL so re-running replaces rather than duplicates.
#
# ⚠️ Contacts go in over REAL CardDAV with curl rather than straight into
# SQLite, on purpose — it exercises the same path a phone or Thunderbird uses,
# so a fixture that seeds successfully has also proved the server works.
set -euo pipefail

C=freeitsm-carddav
BASE=http://localhost:8092
USER=itsm
PASS=itsm
REALM=BaikalDAV

if ! docker ps --format '{{.Names}}' | grep -qx "$C"; then
    echo "container $C is not running — start it with:" >&2
    echo "  docker compose -f docker/carddav-test/docker-compose.yml up -d" >&2
    exit 1
fi

# --- the user, its principal and two books -----------------------------------
# Via SQLite because Baikal has no CLI and its admin UI cannot be scripted.
# `digesta1` is md5("user:realm:password") — that is the whole reason Digest
# works without storing the password.
docker exec "$C" php -r '
$db = new PDO("sqlite:/var/www/baikal/Specific/db/db.sqlite");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
[$user, $pass, $realm] = [$argv[1], $argv[2], $argv[3]];

$have = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$have->execute([$user]);
if (!$have->fetchColumn()) {
    $db->prepare("INSERT INTO users (username, digesta1) VALUES (?, ?)")
       ->execute([$user, md5("$user:$realm:$pass")]);
    $db->prepare("INSERT INTO principals (uri, email, displayname) VALUES (?, ?, ?)")
       ->execute(["principals/$user", "$user@carddav.test", "ITSM Contacts"]);
    echo "created user $user\n";
}
// TWO books deliberately. One is the default; the other is named `itsm` so the
// "scope the sync to one group" case can be tested in its simplest form, where
// the group IS a separate collection with its own URL.
foreach ([["default","Default Address Book"],["itsm","ITSM Group"]] as [$uri,$name]) {
    $q = $db->prepare("SELECT COUNT(*) FROM addressbooks WHERE principaluri = ? AND uri = ?");
    $q->execute(["principals/$user", $uri]);
    if (!$q->fetchColumn()) {
        $db->prepare("INSERT INTO addressbooks (principaluri, displayname, uri, description, synctoken)
                      VALUES (?, ?, ?, ?, 1)")
           ->execute(["principals/$user", $name, $uri, "seeded fixture"]);
        echo "created address book /$uri\n";
    }
}
' "$USER" "$PASS" "$REALM"

# --- the contacts ------------------------------------------------------------
# ⚠️ --digest, not --basic. Baikal ships `dav_auth_type: Digest`, and Basic
# against a stock install is a flat 401. Getting this wrong reads as "wrong
# password" rather than "wrong auth scheme", which is a bad afternoon.
put_card() {
    local book="$1" uid="$2" vcf="$3"
    printf '%s' "$vcf" | curl -sS -u "$USER:$PASS" --digest \
        -X PUT -H 'Content-Type: text/vcard; charset=utf-8' \
        --data-binary @- \
        "$BASE/dav.php/addressbooks/$USER/$book/$uid.vcf" \
        -o /dev/null -w "  $book/$uid.vcf  http=%{http_code}\n"
}

# Deliberately varied: one with everything, one missing an email (a person can
# be a real contact with no mailbox), one with CATEGORIES, and a KIND:group card
# — because "group" means three different things in CardDAV and the importer has
# to be built against whichever one the operator actually uses.
put_card itsm alice "BEGIN:VCARD
VERSION:3.0
UID:alice
FN:Alice Fairweather
N:Fairweather;Alice;;;
ORG:Fairweather Joinery
TITLE:Managing Director
EMAIL;TYPE=WORK:alice@fairweather.test
TEL;TYPE=WORK,VOICE:0113 496 0001
TEL;TYPE=CELL:07700 900001
ADR;TYPE=WORK:;;12 Kirkgate;Leeds;;LS1 1AA;United Kingdom
CATEGORIES:itsm,customer
END:VCARD
"

put_card itsm bruno "BEGIN:VCARD
VERSION:3.0
UID:bruno
FN:Bruno Kowalczyk
N:Kowalczyk;Bruno;;;
ORG:Kowalczyk Logistics
TITLE:Operations Manager
TEL;TYPE=WORK,VOICE:0161 496 0002
CATEGORIES:itsm
END:VCARD
"

put_card itsm chen "BEGIN:VCARD
VERSION:3.0
UID:chen
FN:Chen Wei
N:Wei;Chen;;;
EMAIL;TYPE=WORK:chen.wei@example.test
TEL;TYPE=CELL:07700 900003
END:VCARD
"

# A KIND:group card. This is how Apple Contacts represents a group: one vCard
# whose MEMBER properties point at the others by UID.
put_card itsm itsm-group "BEGIN:VCARD
VERSION:3.0
UID:itsm-group
FN:ITSM
KIND:group
MEMBER:urn:uuid:alice
MEMBER:urn:uuid:bruno
END:VCARD
"

# One card OUTSIDE the itsm book, so a scoped import can be proved to leave it
# alone. If this ever turns up in FreeITSM, the scoping is not working.
put_card default dora "BEGIN:VCARD
VERSION:3.0
UID:dora
FN:Dora Nkemelu
N:Nkemelu;Dora;;;
EMAIL;TYPE=WORK:dora@should-not-be-imported.test
END:VCARD
"

echo
echo "address books now:"
curl -sS -u "$USER:$PASS" --digest -X PROPFIND -H 'Depth: 1' \
     -H 'Content-Type: application/xml' \
     --data '<?xml version="1.0"?><d:propfind xmlns:d="DAV:"><d:prop><d:displayname/></d:prop></d:propfind>' \
     "$BASE/dav.php/addressbooks/$USER/" \
  | grep -o '<d:href>[^<]*</d:href>' | sed 's/<[^>]*>//g' | sed 's/^/  /'

# --- calendars (CalDAV, #133) ------------------------------------------------
# Two users, each with a "Work" calendar, so calendar sync can be tested the
# way it is used: FreeITSM signs in AS each analyst, and a ticket reassigned
# from one to the other has to leave one account's calendar and arrive in the
# other's. `itsm` also gets a second, TASKS-ONLY calendar, which the calendar
# picker must leave out because it cannot hold events.
docker exec "$C" php -r '
$db = new PDO("sqlite:/var/www/baikal/Specific/db/db.sqlite");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$realm = $argv[1];
$user = "tech2";
$q = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$q->execute([$user]);
if (!$q->fetchColumn()) {
    $db->prepare("INSERT INTO users (username, digesta1) VALUES (?, ?)")
       ->execute([$user, md5("$user:$realm:$user")]);
    $db->prepare("INSERT INTO principals (uri, email, displayname) VALUES (?, ?, ?)")
       ->execute(["principals/$user", "$user@carddav.test", "Second technician"]);
    echo "created user $user\n";
}
' "$REALM"

mkcal() {
    local user="$1" uri="$2" name="$3" comp="$4"
    curl -sS -u "$user:$user" --digest -X MKCALENDAR -H 'Content-Type: application/xml' \
        --data "<?xml version=\"1.0\"?><c:mkcalendar xmlns:d=\"DAV:\" xmlns:c=\"urn:ietf:params:xml:ns:caldav\"><d:set><d:prop><d:displayname>$name</d:displayname><c:supported-calendar-component-set><c:comp name=\"$comp\"/></c:supported-calendar-component-set></d:prop></d:set></c:mkcalendar>" \
        "$BASE/dav.php/calendars/$user/$uri/" \
        -o /dev/null -w "  calendars/$user/$uri  http=%{http_code} (201 created, 405 already there)\n"
}
# itsm's password is itsm, and tech2's is tech2.
mkcal itsm  work   "Work"   VEVENT
mkcal itsm  todo   "To-do"  VTODO
mkcal tech2 work   "Work"   VEVENT
