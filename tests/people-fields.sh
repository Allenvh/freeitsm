#!/bin/bash
# Directory sync slice 1 - the person fields, end to end through the real API.
BASE="http://localhost/freeitsm-app"
SID="people$$"
printf 'analyst_id|i:1;analyst_name|s:13:"Administrator";username|s:5:"admin";is_admin|i:1;' > "c:/wamp64/tmp/sess_$SID"
MY() { PW=$(sed -n "s/.*define('DB_PASSWORD',[[:space:]]*'\([^']*\)').*/\1/p" /c/wamp64/db_config.php)
       /c/wamp64/bin/mysql/mysql9.1.0/bin/mysql.exe -u root ${PW:+-p"$PW"} FREEITSM -sN -e "$1" 2>/dev/null; }
post() { curl -s -b "PHPSESSID=$SID" -H 'Content-Type: application/json' -d "$1" "$BASE/api/tickets/save_user.php"; }

pass=0; fail=0
chk() { if [ "$2" = "$3" ]; then printf "  ok    %-52s %s\n" "$1" "$3"; pass=$((pass+1));
        else printf "  FAIL  %-52s got [%s] want [%s]\n" "$1" "$3" "$2"; fail=$((fail+1)); fi; }

echo; echo "Directory sync slice 1 - person fields"; printf '%.0s=' {1..78}; echo

# --- create, with the new fields -------------------------------------------
R=$(post '{"display_name":"Slice1 Tester","email":"slice1.tester@example.test","job_title":"Test Engineer","department":"QA","office":"Bristol","phone":"0117 000 0001","mobile":"07700 900999","employee_id":"T-0001"}')
ID=$(echo "$R" | grep -o '"id":[0-9]*' | head -1 | cut -d: -f2)
chk "create returns an id" "yes" "$([ -n "$ID" ] && echo yes || echo no)"
chk "job_title stored"   "Test Engineer" "$(MY "SELECT job_title FROM users WHERE id=$ID")"
chk "department stored"  "QA"            "$(MY "SELECT department FROM users WHERE id=$ID")"
chk "office stored"      "Bristol"       "$(MY "SELECT office FROM users WHERE id=$ID")"
chk "employee_id stored" "T-0001"        "$(MY "SELECT employee_id FROM users WHERE id=$ID")"
chk "new person is active by default" "1" "$(MY "SELECT is_active FROM users WHERE id=$ID")"
chk "new person is NOT directory-managed" "0" "$(MY "SELECT is_managed FROM users WHERE id=$ID")"

# --- a second person, to be the manager ------------------------------------
R2=$(post '{"display_name":"Slice1 Manager","email":"slice1.manager@example.test","job_title":"QA Lead"}')
MGR=$(echo "$R2" | grep -o '"id":[0-9]*' | head -1 | cut -d: -f2)

post "{\"id\":$ID,\"manager_id\":$MGR}" > /dev/null
chk "manager set" "$MGR" "$(MY "SELECT manager_id FROM users WHERE id=$ID")"

# --- blank means NULL, never '' --------------------------------------------
post "{\"id\":$ID,\"office\":\"\"}" > /dev/null
chk "blanking a field writes NULL, not ''" "1" "$(MY "SELECT office IS NULL FROM users WHERE id=$ID")"

# --- the manager-loop guard -------------------------------------------------
E=$(post "{\"id\":$MGR,\"manager_id\":$ID}")
chk "a two-person manager loop is refused" "1" "$(echo "$E" | grep -c 'loop back on itself')"
chk "  ...and the loop was NOT written" "1" "$(MY "SELECT manager_id IS NULL FROM users WHERE id=$MGR")"

E=$(post "{\"id\":$ID,\"manager_id\":$ID}")
chk "being your own manager is refused" "1" "$(echo "$E" | grep -c 'loop back on itself')"

E=$(post "{\"id\":$ID,\"manager_id\":999999}")
chk "a manager who does not exist is refused" "1" "$(echo "$E" | grep -c 'does not exist')"

