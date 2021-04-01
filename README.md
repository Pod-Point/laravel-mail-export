# Laravel Mail Export

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pod-point/laravel-mail-export.svg?style=flat-square)](https://packagist.org/packages/pod-point/laravel-mail-export)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/pod-point/laravel-mail-export/run-tests?label=tests)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/pod-point/laravel-mail-export.svg?style=flat-square)](https://packagist.org/packages/pod-point/laravel-mail-export)

This package can export any mail sent with Laravel's `Mailable` class to any desired filesystem disk and path as a `.eml` file.

This can be useful when wanting to store emails sent for archive purposes.

## Installation

You can install the package via composer:

For Laravel 5.x and 6.x

```bash
composer require pod-point/laravel-mail-export:^0.1
```

For Laravel 7.x and 8.x

```bash
composer require pod-point/laravel-mail-export:^0.2
```

### Publishing the config file

The configuration for this package comes with some sensible values but you can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="PodPoint\MailExport\MailExportServiceProvider"
```

You will be able to specify:

* `enabled`: wether this package is enabled or not.
* `disk`: which disk to use by default. `null` will use the default disk from your application filesystem.
* `path`: the default path you would like to export your mails within a storage disk.

See our [`config/mail-export.php`](config/mail-export.php) for more details.

## Usage

Simply add the `Exportable` trait and the `ShouldExport` interface to any Mailable class that you want to persist into any storage disk.

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class OrderShipped extends Mailable implements ShouldExport
{
    use Exportable;
    
    // ...
}
```

This will use the default filesystem `disk` and `path` from the configuration and will also generate a unique `filename` for you.

The default filename is using a timestamp, the mail recipients, the subject and will look like so:

```
2021_03_26_150142_jane_at_example_com_this_is_the_subject.eml
```

You can also specify the `disk`, `path` or `filename` to use for a specific Mailable using properties:

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class OrderShipped extends Mailable implements ShouldExport
{
    use Exportable;
    
    public $exportDisk = 'some_disk';

    public $exportPath = 'some_path';

    public $exportFilename = 'some_filename';
    
    // ...
}
```

You can also use methods if you need more flexibility:

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class OrderShipped extends Mailable implements ShouldExport
{
    use Exportable;
    
    // ...
    
    public function exportDisk(): string
    {
        return 'some_disk';
    }

    public function exportPath(): string
    {
        return 'some_path';
    }

    public function exportFilename(): string
    {
        return 'some_filename';
    }
}
```

Then you can keep using your `Mailable` as usual:

```php
Mail::to($request->user())->send(new OrderShipped($order));
```

Even with Notifications too:

```php
<?php

namespace App\Notifications;

use App\Mail\OrderShipped as Mailable;
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification
{
    // ...
    
    public function toMail($notifiable)
    {
        return (new Mailable($this->order))->to($notifiable->email);
    }    
}
```

## Testing

Run the tests with:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [themsaid](https://github.com/themsaid) and Spatie's [laravel-mail-preview](https://github.com/spatie/laravel-mail-preview) for some inspiration
- [Laravel Package Development](https://laravelpackage.com) documentation by [John Braun](https://github.com/Jhnbrn90) 
- [Pod Point](https://github.com/pod-point)
- [All Contributors](https://github.com/pod-point/laravel-mail-export/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENCE.md) for more information.

---

<img src="https://d3h256n3bzippp.cloudfront.net/pod-point-logo.svg" align="right" />

Travel shouldn't damage the earth 🌍

Made with ❤️ at [Pod Point](https://pod-point.com)
