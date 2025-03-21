# lontra-validator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

The lontra-validator is a validator package, a complement to laminas-validator, compatible with validation for laminas-form. It provides an OOP approach.

```
ArrayRecordExists - Check if values in array exists on database
DateBetween - Checks whether the date is between values entered.
DateGreaterThan - Checks if date is greater
DateLessThan - Checks if date is less
EndsWith - If text ends with a value
IsArray - If value is a valid array
Password - Checks whether the entered value is a valid password with the options uppercase, lowercase, number, special characters.
StartsWith - If text starts with a value
WordCount - Validate the number of words in a string
Validator - This class has tools for using laminas-validator and lontra-validator validations
```

## Dependencies

lontra-validator depends on laminas-validator (and suggested(but not required) laminas-db to compare with the database), maintained by the Linux Foundation

## Install

Via Composer

```bash
$ composer require cledilsonweb/lontra-validator
```

## Usage

```php
$validator = new DateBetween([
    'max' => '2020-10-10',
    'min' => '2020-05-05',
    'format' => 'Y-m-d',
    'inclusive' => true
]);
echo $validator->isValid('2020-06-06'); //true

// Using static validation
echo Validator::isValid(DateBetween::class, '2020-06-06', ['max' => '2020-10-10', 'min' => '2020-05-05']); //true
// Or
echo Validator::validateDateBetween('2020-06-06', ['max' => '2020-10-10', 'min' => '2020-05-05']); //true
```

It is possible to use the validator on the Laminas Form with InputFilter

```php
$inputFilter->add(
    [
        'name' => 'input_name',
        'required' => true,
        'filters' => // your filters...,
        'validators' => [
            [
                'name' => DateBetween::class
                'options' => [
                    'max' => '2020-10-10',
                    'min' => '2020-05-05',
                    'format' => 'Y-m-d',
                    'inclusive' => true
                ]
            ]
        ]
    ]
);
```

To customize the validation message:

```php
$inputFilter->add(
    [
        'name' => 'input_name',
        'required' => true,
        'filters' => // your filters...,
        'validators' => [
            [
               'name' => DateGreaterThan::class, 'options' => [                        
                'format' => 'Y-m-d',                        
                'min' => 'now',                        
                'messages' => [                            
                    DateGreaterThan::NOT_GREATER_INCLUSIVE => 'A data informada deve ser maior ou igual a data atual',                            DateGreaterThan::NOT_GREATER           => "A data informada deve ser maior que a data atual",                            DateGreaterThan::IVALID_FORMAT         => "A data informada está no formato inválido",                            DateGreaterThan::IVALID_DATE           => "A data informada é inválida",                        
                    ]                    
                ]
            ]
        ]
    ]
);

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

To run the integration test, you need to enable `extension=pdo_sqlite`

## Suggestions and Security

If you discover any security related issues or have any suggestions, please [create a new issue][new-issue].

## Credits

- [Cledilson Nascimento][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/cledilsonweb/lontra-validator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/cledilsonweb/lontra-validator.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/cledilsonweb/lontra-validator
[link-downloads]: https://packagist.org/packages/cledilsonweb/lontra-validator
[link-author]: https://github.com/cledilsonweb
[link-contributors]: ../../contributors
[new-issue]: https://github.com/cledilsonweb/lontra-validator/issues/new
