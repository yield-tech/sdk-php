Yield SDK for PHP [![Packagist Version](https://img.shields.io/packagist/v/yield-tech/sdk-php)](https://packagist.org/packages/yield-tech/sdk-php)
=================

The official [Yield](https://www.paywithyield.com) SDK for PHP.


Documentation
-------------

- [API reference](https://github.com/yield-tech/sdk-php/blob/main/docs/index.md)


Installation
------------

```sh
composer require yield-tech/sdk-php
```


Usage
-----

```php
// For security, don't save the actual key in your code or repo
$client = new YieldTech\SdkPhp\Client(getenv('YIELD_API_KEY'));

// Fetch an existing order
$order = $client->order->fetch('ord_...');
print_r($order->customer->registeredName);

// Or create a new one
$newOrder = $client->order->create([
    'customer_id' => 'org_...',
    'total_amount' => 'PHP 1234.50',
    'note' => 'Test order from the PHP SDK!',
]);
```

For more details, check out our [API reference](https://github.com/yield-tech/sdk-php/blob/main/docs/index.md).
