# Mazzuma-PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/BaffourAdu/Mazzuma-PHP.svg?branch=master)](https://travis-ci.org/BaffourAdu/Mazzuma-PHP)
[![Code Coverage](https://scrutinizer-ci.com/g/BaffourAdu/Mazzuma-PHP/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/BaffourAdu/Mazzuma-PHP/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/BaffourAdu/Mazzuma-PHP/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/BaffourAdu/Mazzuma-PHP/?branch=master)
[![Total Downloads][ico-downloads]][link-downloads]

A library for consuming Mazzuma's payment API to send or recieve Mobile Money for a PHP Application. 

## Install

Via Composer

``` bash
$ composer require baffouradu/mazzuma
```

## Usage

``` php
require "vendor/autoload.php";

use \BaffourAdu\Mazzuma\MazzumaPayment;

//Replace this with API key as obtained from https://dashboard.mazzuma.com/
$APIKey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxx";

$payment = new MazzumaPayment($APIKey);

try {
    $response = $payment->transfer('MTN_TO_MTN')
                ->amount(1) 
                ->from('05xxxxxx')
                ->to('02xxxxxx')
                ->send();

    if ($payment->isSuccessful()) {
        /* $response holds the original
        structure of Mazzuma's API Response */
        echo json_encode($response);
    } else {
        /* $response holds the original
        structure of Mazzuma's API Response */
        echo json_encode($response);
    }  
} catch (Exception $e) {
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
[ico-downloads]: https://img.shields.io/packagist/dt/BaffourAdu/mazzuma.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/BaffourAdu/mazzuma
[link-downloads]: https://packagist.org/packages/BaffourAdu/mazzuma
[link-author]: https://twitter.com/BaffourBoampong
[link-contributors]: ../../contributors
