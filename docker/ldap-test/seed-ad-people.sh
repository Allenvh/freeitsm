#!/usr/bin/env bash
# Grow the Northwind test directory into a population worth syncing against.
#
# seed-ad-company.sh builds the ten people needed to test SIGN-IN. Directory
# SYNC is a different problem and needs a different fixture: enough people to
# page through, spread across enough OUs that "which OUs do I want" is a real
# question, and carrying the attributes a sync is supposed to map — manager,
# office, telephone, mobile, employee number.
#
# Deliberately included, because each one breaks something that a tidy fixture
# never would:
#
#   - an OU that must NOT be synced (Contractors) — proves OU selection selects
#   - two different people with the SAME display name — proves matching does not
#     fall back to names
#   - a person whose email is somebody else's username — near-miss matching
#   - accounts with no email at all — the warehouse case FreeITSM exists for
#   - disabled accounts, in and out of the Leavers OU — "disabled" and "moved"
#     are separate states and a sync must not conflate them
#   - a manager chain three deep, and one person managing across departments
#   - non-ASCII names, an apostrophe, and a double-barrelled surname
#   - one person in a deeply nested OU
#
# Idempotent: re-running skips anybody who already exists.
#
# Usage: bash docker/ldap-test/seed-ad-people.sh
#        (container freeitsm-samba-ad must be running)
set -u
D=freeitsm-samba-ad
BASE="DC=ad,DC=freeitsm,DC=test"
NW="OU=Northwind,$BASE"
NWREL="OU=Northwind"          # relative form: --userou must NOT include the base DN
PW='Nw!People2026'

st() { docker exec "$D" samba-tool "$@"; }

echo "--- extra organisational units ---"
# NB: `ou create` takes a FULL DN, `user create --userou` takes a RELATIVE one.
# Mixing them up is why the first run of this script created nobody at all.
for ou in \
  "OU=Operations,OU=Staff,$NW" \
  "OU=HR,OU=Staff,$NW" \
  "OU=Marketing,OU=Staff,$NW" \
  "OU=Warehouse,OU=Operations,OU=Staff,$NW" \
  "OU=Contractors,$NW" \
  ; do
  st ou create "$ou" >/dev/null 2>&1 && echo "  + $ou" || echo "  = $ou (exists)"
done

# mkperson <user> <ou> <given> <surname> <mail> <title> <dept> <office> <phone> <mobile> <empid>
# Pass "" for mail to create somebody with no mailbox at all.
mkperson() {
  local u=$1 ou=$2 gn=$3 sn=$4 mail=$5 title=$6 dept=$7 office=$8 phone=$9 mobile=${10} empid=${11}

  if docker exec "$D" samba-tool user show "$u" >/dev/null 2>&1; then
    echo "  = $u (exists)"
  else
    local args=(user create "$u" "$PW" --userou="$ou" --given-name="$gn" --surname="$sn"
                --job-title="$title" --department="$dept" --company="Northwind Trading Ltd"
                --physical-delivery-office="$office" --telephone-number="$phone")
    [ -n "$mail" ] && args+=(--mail-address="$mail")
    if st "${args[@]}" >/dev/null 2>&1; then echo "  + $u"; else echo "  ! $u FAILED"; return; fi
  fi

  # mobile and employeeID have no samba-tool flag, so they go in by LDIF.
  local dn="CN=$gn $sn,$ou,$BASE"
  MSYS_NO_PATHCONV=1 docker exec -i "$D" ldbmodify -H /var/lib/samba/private/sam.ldb >/dev/null 2>&1 <<LDIF || true
dn: $dn
changetype: modify
replace: mobile
mobile: $mobile
-
replace: employeeID
employeeID: $empid
-
LDIF
}

echo "--- IT ---"
mkperson n.hughes   "OU=IT,OU=Staff,$NWREL"        "Nia"    "Hughes"     "n.hughes@northwind.test"   "1st Line Analyst"      "IT"         "London"     "020 7946 0011" "07700 900011" "NW-1011"
mkperson d.okafor   "OU=IT,OU=Staff,$NWREL"        "Daniel" "Okafor"     "d.okafor@northwind.test"   "1st Line Analyst"      "IT"         "London"     "020 7946 0012" "07700 900012" "NW-1012"
mkperson k.silva    "OU=IT,OU=Staff,$NWREL"        "Karina" "Silva"      "k.silva@northwind.test"    "Network Engineer"      "IT"         "Manchester" "0161 496 0013" "07700 900013" "NW-1013"

