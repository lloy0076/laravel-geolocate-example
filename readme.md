# GeoLocate Test

This is a simple Laravel 5 application to test GeoLocation via arbitrary IPv4
addresses.

# Installation

* Install node globally
* Install npm globally
* Install webpack globally
* Install composer globally
* Run a composer install
* Run an npm install
* Run webpack
* Run the application.

# Getting the Geoip2 Database

Download the **GeoLite2 City** database from:

* http://dev.maxmind.com/geoip/geoip2/geolite2/

Extract the file named `GeoLite2-City.mmdb` to the `resources\geoip2`, creating
the folder if necessary.

To test the installation, the command `php artisan optimize` should complete
without error (if the database cannot be found, a relevant error will be
displayed).

# About Laravel

See http://laravel.com/.

# License

Apache License 2.0.

Please be aware of the license described (here)[http://dev.maxmind.com/geoip/geoip2/geolite2/].
