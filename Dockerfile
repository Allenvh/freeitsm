FROM php:8.4-apache-bookworm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable required PHP extensions and tools for Composer package installation
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        git \
        unzip \
        libonig-dev \
        libzip-dev \
    && update-ca-certificates \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy application files
COPY . /var/www/html/

# Copy Docker-specific config files into place
COPY docker/config.php /var/www/html/config.php
COPY docker/db_config.php /var/www/html/db_config.php

# Optional local corporate/root CAs for development builds. The repository only
# tracks certs/.gitkeep; actual *.crt files are ignored and must remain local.
COPY certs/ /tmp/certs/
RUN set -eux; \
    if find /tmp/certs -type f -name '*.crt' | grep -q .; then \
        find /tmp/certs -type f -name '*.crt' -exec cp {} /usr/local/share/ca-certificates/ \; ; \
        update-ca-certificates; \
    fi

# Install PHP dependencies, including the userland IMAP client.
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Create directories for uploads, attachments, and encryption keys
RUN mkdir -p /var/www/html/tickets/attachments \
    /var/www/html/change-management/attachments \
    /var/www/encryption_keys \
    && chown -R www-data:www-data /var/www/html /var/www/encryption_keys \
    && chmod -R 755 /var/www/html \
    && chmod 700 /var/www/encryption_keys

# Copy entrypoint script (auto-generates encryption key on first boot)
# sed strips Windows CRLF line endings that break bash in Linux
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
