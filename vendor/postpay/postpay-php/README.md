<p align="center">
  <a href="https://github.com/postpayio/postpay-php/releases"><img src="https://img.shields.io/github/release/postpayio/postpay-php.svg" alt="Latest Version" /></a> <a href="https://travis-ci.com/postpayio/postpay-php"><img src="https://img.shields.io/travis/postpayio/postpay-php.svg" alt="Build Status" /></a> <a href="https://scrutinizer-ci.com/g/postpayio/postpay-php/"><img src="https://scrutinizer-ci.com/g/postpayio/postpay-php/badges/quality-score.png?b=master" alt="Scrutinizer" /></a> <a href="https://scrutinizer-ci.com/g/postpayio/postpay-php/"><img src="https://scrutinizer-ci.com/g/postpayio/postpay-php/badges/coverage.png?b=master" alt="Coverage" /></a>
</p>

# Postpay SDK for PHP

PHP library for [Postpay](https://postpay.io) API.

## Installation

The recommended way to install *postpay-php* is through [Composer](https://getcomposer.org/):

```sh
composer require postpay/postpay-php
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Quickstart

All configs are passed around as a single variable `config`:

```php
$postpay = new \Postpay\Postpay([
    'merchant_id' => 'id_ ...',
    'secret_key' => 'sk_live_ ...',
]);
```

## Documentation

Fantastic documentation is available at [https://php.postpay.io](https://php.postpay.io).
