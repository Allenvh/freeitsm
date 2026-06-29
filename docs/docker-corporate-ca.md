# Docker corporate CA certificates

FreeITSM's Docker image keeps TLS verification enabled. Do not use `composer config secure-http false`, `COMPOSER_DISABLE_TLS`, or any other setting that disables HTTPS verification.

Some corporate networks intercept outbound HTTPS traffic with a local root CA, which can make Composer fail with errors such as:

```text
curl error 60 while downloading https://repo.packagist.org/packages.json:
SSL certificate problem: unable to get local issuer certificate
```

## Safe local CA workflow

1. Export your organisation's root/intermediate CA certificate in PEM format with a `.crt` extension.
2. Place the file under the local `certs/` directory, for example:

   ```text
   certs/company-zscaler-root.crt
   ```

3. Build the Docker image normally:

   ```sh
   docker build -t freeitsm .
   ```

During the build, Docker copies `certs/` to `/tmp/certs/`. If any `*.crt` files are present, they are copied into `/usr/local/share/ca-certificates/` and `update-ca-certificates` is run before Composer installs dependencies.

## Repository safety

- The repository commits only `certs/.gitkeep` so the directory exists for fresh checkouts.
- `.gitignore` ignores `certs/*.crt`, so corporate/root CA files remain local and are not committed.
- Builds still work when `certs/` contains only `.gitkeep`; the optional CA install step simply does nothing.
- Never commit corporate, Zscaler, proxy, or root CA certificates to the repository.
