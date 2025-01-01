# LaraSettings

## Description
LaraSettings is a Laravel package that simplifies the management of application settings. It provides a structured and extensible way to define, retrieve, and update settings stored in the database. It includes useful commands for generating and seeding settings classes, making it easy to customize for various use cases.

---

## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Usage](#usage)
   - [Defining a Settings Class](#defining-a-settings-class)
   - [Seeding Settings](#seeding-settings)
   - [Retrieving and Updating Settings](#retrieving-and-updating-settings)
   - [Strict Mode](#strict-mode)
4. [Contributing](#contributing)
5. [Maintainers](#maintainers)
6. [License](#license)

---

## Requirements
- PHP 8.0+
- Laravel 9.0+

---

## Installation
1. Install the package via Composer:
   ```bash
   composer require utyemma/larasettings
   ```

2. Publish the migration file:
   ```bash
   php artisan vendor:publish --tag=larasettings-migrations
   ```

3. Run the migrations to create the `settings` table:
   ```bash
   php artisan migrate
   ```

---

## Usage

### Defining a Settings Class
Use the `make:setting` command to create a new settings class:
```bash
php artisan make:setting ExampleSettings
```
This will generate a new settings class in the `App\Settings` namespace. Define your options, labels, and casts within the class to tailor it to your application's needs.

### Seeding Settings
To seed settings into the database, use the `settings:seed` command:
```bash
php artisan settings:seed --class=ExampleSettings
```
Replace `ExampleSettings` with the name of your settings class. This will populate the database with the default attributes and values defined in the class.

### Retrieving and Updating Settings
You can interact with settings using dynamic property access or methods:

#### Retrieving a Setting
```php
$exampleSettings = app(\App\Settings\ExampleSettings::class);
$value = $exampleSettings->some_key; // Retrieve the value of a setting
```

#### Updating a Setting
```php
$exampleSettings->some_key = 'New Value'; // Update the value of a setting
```

#### Bulk Updating Settings
```php
$exampleSettings->update([
    'key1' => 'Value1',
    'key2' => 'Value2',
]);
```

#### Fetching All Settings
```php
$settingsArray = $exampleSettings->toArray();
```

### Strict Mode
LaraSettings operates in strict mode by default. Strict mode ensures that only settings defined in the `options` array of a settings class can be updated or created. This prevents accidental changes to undefined settings. When enabled, attempting to update an undefined setting will throw an exception. Ensure that all valid keys are listed in the `options` array of your settings class.

#### Disabling Strict Mode
If you want to allow dynamic creation of settings that are not predefined in the `options` array, you can set the `$strict` property to `false` in your settings class:
```php
protected $strict = false;
```

---

## Contributing
Contributions are welcome! To contribute:
1. Fork the repository.
2. Create a feature branch.
3. Submit a pull request.

---

## Maintainers
- Utibe-Abasi Emmanuel ([GitHub](https://github.com/UtyEmma))

---

## License
This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
