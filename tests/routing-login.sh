#!/bin/bash
# Issue #68: the app must find its own login page with NO URL rewriting.
# Run twice - once with .htaccess in place (Apache), once without (nginx).
cd /c/wamp64/www/freeitsm-app || exit 1
BASE="http://localhost/freeitsm-app"

code() { curl -s -o /dev/null -w "%{http_code}" "$1"; }
loc()  { curl -s -o /dev/null -w "%{redirect_url}" "$1"; }

run_suite() {
  local mode="$1" pass=0 fail=0
  echo
  echo "### $mode"
  printf '%.0s-' {1..66}; echo

  chk() { # name expected actual
    if [ "$2" = "$3" ]; then printf "  ok    %-46s %s\n" "$1" "$3"; pass=$((pass+1))
    else printf "  FAIL  %-46s got %s, expected %s\n" "$1" "$3" "$2"; fail=$((fail+1)); fi
  }

  # The real file must be reachable at its real path, with no rewriting.
  chk "auth/login.php is reachable"        "200" "$(code "$BASE/auth/login.php")"
  chk "self-service/login.php reachable"   "200" "$(code "$BASE/self-service/login.php")"

  # A logged-out visitor must land somewhere that EXISTS.
  local target; target=$(loc "$BASE/tickets/")
  local tcode;  tcode=$(code "$target")
  chk "logged-out /tickets/ redirect resolves" "200" "$tcode"
  echo "        -> $target"

  # And the same for the site root.
  local root; root=$(loc "$BASE/")
  local rcode; rcode=$(code "$root")
  chk "logged-out / redirect resolves"     "200" "$rcode"
  echo "        -> $root"

  # A deeper module page (../../ depth) must resolve too.
  local deep; deep=$(loc "$BASE/cmdb/settings/")
  chk "logged-out /cmdb/settings/ resolves" "200" "$(code "$deep")"
  echo "        -> $deep"

  # The logo on the login page must load from wherever the page is served.
  chk "login page logo loads"              "200" "$(code "$BASE/assets/images/CompanyLogo.png")"

  # Where the login page sends you AFTER signing in. This is the one that bit:
  # auth/login.php redirected to a relative "index.php", which resolved to the
  # root while the page was served at /login, and to /auth/index.php (404) once
  # the app started linking to the real path. Every redirect out of auth/ must be
  # absolute, because these pages are reachable at two different URL depths.
  local sid="routetest$$"
  printf 'analyst_id|i:1;analyst_name|s:13:"Administrator";username|s:5:"admin";is_admin|i:1;' \
    > "c:/wamp64/tmp/sess_$sid"
  local after; after=$(curl -s -o /dev/null -b "PHPSESSID=$sid" -w "%{redirect_url}" "$BASE/auth/login.php")
  # Follow it WITH the session, or index.php quite rightly bounces us back to login.
  chk "post-login redirect resolves"       "200" \
      "$(curl -s -o /dev/null -b "PHPSESSID=$sid" -w '%{http_code}' "$after")"
  case "$after" in
    */auth/index.php) printf "  FAIL  %-46s %s\n" "post-login target is not inside auth/" "$after"; fail=$((fail+1));;
    *) printf "  ok    %-46s %s\n" "post-login target is not inside auth/" "$after"; pass=$((pass+1));;
  esac
  rm -f "c:/wamp64/tmp/sess_$sid"

  echo "  $pass passed, $fail failed"
  return $fail
}

TOTAL=0
run_suite "WITH .htaccess (Apache)"; TOTAL=$((TOTAL+$?))

mv .htaccess .htaccess.t68 2>/dev/null
mv auth/.htaccess auth/.htaccess.t68 2>/dev/null
run_suite "WITHOUT .htaccess (simulating nginx)"; TOTAL=$((TOTAL+$?))
mv .htaccess.t68 .htaccess 2>/dev/null
mv auth/.htaccess.t68 auth/.htaccess 2>/dev/null

echo
printf '%.0s=' {1..66}; echo
if [ -f .htaccess ] && [ -f auth/.htaccess ]; then echo "  .htaccess files restored OK"; else echo "  *** .htaccess NOT RESTORED ***"; fi
[ "$TOTAL" -eq 0 ] && echo "  ALL PASSED in both modes" || echo "  $TOTAL FAILED"
