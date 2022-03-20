FROM bertrandkhe/drupal:9.3-fpm-alpine

ARG WWW_UID=82
ARG WWW_GID=82

RUN set -eux; \ 
  sed -i "s/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g" /etc/apk/repositories; \
  apk add --no-cache shadow patch zip mariadb-client; \
  echo "${WWW_UID}:${WWW_GID}" > /tmp/id; \
  \
  if [ ${WWW_GID} -gt 500 ]; then \
    groupmod -g ${WWW_GID} www-data; \
  fi; \
  \
  if [ ${WWW_UID} -gt 500 ]; then \
    usermod -u ${WWW_UID} www-data; \
  fi; \
  \
  apk del shadow; \
  mkdir -p /tmp/drupal /opt/drupal/web/sites/default/files; \
  chown -R www-data:www-data /home/www-data /tmp/drupal /opt/drupal/web/sites; \
  rm -rf /opt/drupal/web/core;


COPY ./docker/drupal/etc /tmp/etc
RUN set -eux; \
 cp /tmp/etc/php.ini-production /usr/local/etc/php/php.ini;

COPY src/composer.json src/composer.lock /opt/drupal/
COPY ./src/patches /opt/drupal/patches

ARG ENVIRONMENT

RUN set -eux; \
  \
  if [ "${ENVIRONMENT}" == "development" ]; then \
    exit 0; \
  fi; \
  \
  composer install --no-dev;

COPY ./src/config_files /opt/drupal/config_files
COPY ./src/web/modules/custom /opt/drupal/web/modules/custom
COPY ./src/web/themes/custom /opt/drupal/web/themes/custom
COPY ./src/web/robots.txt /opt/drupal/web/robots.txt
COPY ./src/web/files.php /opt/drupal/web/files.php