echo "--- Sales ---"
mkperson m.abara    "OU=Sales,OU=Staff,$NWREL"     "Michael" "Abara"     "m.abara@northwind.test"    "Sales Director"        "Sales"      "London"     "020 7946 0021" "07700 900021" "NW-1021"
mkperson h.novak    "OU=Sales,OU=Staff,$NWREL"     "Hana"   "Novak"      "h.novak@northwind.test"    "Account Manager"       "Sales"      "London"     "020 7946 0022" "07700 900022" "NW-1022"
mkperson g.whitlock "OU=Sales,OU=Staff,$NWREL"     "Grace"  "Whitlock"   "g.whitlock@northwind.test" "Account Manager"       "Sales"      "Manchester" "0161 496 0023" "07700 900023" "NW-1023"
mkperson f.adeyemi  "OU=Sales,OU=Staff,$NWREL"     "Femi"   "Adeyemi"    "f.adeyemi@northwind.test"  "Sales Executive"       "Sales"      "Manchester" "0161 496 0024" "07700 900024" "NW-1024"

echo "--- Finance ---"
mkperson c.doherty  "OU=Finance,OU=Staff,$NWREL"   "Ciara"  "Doherty"    "c.doherty@northwind.test"  "Finance Director"      "Finance"    "London"     "020 7946 0031" "07700 900031" "NW-1031"
mkperson v.raman    "OU=Finance,OU=Staff,$NWREL"   "Vikram" "Raman"      "v.raman@northwind.test"    "Management Accountant" "Finance"    "London"     "020 7946 0032" "07700 900032" "NW-1032"
mkperson e.blazek   "OU=Finance,OU=Staff,$NWREL"   "Eliška" "Blažek"     "e.blazek@northwind.test"   "Accounts Assistant"    "Finance"    "London"     "020 7946 0033" "07700 900033" "NW-1033"

echo "--- HR ---"
mkperson s.mbeki    "OU=HR,OU=Staff,$NWREL"        "Sipho"  "Mbeki"      "s.mbeki@northwind.test"    "HR Manager"            "HR"         "London"     "020 7946 0041" "07700 900041" "NW-1041"
mkperson a.lindqvist "OU=HR,OU=Staff,$NWREL"       "Anja"   "Lindqvist"  "a.lindqvist@northwind.test" "HR Advisor"           "HR"         "London"     "020 7946 0042" "07700 900042" "NW-1042"

echo "--- Marketing ---"
mkperson r.ferreira "OU=Marketing,OU=Staff,$NWREL" "Rui"    "Ferreira"   "r.ferreira@northwind.test" "Marketing Manager"     "Marketing"  "London"     "020 7946 0051" "07700 900051" "NW-1051"
mkperson j.smith    "OU=Marketing,OU=Staff,$NWREL" "John"   "Smith"      "j.smith@northwind.test"    "Content Executive"     "Marketing"  "London"     "020 7946 0052" "07700 900052" "NW-1052"

echo "--- Operations ---"
mkperson b.kowalski "OU=Operations,OU=Staff,$NWREL" "Bartek" "Kowalski"  "b.kowalski@northwind.test" "Operations Manager"    "Operations" "Birmingham" "0121 496 0061" "07700 900061" "NW-1061"
mkperson o.mensah   "OU=Operations,OU=Staff,$NWREL" "Osei"  "Mensah"     "o.mensah@northwind.test"   "Logistics Coordinator" "Operations" "Birmingham" "0121 496 0062" "07700 900062" "NW-1062"

echo "--- Warehouse (deeply nested OU; mostly no mailbox) ---"
mkperson t.byrne    "OU=Warehouse,OU=Operations,OU=Staff,$NWREL" "Tomas" "Byrne"   ""                 "Warehouse Supervisor"  "Operations" "Birmingham" "0121 496 0071" "07700 900071" "NW-1071"
mkperson d.acheampong "OU=Warehouse,OU=Operations,OU=Staff,$NWREL" "Doris" "Acheampong" ""            "Warehouse Operative"   "Operations" "Birmingham" "0121 496 0072" "07700 900072" "NW-1072"
mkperson p.oreilly  "OU=Warehouse,OU=Operations,OU=Staff,$NWREL" "Padraig" "O'Reilly" ""              "Forklift Driver"       "Operations" "Birmingham" "0121 496 0073" "07700 900073" "NW-1073"

echo "--- awkward on purpose ---"
# Same display name as the Marketing John Smith. Nothing but the GUID tells them apart.
mkperson j.smith2   "OU=Sales,OU=Staff,$NWREL"     "John"   "Smith"      "john.smith@northwind.test" "Sales Executive"       "Sales"      "Manchester" "0161 496 0081" "07700 900081" "NW-1081"
# Double-barrelled, and her email is another person's username - a near miss for
# any matching rule that is sloppy about which field it compares.
mkperson a.hall-jones "OU=Marketing,OU=Staff,$NWREL" "Amelia" "Hall-Jones" "j.smith@northwind.test.uk" "Brand Manager"       "Marketing"  "London"     "020 7946 0082" "07700 900082" "NW-1082"
# Left, but never moved to the Leavers OU. Still sitting in Sales, disabled.
mkperson q.deleon   "OU=Sales,OU=Staff,$NWREL"     "Quique" "de León"    "q.deleon@northwind.test"   "Account Manager"       "Sales"      "Manchester" "0161 496 0083" "07700 900083" "NW-1083"
st user disable q.deleon >/dev/null 2>&1 && echo "  ! q.deleon disabled (still in Sales, NOT in Leavers)"
# Moved to Leavers AND disabled - the tidy version of the same thing.
mkperson y.tanaka   "OU=Leavers,$NWREL"            "Yuki"   "Tanaka"     "y.tanaka@northwind.test"   "Former Employee"       "Finance"    "London"     "020 7946 0084" "07700 900084" "NW-1084"
st user disable y.tanaka >/dev/null 2>&1 && echo "  ! y.tanaka disabled (in Leavers)"

