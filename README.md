# City of Learning Client

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Here is a PHP client for the [City of Learning API][badgekit-api].

## Install

Via Composer

``` bash
$ composer require caxy/cityoflearning-client
```
## Usage

``` php

$client = new GuzzleHttp\Client([
  'base_uri' => 'https://col.example.com'
]);
$middleware = new Caxy\CityOfLearning\JwtMiddleware('API_TOKEN', 'API_SECRET');

$stack = $client->getConfig('handler');
$stack->push(GuzzleHttp\Middleware::mapRequest($middleware));

$serviceClient = new Caxy\CityOfLearning\ServiceClient($client);
$command = $serviceClient->getCommand('get_reviews', [
  'system' => 'example',
  'application' => '235f684c5e5f88f1575434403adc2562',
  'badge' => 'a-groovy-badge',
]);
$result = $serviceClient->execute($command);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email bdoherty@caxy.com instead of using the issue tracker.

## Credits

- [Benjamin Doherty][link-author] of [Caxy Consulting][link-organization]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[badgekit-api]: https://github.com/mozilla/badgekit-api

[ico-version]: https://img.shields.io/packagist/v/caxy/badgekit-client.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/caxy/badgekit-client/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/caxy/badgekit-client.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/caxy/badgekit-client.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/caxy/badgekit-client.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/caxy/badgekit-client
[link-downloads]: https://packagist.org/packages/caxy/badgekit-client
[link-author]: https://github.com/bangpound
[link-organization]: https://github.com/caxy
[link-contributors]: ../../contributors
