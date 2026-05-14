# Usar imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql mysqli gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar configuração do Apache
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/myfinances.conf

RUN a2enconf myfinances

# Definir DocumentRoot para /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copiar arquivos do projeto
COPY . /var/www/html/

# Instalar dependências Composer
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader --no-interaction

# Criar diretório de uploads e dar permissões
RUN mkdir -p /var/www/html/storage/uploads/profile && \
    chmod -R 755 /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/storage

# Expor porta 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
