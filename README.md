# Settings

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

### Anual settings

Settings which have different values per year can be managed with the `AnualSettings` class and the `AnualValue` class.

```
$first2019 = new AnualValue(1, 'First 2019', 2019);
$second2019 = new AnualValue(2, 'Second 2019', 2019);
$first2020 = new AnualValue(1, 'First 2020', 2020);
$second2020 = new AnualValue(2, 'Second 2020', 2020);

$settings = new AnualSettings([$first2019, $second2019, $first2020, $second2020]);
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