echo "--- contractors: an OU that must NOT be synced ---"
mkperson z.contractor "OU=Contractors,$NWREL"      "Zoe"    "Contractor" "zoe@othercompany.test"     "External Consultant"   "Contract"   "Remote"     "020 7946 0091" "07700 900091" "EXT-001"
mkperson w.vendor     "OU=Contractors,$NWREL"      "Walter" "Vendor"     "walter@supplier.test"      "Supplier Engineer"     "Contract"   "Remote"     "020 7946 0092" "07700 900092" "EXT-002"

echo
echo "--- manager chain (three deep, plus one cross-department report) ---"
setmgr() { # <user-dn-fragment> <manager-dn-fragment>
  MSYS_NO_PATHCONV=1 docker exec -i "$D" ldbmodify -H /var/lib/samba/private/sam.ldb >/dev/null 2>&1 <<LDIF && echo "  $1 -> $2" || echo "  ! could not set manager for $1"
dn: CN=$1,$BASE
changetype: modify
replace: manager
manager: CN=$2,$BASE
-
LDIF
}
# Amy Chen (IT Manager) reports to Ciara Doherty (Finance Director) - real orgs
# do this, and a sync that assumes managers share a department gets it wrong.
setmgr "Amy Chen,OU=IT,OU=Staff,OU=Northwind"                 "Ciara Doherty,OU=Finance,OU=Staff,OU=Northwind"
setmgr "Nia Hughes,OU=IT,OU=Staff,OU=Northwind"               "Amy Chen,OU=IT,OU=Staff,OU=Northwind"
setmgr "Daniel Okafor,OU=IT,OU=Staff,OU=Northwind"            "Amy Chen,OU=IT,OU=Staff,OU=Northwind"
setmgr "Karina Silva,OU=IT,OU=Staff,OU=Northwind"             "Amy Chen,OU=IT,OU=Staff,OU=Northwind"
setmgr "Raj Patel,OU=IT,OU=Staff,OU=Northwind"                "Amy Chen,OU=IT,OU=Staff,OU=Northwind"
setmgr "Hana Novak,OU=Sales,OU=Staff,OU=Northwind"            "Michael Abara,OU=Sales,OU=Staff,OU=Northwind"
setmgr "Grace Whitlock,OU=Sales,OU=Staff,OU=Northwind"        "Michael Abara,OU=Sales,OU=Staff,OU=Northwind"
setmgr "Femi Adeyemi,OU=Sales,OU=Staff,OU=Northwind"          "Michael Abara,OU=Sales,OU=Staff,OU=Northwind"
setmgr "Vikram Raman,OU=Finance,OU=Staff,OU=Northwind"        "Ciara Doherty,OU=Finance,OU=Staff,OU=Northwind"
setmgr "Eliška Blažek,OU=Finance,OU=Staff,OU=Northwind"       "Vikram Raman,OU=Finance,OU=Staff,OU=Northwind"
setmgr "Osei Mensah,OU=Operations,OU=Staff,OU=Northwind"      "Bartek Kowalski,OU=Operations,OU=Staff,OU=Northwind"
setmgr "Tomas Byrne,OU=Warehouse,OU=Operations,OU=Staff,OU=Northwind"      "Bartek Kowalski,OU=Operations,OU=Staff,OU=Northwind"
setmgr "Doris Acheampong,OU=Warehouse,OU=Operations,OU=Staff,OU=Northwind" "Tomas Byrne,OU=Warehouse,OU=Operations,OU=Staff,OU=Northwind"
setmgr "Padraig O'Reilly,OU=Warehouse,OU=Operations,OU=Staff,OU=Northwind" "Tomas Byrne,OU=Warehouse,OU=Operations,OU=Staff,OU=Northwind"
setmgr "Anja Lindqvist,OU=HR,OU=Staff,OU=Northwind"           "Sipho Mbeki,OU=HR,OU=Staff,OU=Northwind"
setmgr "John Smith,OU=Marketing,OU=Staff,OU=Northwind"        "Rui Ferreira,OU=Marketing,OU=Staff,OU=Northwind"
setmgr "Amelia Hall-Jones,OU=Marketing,OU=Staff,OU=Northwind" "Rui Ferreira,OU=Marketing,OU=Staff,OU=Northwind"

echo
echo "Done."
echo "  All passwords: $PW   (except those from seed-ad-company.sh)"
echo "  Sync-me OU   : OU=Staff,$NW"
echo "  Do-NOT-sync  : OU=Contractors,$NW  and  OU=Leavers,$NW"
