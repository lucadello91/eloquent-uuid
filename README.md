# eloquent-uuid
An Eloquent UUID Trait to use with Laravel > 5.6

[![Latest Version](https://img.shields.io/github/release/lucadello91/eloquent-uuid.svg?style=flat-square)](https://github.com/lucadello91/eloquent-uuid/releases)
[![Build Status](https://img.shields.io/travis/lucadello91/eloquent-uuid/master.svg?style=flat-square)](https://travis-ci.org/lucadello91/eloquent-uuid)
[![StyleCI](https://github.styleci.io/repos/222470946/shield?branch=master)](https://github.styleci.io/repos/222470946)
[![MIT licensed](https://img.shields.io/github/license/lucadello91/eloquent-uuid)](https://img.shields.io/github/license/lucadello91/eloquent-uuid)
[![Total Downloads](https://img.shields.io/packagist/dt/lucadello91/eloquent-uuid.svg?style=flat-square)](https://packagist.org/packages/lucadello91/eloquent-uuid)


## Installation

    composer require lucadello91/eloquent-uuid

#### In your models

For using uuid in your Eloquent Model, just put `use UuidModelTrait;`:

```
<?php

namespace App;

use Lucadello91\EloquentUuid\UuidModelTrait;

class User extends Eloquent
{
    use UuidModelTrait;
}
```

#### Database Schema

When using UuidModelTrait, you have to use `uuid` your **schema** :

```
Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    //...
});
```

## Running tests

To run the tests, just run `composer install` and `./vendor/bin/phpunit`.
