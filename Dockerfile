# Usar imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

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

# Criar diretório de uploads e dar permissões
RUN mkdir -p /var/www/html/storage/uploads/profile && \
    chmod -R 755 /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/storage

# Expor porta 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
