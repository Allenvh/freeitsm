const fs = require('fs'), vm = require('vm'), assert = require('assert');
const source = fs.readFileSync('tickets/settings/index.php', 'utf8');
const fields = {};
function element(id) { return fields[id] ??= {value: '', style: {}, validity: '', required: false, closest() { return this; }, setCustomValidity(value) { this.validity = value; }}; }
const context = {document: {getElementById: element, querySelectorAll: () => []}, t: x => x,
 getDefaultOAuthRedirectUri: p => p === 'google' ? '/google_oauth_callback.php' : '/oauth_callback.php'};
vm.createContext(context);
vm.runInContext(source.slice(source.indexOf('        function toggleAuthModeFields()'), source.indexOf('        function getDefaultOAuthRedirectUri(')), context);
for (const [provider, auth, oauth] of [['imap_smtp','delegated',false], ['google','delegated',true], ['microsoft','delegated',true], ['microsoft','app_only',false]]) {
 element('mailboxProvider').value = provider; element('mailboxAuthMode').value = auth;
 element('mailboxId').value = '1'; element('mailboxRedirectUri').value = '/oauth_callback.php'; element('mailboxRedirectUri').validity = 'stale error';
 context.toggleProviderFields();
 assert.equal(element('mailboxRedirectUri').required, oauth);
 assert.equal(element('mailboxRedirectUri').style.display, oauth ? '' : 'none');
 assert.equal(element('mailboxScopes').style.display, oauth ? '' : 'none');
 assert.equal(element('mailboxRedirectUri').validity, '');
 console.log(`PASS ${provider}/${auth}: OAuth visibility and validation`);
}
