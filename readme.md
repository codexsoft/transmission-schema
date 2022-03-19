# CodexSoft Transmission

Compact extendable library to build HTTP API based on JSON.

Provides normalization and validation features based on top of [symfony/validator](https://symfony.com/doc/current/components/validator.html) component.

## Installation

```shell script
composer require codexsoft/transmission
```

## Why

Suppose we have incoming data like this in our controller:

```php
$input = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'pets' => [
        [
            'kind' => 42,
            'name' => 'Rex',
        ],
        [
            'kind' => 34,
            'name' => 'Sunny',
        ]
    ],
];
```

- How will we check that `pets` present in `$input`?
- How will we check that `name` is not empty or contains at least 3 symbols?
- How will we check that `email` is formally correct?
- How will we check that `pets` has at least one pet?
- How will we check that `pets[n].kind` contains one of allowed values (15, 34, 42)?
- How will we handle these violations and generate error response?

This library allows to do this:

```php
use CodexSoft\Transmission\Schema\Accept;

$schema = Accept::json([
    'name' => Accept::string()->minLength(3),
    'email' => Accept::email(),
    'pets' => Accept::collection(
        Accept::json([
            'kind' => Accept::integer()->choices([15, 34, 42]),
            'name' => Accept::string(),
        ])
    ),
]);

$result = $schema->validateNormalizedData($userInput);
if ($result->getViolations()->count()) {
    return new JsonResponse($result->getViolations());
}

$data = $result->getData();
```

That's it! We have now normalized data that we can process further.

## Introduction

This library supports numerous scalar data type definitions and two complex type definitions: arrays
("collections") and hash ("json") out the box. Data type definition called `Element` in this library.
You can add your own data types by extending `AbstractElement` class.

To use specific element you should first instantiate it by just calling:

```php
use CodexSoft\Transmission\Schema\Elements\BoolElement;

$element = new BoolElement();
```

or by using `Accept` fascade:

```php
use CodexSoft\Transmission\Schema\Accept;

$boolean = Accept::bool();
```

Every element has some basic attributes:
- is it nullable or not
- is it required or not
- what is default value for optional elements
- should be value type strictly match element data type or can be automatically converted
- is it deprecated* or not
- label* (or, comment)

More specific elements have more specific attributes. For example: minimal length for strings, max value
for integer, can be string blank, etc.

When strict type check enabled for integer, '42' will generate violation for this element.

List of all built-in elements:

| Element           | Value example                          |
|-------------------|----------------------------------------|
| BoolElement       | true, false                            |
| CollectionElement | [1, 2, 3]                              |
| DateElement       | '2020-05-25'                           |
| DateTimeElement   | '2020-05-10 12:34:56'                  |
| EmailElement      | 'some@example.com'                     |
| FloatElement      | 42.4                                   |
| IntegerElement    | 42                                     |
| JsonElement       | ['hello' => 'world']                   |
| NumberElement     | 42.4, 55                               |
| ScalarElement     | 'hello', 42, true                      |
| StringElement     | 'hello world'                          |
| TimeElement       | '12:34:56'                             |
| TimestampElement  | 1541508448                             |
| UrlElement        | https://example.com                    |
| UuidElement       | 'a8d8f871-481f-436f-b22f-6598f89635ca' |

## Usage

Basic normalizing for boolean data.

```php
use CodexSoft\Transmission\Schema\Accept;

$userInput = 'test';

$boolean = Accept::bool();
$result = $boolean->validateNormalizedData($userInput);
$result->getViolations()->count(); // 0
$result->getData(); // true

$strictBoolean = Accept::bool()->strict();
$result = $boolean->validateNormalizedData($userInput);
$result->getViolations()->count(); // 1
$result->getData(); // 'test'
```

* `label` and `deprecated` attributes exists for historical reasons, because this library started as
  unified schema description for further OpenApi schema generation (check [codexsoft/transmission-openapi3](https://github.com/codexsoft/transmission-openapi3)).

Always check violations count before using normalized data, as
in that case original input data will be stored in `result.data`.

No exceptions are trown automatically but you can write a wrapper to force throwing exceptions
in case of violations detected or handle this case in another way.

## Testing

```shell script
php ./vendor/bin/phpunit
```
