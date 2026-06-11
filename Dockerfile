FROM php:8.2-apache

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql

# Copiar archivos
COPY . /var/www/html/

# Instalar dependencias Node y compilar
RUN npm install && npm run build

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache