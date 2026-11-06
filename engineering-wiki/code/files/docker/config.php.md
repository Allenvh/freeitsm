# docker/config.php

> PHP - 72 lines

Docker Configuration for FreeITSM Credentials are read from environment variables set in docker-compose.yml

## How it hangs together

**Includes:** `docker/includes/ssl.php`, `docker/includes/base_url.php`

**Calls:** [sslResolveCaBundle()](/code/functions/sslResolveCaBundle.md)

