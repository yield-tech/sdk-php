[*← Return to index*](../index.md)

Customer module
===============

**Endpoints:**
- ![query](https://img.shields.io/badge/QUERY-green) [`list($params)`](#-listparams)

**Objects:**
- [`CustomerRow`](#customerrow)
- [`CustomerCreditLineInfo`](#customercreditlineinfo)


Endpoints
---------

### ![query](https://img.shields.io/badge/QUERY-green) `list($params)`

Provides the list of customers under your account, ordered by creation time (newest first).

```php
$params = ['field' => $value, ...];
$customers = $client->customer->list($params);

// or since the parameters are all optional
$customers = $client->customer->list();
```

**Returns:** `Page` of [`CustomerRow`](#customerrow)

**Parameters:**

- `$params`: `array` — See the fields right below.

| Field           | Required? | Type         | Description                                             |
| --------------- | --------- | ------------ | ------------------------------------------------------- |
| `limit`         | Optional  | `int`        | The maximum number of results to return. Default: `10`. |
| `after`         | Optional  | `CursorLike` | See docs on pagination.                                 |
| `customer_code` | Optional  | `string`     | Filter by customer code.                                |


Objects
-------

### `CustomerRow`

| Field            | Type                                                          | Description                                   |
| ---------------- | ------------------------------------------------------------- | --------------------------------------------- |
| `id`             | `string`                                                      | The ID of the customer.                       |
| `registeredName` | `string`                                                      | The official registered name of the customer. |
| `tradeName`      | `string` \| `null`                                            | The trade name of the customer.               |
| `customerCode`   | `string` \| `null`                                            | The customer code assigned to this customer.  |
| `creditLine`     | [`CustomerCreditLineInfo`](#customercreditlineinfo) \| `null` | The credit line of the customer.              |


### `CustomerCreditLineInfo`

| Field             | Type    | Description                                |
| ----------------- | ------- | ------------------------------------------ |
| `creditLimit`     | `Money` | The credit limit.                          |
| `amountAvailable` | `Money` | The amount available for this credit line. |
