FROM php:8.2-apache

# Install SQLite development libraries and utilities
RUN apt-get update && apt-get install -y --no-install-recommends \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Enable required Apache modules
RUN a2enmod rewrite headers

# Install PDO and SQLite extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure production PHP settings
RUN { \
    echo 'upload_max_filesize = 32M'; \
    echo 'post_max_size = 32M'; \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 120'; \
    echo 'session.cookie_httponly = 1'; \
    echo 'session.use_strict_mode = 1'; \
    echo 'date.timezone = Asia/Kolkata'; \
} > /usr/local/etc/php/conf.d/attendease.ini

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Create and set proper permissions for SQLite database and config directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/config \
    && touch /var/www/html/config/attendease.db \
    && chown www-data:www-data /var/www/html/config/attendease.db \
    && chmod 664 /var/www/html/config/attendease.db

# Expose HTTP port
EXPOSE 80

# Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

CMD ["apache2-foreground"]
