# Docker PHP 8.3
docker-compose -f docker-compose-leaseweb.yml up

# Install vendors 
php composer.phar install
php bin/console importmap

## Deploy assets (public folder)
php bin/console asset-map:compile

