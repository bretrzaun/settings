# Settings
[![Latest Stable Version](https://poser.pugx.org/bretrzaun/settings/v)](//packagist.org/packages/bretrzaun/settings)
[![Build Status](https://travis-ci.org/bretrzaun/settings.svg?branch=master)](https://travis-ci.org/bretrzaun/settings)
[![License](https://poser.pugx.org/bretrzaun/settings/license)](//packagist.org/packages/bretrzaun/settings)

Manage application settings.

## Installation

Install via [Composer](https://getcomposer.org):
```
composer require bretrzaun/settings
```

## Usage

### 1. Create a settings instance

```
$settings = new BretRZaun\Settings\Settings();
```

### 2. Fill values

```
use BretRZaun\Settings\Value\SimpleValue;

$value1 = new SimpleValue(1, 'Value 1');
$settings->add($value1);
$value2 = new SimpleValue(2, 'Value 2');
$settings->add($value2);
```

The SimpleValue class stores any key/value information.

### 3. Persist values

To persist the settings use a storage class, which implements the `BretRZaun\Settings\Storage\StorageInterface`.
Currently these storages are supported:

- FileStorage
- MongoDbStorage

#### Example 1 - save settings:
```
$storage = new FileStorage('setting.json');
$storage->save($settings);
```

#### Example 2 - load settings:
```
$storage = new FileStorage('setting.json';
$settings = $storage->load(Settings::class);
```

Loads the settings into an instance of the given settings class.

## Advanced usage

### Annual settings

Settings which have different values per year can be managed with the `AnnualSettings` class and the `AnnualValue` class.

```
$first2019 = new AnnualValue(1, 'First 2019', 2019);
$second2019 = new AnnualValue(2, 'Second 2019', 2019);
$first2020 = new AnnualValue(1, 'First 2020', 2020);
$second2020 = new AnnualValue(2, 'Second 2020', 2020);

$settings = new AnnualSettings([$first2019, $second2019, $first2020, $second2020]);
```

To retrieve all settings for 2019 use:

```
$settings->findByYear(2019);

-> [$first2019, $second2019]
```

To get a certain key for a year:
```
$settings->getByYearAndKey(2019, 2);

-> $second2019
```

To get a certain key for a year - with going back to the last existing year:

```
$settings->getLastByYearAndKey(2021, 1);

-> $first2020
```
Note: Since there is no entry for the year 2021 it goes back until it finds a year with the requested key.
