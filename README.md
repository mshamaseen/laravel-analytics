Laravel analytics is an analytic tool to track conversions, weather you want to track visitors of your website or other conversions such us registration, subscription, or any type of action, then Laravel analytics is what you are looking for.

Laravel analytics will also record the user\guest device information, such as the device type, os, browser, version, language, city, country, continent, timezone for statistics. 

NOTE: this package does NOT use Google Analytics, if you want Google analytics use [spatie/laravel-analytics](https://github.com/spatie/laravel-analytics) package instead.

<br/>

## installation

Run

``
composer install shamaseen/laravel-analytics
``

Publish the config file by running

``
php artisan vendor:publish --provider="Shamaseen\Analytics\ServiceProvider"
``

and at last run

``
php artisan migrate
``

to run the migration files.

<br/>


| :information_source: the migration will add two tables to your database, la_conversions and la_device_info|
| --- |

<br/>

## Usage

### Create
Create conversion:

```
Shamaseen\Analytics\Models\LaConversion::conversion($name,$weight = 100,$source = null, $force = false );
```

<br/>


| :information_source: By default the package will check for conversions made with the same name within a threshold time defined in the config, if one found then the package will NOT insert the conversion to the database, to force insertion you should add send the force parameter as true  |
| --- |

<br/>


To create conversion for a model, use ``Shamaseen\Analytics\Traits\Conversionable`` as a trait in the model you want, then: 

```PHP
 yourModelInstance->la_conversions()->create([
        'name' => 'String: Required field',
        'weight' => 'Int: Optional field',
        'source' => 'String: Optional field',
        'force' => 'Boolean: Optional field, set this to true to force the insertion ',
    ])
```
<br/>

### Statistics

To get statistics about the conversions create an instance of the Statistics class

```PHP
$statistics = new \Shamaseen\Analytics\Repositories\Statistics($name, $start_at = null, $end_at = null)
```

or from yourModelInstance, like

```PHP
 yourModelInstance->la_statistics($name,$start_at = null, $end_at = null)
```

<br/>

| :information_source: If you call the statistics from your model then all the returned statistics will be the ones related to that model only.  |
| --- |

<br/>



By default, all method will return data about the current month only, if you want to adjust the date, set the start at and the end at when instancing the Statistics class.

Now you can run methods like:

```PHP
    $statistics->sourcesCount()
```

```PHP
    $statistics->citiesCount()
```

```PHP
    $statistics->countriesCount()
```

```PHP
    $statistics->continentCount()
```

```PHP
    $statistics->timezoneCount()
```

```PHP
    $statistics->conversionsOverTime()
```

<br/>


### Extending the methods

You can always run your custom queries like you always do using the `LaConversion` model, but remember, this is an open source project, so new methods to the statistics class is always welcomed to be added, just make a request :).

<br/>


### Statistics Data type

By default, statistics methods will return Laravel Collections, if you want to return arrays instead call:
```PHP
$statistics->setResponse(\Shamaseen\Analytics\Repositories\Statistics::$ARRAY_RESPONSE)
```

<br/>


## License

This project is an open source project licensed under **MIT**.
