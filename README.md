# Russian Post API for Laravel 5.3+

Package for working with API Russian Post.

![pochta ru](https://cloud.githubusercontent.com/assets/10347617/23131256/ab7305e8-f7cc-11e6-85e5-0a0223c5955d.jpg)

<p align="center">
<a href="https://travis-ci.org/andrey-helldar/pochta"><img src="https://travis-ci.org/andrey-helldar/pochta.svg?branch=master&style=flat-square" alt="Build Status" /></a>
<a href="https://packagist.org/packages/andrey-helldar/pochta"><img src="https://img.shields.io/packagist/dt/andrey-helldar/pochta.svg?style=flat-square" alt="Total Downloads" /></a>
<a href="https://packagist.org/packages/andrey-helldar/pochta"><img src="https://poser.pugx.org/andrey-helldar/pochta/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
<a href="https://packagist.org/packages/andrey-helldar/pochta"><img src="https://poser.pugx.org/andrey-helldar/pochta/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
<a href="https://github.com/andrey-helldar/pochta"><img src="https://poser.pugx.org/andrey-helldar/pochta/license?format=flat-square" alt="License" /></a>
</p>


<p align="center">
<a href="https://github.com/andrey-helldar/pochta"><img src="https://img.shields.io/scrutinizer/g/andrey-helldar/pochta.svg?style=flat-square" alt="Quality Score" /></a>
<a href="https://styleci.io/repos/82571643"><img src="https://styleci.io/repos/82571643/shield" alt="StyleCI" /></a>
<a href="https://php-eye.com/package/andrey-helldar/pochta"><img src="https://php-eye.com/badge/andrey-helldar/pochta/tested.svg?style=flat-square" alt="PHP-Eye" /></a>
</p>


## Installation

Require this package with composer using the following command:

```
composer require andrey-helldar/pochta
```

or

```json
{
    "require": {
        "andrey-helldar/pochta": "~1.0"
    }
}
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Helldar\Pochta\PochtaServiceProvider::class,
```


You can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\Pochta\PochtaServiceProvider"
```


## Configuration

See at `config/pochta.php`:

    `api_url_one`   -   Адрес для Единичного доступа.
    `api_login`     -   Логин для доступа к API Сервиса отслеживания.
    `api_password`  -   Пароль для доступа к API Сервиса отслеживания.


## Using

```php
echo \Helldar\Pochta\Tracking::one('XX123456789123');
    // XX123456789123 - Track ID
```


## Copyright and License

Sitemap was written by Andrey Helldar for the Laravel Framework 5.3 or later, and is released under the MIT License. See the LICENSE file for details.
