#!/bin/sh

set -eux;

docker-compose -f docker-compose.yml -f docker-compose.development.yml start
docker-compose exec drupal composer require -vvv \
  drush/drush \
  drupal/devel \
  drupal/config_ignore \
  drupal/redis \
  drupal/field_group \
  drupal/examples \
  drupal/pathauto \
  drupal/redirect \
  drupal/simple_sitemap \
  drupal/password_policy \
  illuminate/collections \
  nesbot/carbon

TABLES_COUNT="$(docker-compose exec drupal php vendor/bin/drush sql-cli --extra='-e "SHOW TABLES"'|wc -l)"

if [ $TABLES_COUNT -gt 0 ]; then
  echo "Skip install. Database is not empty."
  exit 0
fi

docker-compose exec drupal php vendor/bin/drush site:install \
  --no-interaction \
  --account-name=admin \
  --account-pass=test1234

docker-compose exec drupal php vendor/bin/drush en \
  --no-interaction \
  devel \
  config_ignore \
  field_group \
  pathauto \
  redirect \
  password_policy \
  simple_sitemap \
  jsonapi \
  media \
  media_library

docker-compose exec drupal php vendor/bin/drush cex