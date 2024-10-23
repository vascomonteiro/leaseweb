# LeaseWeb test assessment
## Docker PHP 8.3
docker-compose -f docker-compose-leaseweb.yml up

## Install vendors 
php composer.phar install
php bin/console importmap

### Deploy assets (public folder)
php bin/console asset-map:compile

## Application detail
### Frontend
* Twig, bootstrap, jquery, datatables, ion-rangeslider  
* Dynamic data rendering for: RAM, Locations and HDDStorage  

### Backend
* Symfony 6.4, SimpleXLSX, asset-mapper, cache, phpunit  
* Dynamic data rendering for: RAM, Locations and HDDStorage  
* Use cache (1h refresh or new upload) to improve performance  

## TODO
### FRONTEND
* Form login to allow file upload  
* Remove Search button: filter auto update on parameters change  
* Datatable multiple pages data  
* Currency selector  

### BACKEND
* Currency converter ($, €, ...)
* Interface to allow multiple data source  
* Clean code  
* Add anotation PHPDOC  
* Complete PhpUnit code coverage 