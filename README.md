![](https://banners.beyondco.de/Laravel-Binance-API.png?theme=light&packageManager=composer+require&packageName=techtailor%2Flaravel-binance-api&pattern=architect&style=style_2&description=A+laravel+wrapper+for+the+Binance+API.&md=1&showWatermark=0&fontSize=100px&images=server)

[![GitHub release](https://img.shields.io/github/release/techtailor/laravel-binance-api.svg?include_prereleases&style=for-the-badge&&colorB=7E57C2)](https://packagist.org/packages/techtailor/laravel-binance-api)
[![GitHub issues](https://img.shields.io/github/issues/TechTailor/Laravel-Binance-Api.svg?style=for-the-badge)](https://github.com/TechTailor/Laravel-Binance-Api/issues)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge&&colorB=F27E40)](license.md)
[![Total Downloads](https://img.shields.io/packagist/dt/techtailor/laravel-binance-api.svg?style=for-the-badge)](https://packagist.org/packages/techtailor/laravel-binance-api)

This package provides a Laravel Wrapper for the [Binance API](https://binance-docs.github.io/apidocs/spot) and allows you to easily communicate with it.

 ---
#### Important Note
This package is in early development stage. It is not advisable to use it in a production app until **`v1.0`** is released. Feel free to open a PR to contribute to this project and help me reach a production ready build.

---

### Installation

You can install the package via composer:

```bash
composer require techtailor/laravel-binance-api
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="binance-api-config"
```

Open your `.env` file and add the following (replace ``YOUR_API_KEY`` and ``YOUR_SECRET`` with the API Key & Secret you received from [Binance](https://www.binancezh.top/en/support/faq/360002502072)) -
```php
BINANCE_KEY=YOUR_API_KEY
BINANCE_SECRET=YOUR_SECRET
```
Or

Open the published config file available at `config/binance-api.php` and add your API and Secret Keys:

```php
return [
    'auth' => [
        'key'        => env('BINANCE_KEY', 'YOUR_API_KEY'),
        'secret'     => env('BINANCE_SECRET', 'YOUR_SECRET')
    ],
];
```

### Usage

Using this package is very simple. Just initialize the Api and call one of the available methods: 
```php
use TechTailor\BinanceApi\BinanceAPI;

$binance = new BinanceAPI();

$time = $binance->getTime();
```

You can also set an API & Secret for a user by passing it after initalization (useful when you need to isolate api keys for individual users):

```php
$binance = new BinanceApi();

$binance->setApi($apiKey, $secretKey);

$accountInfo = $binance->getAccountInfo();
```

### Available Methods

Available Public Methods (Security Type : `NONE`) **[API Keys Not Required]**
```
- getSystemStatus()         // returns system status, expect msg to be "normal".
- getTime()                 // returns server timestamp.
- getExchangeInfo($symbol)  // returns current exchange trading rules and symbol information.
- getOrderBook($symbol)     // returns the order book for the symbol.
- getAvgPrice($symbol)      // returns the average price for a symbol.
- getTicker($symbol)        // returns the 24hr ticker for a symbol (if no symbol provided, returns an array of all symbols).
```
Available Private Methods (Security Type : `USER_DATA`) **[API Keys Required]**
```
- getAccountInfo()          // returns current account information.
- getAllOrders()            // return all current account orders (active, canceled or filled).
- getOpenOrders($symbol)    // returns all current account open orders (Careful when accessing without symbol).
- getTrades($symbol)        // returns all trades for a symbol.
- getOrderStatus($symbol, $orderId) // returns status of a given order.
- getUserCoinsInfo()        // returns information of all coins available to the user.
- getDepositHistory()       // returns the user's deposit history.
- getWithdrawHistory()      // returns the user's withdraw history.
```

### TODO

List of features or additional functionality we are working on (in no particular order) -

```bash
- Improve exception handling.
- Add rate limiting to API Calls.
- Add response for API ban/blacklisting response.
- Improve ReadMe.
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

### Credits

- [Moinuddin S. Khaja](https://github.com/TechTailor)
- [All Contributors](../../contributors)

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
