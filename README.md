# Laravel Mail Export

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)

A mail exporter for Laravel. This package exports any mail sent with Laravel's `Mailable` class. Simply use 
the `Exportable` trait on the desired email that you would like to export to some storage.

Laravel mail exporter currently only exports the mail as .eml.

## Installation

Run the following command inside the desired project workspace.
```bash
composer require pod-point/laravel-mail-export
```
##### Publish the configuration - Laravel
1. Add the provider to the list of providers on config/app.php

```php
PodPoint\LaravelMailExport\Provider\MailExportServiceProvider::class,
```

2. Publish the config
```php
php artisan vendor:publish --provider="PodPoint\LaravelMailExport\Provider\MailExportServiceProvider" --tag="mail-export"
```

## Usage

Simply add the Exportable trait to the Mailable class that you want to push to storage.

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Exportable;

class OrderShipped extends Mailable
{
    use Exportable;
    
    // ...
}
```

### How to define disk and storage path?

There are 3 different ways of defining which disk and path you'd like to use to store the copy of the email. There is an order of precedence in how the disk and path is used. Class Method -> Class Property -> Config.

#### Class Method
If you need to define the path or disk dynamically then you can add methods to the class that uses the trait. This method of defining the disk and path will overwrite both the Class property and the config file.

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Exportable;

class OrderShipped extends Mailable
{
    use Exportable;
    
    public function getStorageDisk(): string
    {
        return 's3';
    }
    
    public function getStoragePath(): string
    {
        return 'some/path';
    }
}
```

#### Class Property
declaring the storageDisk and storagePath as public properties on the class that uses the trait will allow you to define the disk and path respectively for the exporter to use.

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Exportable;

class OrderShipped extends Mailable
{
    use Exportable;
    
    public $storagePath = 'some/path';
    
    public $storageDisk = 's3';
}
```

#### Config file.
Once the config has been published. You will have some basic array structure.

```php
<?php

return [
    'disk' => 's3',
    'storage' => [
        \some\namespace\OrderShipped::class => [
            'path' => 'some/path',
            'disk' => 's3'
        ],
    ],
];
```

Simply add your class as an array key, a sub array with 2 elements one for disk and another for the path.

### Testing

This project uses PHPUnit, run the following command to run the tests:
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License
The MIT License (MIT). Please see [License File](LICENCE) for more information.
