# eloquent-uuid
An Eloquent UUID Trait to use with Laravel > 5.6

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lucadello91/eloquent-uuid.svg?style=flat-square)](https://packagist.org/packages/lucadello91/eloquent-uuid)
[![Build Status](https://img.shields.io/travis/lucadello91/eloquent-uuid/master.svg?style=flat-square)](https://travis-ci.org/lucadello91/eloquent-uuid)
[![StyleCI](https://github.styleci.io/repos/222470946/shield?branch=master)](https://github.styleci.io/repos/222470946)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lucadello91/eloquent-uuid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lucadello91/eloquent-uuid/?branch=master)
[![MIT licensed](https://img.shields.io/github/license/lucadello91/eloquent-uuid?style=flat-square)](https://img.shields.io/github/license/lucadello91/eloquent-uuid)
[![Total Downloads](https://img.shields.io/packagist/dt/lucadello91/eloquent-uuid.svg?style=flat-square)](https://packagist.org/packages/lucadello91/eloquent-uuid)


## Installation

    composer require lucadello91/eloquent-uuid

#### In your models

For using uuid in your Eloquent Model, just put `use UuidModelTrait;`:

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class User extends Model
{
    use UuidModelTrait;
}
```

This package will use UUID v4 values by default.
You can use `uuid1`, `uuid3`, `uuid4`, `uuid5` or `ordered` by setting the protected property `$uuidVersion` in your Eloquent Model. 

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class Post extends Model
{
    use UuidModelTrait;

    protected $uuidVersion = 'uuid5';
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

#### Binary Uuid

If you prefer to use a binary UUID in your database, you just need to cast your primary key to `uuid`

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class User extends Model
{
    use UuidModelTrait;
    
    protected $keyType = 'uuid';
    
    //or
    
    protected $casts = [
        'id' => 'uuid',
    ];
}
```

## Running tests

To run the tests, just run `composer install` and `./vendor/bin/phpunit`.

### Changelog

Please see [CHANGELOG](CHANGELOG) for more information what has changed recently.


## Credits

- [Luca Dell'Orto](https://github.com/lucadello91)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.