# --- deactivate / reactivate ------------------------------------------------
post "{\"id\":$ID,\"is_active\":0}" > /dev/null
chk "deactivate sets is_active=0" "0" "$(MY "SELECT is_active FROM users WHERE id=$ID")"
chk "  ...and stamps deactivated_datetime" "1" "$(MY "SELECT deactivated_datetime IS NOT NULL FROM users WHERE id=$ID")"
chk "  ...and does NOT delete the person" "1" "$(MY "SELECT COUNT(*) FROM users WHERE id=$ID")"

post "{\"id\":$ID,\"is_active\":1}" > /dev/null
chk "reactivate sets is_active=1" "1" "$(MY "SELECT is_active FROM users WHERE id=$ID")"
chk "  ...and clears deactivated_datetime" "1" "$(MY "SELECT deactivated_datetime IS NULL FROM users WHERE id=$ID")"

# --- directory-owned fields on a MANAGED record -----------------------------
MY "UPDATE users SET is_managed=1 WHERE id=$ID" > /dev/null
E=$(post "{\"id\":$ID,\"department\":\"Should Not Stick\"}")
chk "managed record refuses a directory-owned edit" "1" "$(echo "$E" | grep -c 'kept up to date from a directory')"
chk "  ...and the old value survived" "QA" "$(MY "SELECT department FROM users WHERE id=$ID")"
E=$(post "{\"id\":$ID,\"display_name\":\"Renamed Locally\"}")
chk "managed record still allows a NON-directory field" "1" "$(echo "$E" | grep -c '"success":true')"
MY "UPDATE users SET is_managed=0 WHERE id=$ID" > /dev/null

# --- the reporting line, read in BOTH directions ----------------------------
# manager_id is stored once, pointing upwards, so "who does she manage" can only
# be answered by looking for everybody pointing at her. Easy to add one way and
# forget the other, hence a test for each.
post "{\"id\":$ID,\"manager_id\":$MGR}" > /dev/null
detail() { curl -s -b "PHPSESSID=$SID" "$BASE/api/assets/get_user_assets.php?user_id=$1"; }
chk "the report knows their manager"  "1" "$(detail $ID | grep -c '"manager_name":"Slice1 Manager"')"
# Matched on ID, not name: an earlier test in this script renames this person,
# and an assertion that depends on a name set 40 lines earlier is a test that
# breaks for reasons unrelated to what it is testing.
chk "the manager lists their report"  "1" "$(detail $MGR | grep -c "\"reports\":\[{\"id\":$ID,")"
chk "a person with nobody under them lists none" "1" "$(detail $ID | grep -c '"reports":\[\]')"

# The detail panel must be complete for somebody OUTSIDE the current list too —
# it used to read the person fields from the loaded rows, so following a link to
# anyone filtered out produced a panel with no details in it.
chk "detail carries the person fields, not just a name" "1" "$(detail $ID | grep -c '"department":"QA"')"

# --- the directory list shows people holding nothing ------------------------
count() { curl -s -b "PHPSESSID=$SID" "$BASE/api/assets/get_people.php?scope=$1&search=Slice1" | grep -o '"id":' | wc -l; }
chk "directory lists people with no equipment" "2" "$(count current)"
chk "CONTROL: 'holding' excludes people with none" "0" "$(count holding)"

# --- the four scopes mean what they say -------------------------------------
# 'everyone' has to include leavers, or the label is a lie. It originally did
# not, which is the bug this block exists to stop coming back.
MY "UPDATE users SET is_active=0 WHERE id=$ID" > /dev/null
chk "a leaver disappears from 'current'"        "1" "$(count current)"
chk "a leaver appears under 'leavers'"          "1" "$(count leavers)"
chk "'everyone' MEANS everyone (leaver + current)" "2" "$(count everyone)"
MY "UPDATE users SET is_active=1 WHERE id=$ID" > /dev/null
chk "CONTROL: reactivated, 'leavers' is empty"  "0" "$(count leavers)"

# --- clean up ---------------------------------------------------------------
MY "DELETE FROM users WHERE id IN ($ID,$MGR)" > /dev/null
chk "test people removed" "0" "$(MY "SELECT COUNT(*) FROM users WHERE id IN ($ID,$MGR)")"

rm -f "c:/wamp64/tmp/sess_$SID"
printf '%.0s=' {1..78}; echo
echo "  $pass passed, $fail failed"
