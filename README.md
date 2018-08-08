# Mazzuma-PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A library for consuming Mazzuma's payment API for sending or receiving Mobile Money for an PHP Application. 

## Install

Via Composer

``` bash
$ composer require BaffourAdu/mazzuma
```

## Usage

``` php
require "vendor/autoload.php";

use \BaffourAdu\Mazzuma\MazzumaPayment;

$APIKey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$payment = new MazzumaPayment($APIKey);

try {
    $response = $payment::recieve('MTN_TO_MTN')
                ->amount(1)
                ->from('054xxxxxxx')
                ->to('054xxxxxxxx')
                ->now();

    echo $response;
}
    
//catch exception
catch (Exception $e) {
    echo 'Message: ' .$e->getMessage();
}

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email baffouraduboampong@gmail.com instead of using the issue tracker.

## Credits

- [Baffour Adu Boampong][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/BaffourAdu/mazzuma.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/BaffourAdu/mazzuma/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/BaffourAdu/mazzuma.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/BaffourAdu/mazzuma.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/BaffourAdu/mazzuma.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/BaffourAdu/mazzuma
[link-travis]: https://travis-ci.org/BaffourAdu/mazzuma
[link-scrutinizer]: https://scrutinizer-ci.com/g/BaffourAdu/mazzuma/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/BaffourAdu/mazzuma
[link-downloads]: https://packagist.org/packages/BaffourAdu/mazzuma
[link-author]: https://twitter.com/BaffourBoampong
[link-contributors]: ../../contributors
