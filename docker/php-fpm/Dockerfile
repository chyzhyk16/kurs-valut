FROM php:8.2-fpm

RUN apt-get update && apt-get install --no-install-recommends -y libzip-dev

RUN docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

ARG user=admin
ARG group=admin
ARG uid=1000
ARG gid=1000

RUN groupadd -g ${gid} ${group}
RUN useradd -u ${uid} -ms /bin/bash -g ${group} ${user}

COPY . /var/www

COPY --chown=${group}:${user} . /var/www

USER ${user}

EXPOSE 9000