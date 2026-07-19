FROM php:8.3-apache

# Active la configuration de développement (permet d'afficher les erreurs PHP à l'écran)
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite headers

# Autorise .htaccess à remplacer la configuration du serveur
RUN sed -i '/<Directory \/>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Définit le nom du serveur pour éviter les messages d'avertissement d'Apache au démarrage
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html/

# On redirige la racine du serveur web vers le dossier /public pour des raisons de sécurité
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
