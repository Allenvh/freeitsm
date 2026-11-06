# includes/docker_tls.php

> PHP - 429 lines

HTTPS for the Docker image, with a certificate FreeITSM makes itself.

System → Docker lets an administrator type the name people will use
(freeitsm.internal) and get a working HTTPS certificate for it, without
knowing anything about certificates.

🔑 WHY A PRIVATE CERTIFICATE AUTHORITY AND NOT LET'S ENCRYPT.
A public authority only signs names that exist on the public internet and
that you can prove you own. `.internal` is reserved for private use, so nobody
owns it and nobody public will ever sign it. So this makes a small authority
of its own, signs the server certificate with it, and the administrator
installs the authority's certificate on their PCs once.

🔒 THE AUTHORITY IS NAME-CONSTRAINED. Its certificate ends up trusted by every
PC in the company, and its private key lives in a folder the web server can
write to. Unconstrained, anybody who ever read that key could mint a trusted
certificate for any website at all. Constrained, the worst they can do is sign
the one name (and IP) it was made for. The price: a new name means a new
authority, and the new one has to be installed on the PCs again. The screen
says so before it happens.

Files, all in DOCKER_TLS_DIR (a Docker volume, so a rebuild keeps them):
  ca.crt        the authority's certificate — the ONLY file to hand out
  ca.key        the authority's private key — never leaves this folder
  server.crt    the server certificate followed by ca.crt (the full chain)
  server.key    the server certificate's private key
  settings.json the name and IP the certificates were made for
  loaded.txt    written by docker/entrypoint.sh: the fingerprint of the
                certificate Apache actually started with. Comparing it with
                server.crt is how the screen knows a restart is still due.

⚠️ PHP cannot switch HTTPS on by itself. Apache reads the certificate at start
and runs as root to do it; PHP runs as www-data. So generating writes the
files and the screen tells the operator to restart the container, where
docker/entrypoint.sh checks the files and only then turns HTTPS on. A broken
or mismatched certificate leaves the container on plain HTTP, never down.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dockerTlsNormaliseHost()](/code/functions/dockerTlsNormaliseHost.md) | 61 | Tidy what someone typed into a bare host name, or null if it is not one. |
| [dockerTlsNormaliseIp()](/code/functions/dockerTlsNormaliseIp.md) | 87 | A valid IPv4 or IPv6 address, or null. Empty input is allowed and gives null. |
| [dockerTlsBrowserAddress()](/code/functions/dockerTlsBrowserAddress.md) | 101 | What the browser used to reach this page: host, port and whether host is an IP. |
| [dockerTlsRequestIsHttps()](/code/functions/dockerTlsRequestIsHttps.md) | 124 | True when this request arrived over HTTPS, as PHP reports it in `$_SERVER`['HTTPS']. |
| [dockerTlsFingerprintNormalise()](/code/functions/dockerTlsFingerprintNormalise.md) | 134 | Lower-case hex with no separators, so PHP's and the openssl CLI's agree. |
| [dockerTlsCertInfo()](/code/functions/dockerTlsCertInfo.md) | 145 | Read one PEM certificate's details, or null. |
| [dockerTlsState()](/code/functions/dockerTlsState.md) | 166 | Everything the screen needs to know, in one place. |
| [dockerTlsNeedsNewCa()](/code/functions/dockerTlsNeedsNewCa.md) | 235 | Would making a certificate for this name and IP need a NEW authority? |
| [dockerTlsSingleAddressMask()](/code/functions/dockerTlsSingleAddressMask.md) | 254 | The netmask that pins a name constraint to exactly one address. |
| [dockerTlsOpensslConfig()](/code/functions/dockerTlsOpensslConfig.md) | 272 | The OpenSSL config both certificates are made from. |
| [dockerTlsWriteFile()](/code/functions/dockerTlsWriteFile.md) | 310 | Write a file by renaming a temp copy over it, so a reader never sees half of one. |
| [dockerTlsGenerate()](/code/functions/dockerTlsGenerate.md) | 335 | Make the certificates. Returns ['new_ca' => bool, 'server' => info, 'ca' => info]. |
| [dockerTlsOpensslError()](/code/functions/dockerTlsOpensslError.md) | 423 | Drain OpenSSL's error queue into one line. |

## How it hangs together

**Included by:** [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md), [system/docker/index.php](/code/files/system/docker/index.php.md)

**Calls:** [storagePersistenceStatus()](/code/functions/storagePersistenceStatus.md)

