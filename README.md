# The official Yield SDK for PHP

## Installation

```sh
composer require yield-tech/sdk-php
```

## Usage

```php
// for security, never commit the actual key in your code
$client = new YieldTech\SdkPhp\Client(getenv('YIELD_API_KEY'));

// fetch an existing order
$order = $client->order->fetch('ord_...');
var_dump($order->customer->registeredName);

// or create a new one
$newOrder = $client->order->create([
    'customer_id' => 'org_...',
    'total_amount' => 'PHP 1234.50',
    'note' => 'Test order from the PHP SDK!',
]);
```
