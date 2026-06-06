FROM laradock_7_2-apache2:latest

RUN rm -f /etc/apache2/sites-available/default-ssl.conf \
    && a2enmod proxy proxy_fcgi rewrite headers ssl

COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf
