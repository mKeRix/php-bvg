# php-bvg
[![Build Status](https://travis-ci.org/mKeRix/php-bvg.svg?branch=master)](https://travis-ci.org/mKeRix/php-bvg)
[![Coverage Status](https://coveralls.io/repos/github/mKeRix/php-bvg/badge.svg?branch=master)](https://coveralls.io/github/mKeRix/php-bvg?branch=master)
[![Total Downloads](https://poser.pugx.org/mkerix/php-bvg/downloads)](https://packagist.org/packages/mkerix/php-bvg)
[![Latest Stable Version](https://poser.pugx.org/mkerix/php-bvg/v/stable)](https://packagist.org/packages/mkerix/php-bvg)
[![Latest Unstable Version](https://poser.pugx.org/mkerix/php-bvg/v/unstable)](https://packagist.org/packages/mkerix/php-bvg)
[![License](https://poser.pugx.org/mkerix/php-bvg/license)](https://packagist.org/packages/mkerix/php-bvg)

An unofficial API for the BVG (Berlin Public Transportation Services) for PHP.

## Installation

This package can be found on [Packagist](https://packagist.org/packages/mkerix/php-bvg) and is best loaded using [Composer](http://getcomposer.org/).

```
$ composer require mkerix/php-bvg
```

## Usage

You can search for the ID of a station using a search term:

```php
$stations = BVGApi\Station::getStations('alexanderplatz');
```

With the ID you can get the departures starting at a given point in time, which is symbolized by a [Carbon](https://github.com/briannesbitt/carbon) object:

```php
$time = \Carbon\Carbon::create(2016, 3, 21, 12, 0, 0, 'Europe/Berlin');
$departures = BVGApi\Station::getDepartures(9100003, $time);
```
