FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Restart Apache
CMD ["apache2-foreground"]
